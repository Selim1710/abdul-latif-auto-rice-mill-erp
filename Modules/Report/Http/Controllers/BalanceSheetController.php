<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Transaction;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Mill\Entities\Mill;
use Modules\Report\Entities\BalanceSheet;
use Modules\Transport\Entities\Truck;

class BalanceSheetController extends BaseController
{
    public function __construct(BalanceSheet $model)
    {
        return $this->model = $model;
    }

    public function index()
    {
        if (permission('balance-sheet')) {
            $setTitle = __('file.Balance Sheet');
            $setSubTitle = __('file.Balance Sheet');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
            return view('report::balanceSheet.index');
        } else {
            return $this->access_blocked();
        }
    }

    public function balanceSheetData(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $cohId = ChartOfHead::where(['type' => 1])->get();
        $cashBank = ChartOfHead::where(['head_id' => 2])->whereNotIn('name', ['Cash At Bank', 'Cash At Mobile'])->pluck('id');
//        24, 85, 86, 87, 88, 89, 90, 91, 240, 241, 242, 243, 258, 283, 284, 285, 286, 287, 288, 312, 313, 314, 321, 360, 361, 364, 365, 422, 440, 466, 473, 474, 541, 560, 561
        $cashAndBankBalance = Transaction::whereIn('chart_of_head_id', $cashBank)->selectRaw('SUM(debit) - SUM(credit) as balance')->first();
        $collection = Transaction::whereIn('voucher_type', ['SALE', 'COLLECTION'])->whereIn('chart_of_head_id', $cashBank)->sum('debit');
        $saleValue = Transaction::where('voucher_type', 'SALE')->where('chart_of_head_id', 17)->selectRaw('SUM(credit) - SUM(debit) as balance')->first();
        $saleValue = $saleValue->balance;

        $suppliersHeads = ChartOfHead::where(['master_head' => 3, 'head_id' => 8, 'sub_head_id' => 27])->whereNotNull('party_id')->pluck('id');
        $supplierOpeningBalance = Transaction::where('voucher_type', 'OPENING-BALANCE')->whereIn('chart_of_head_id', $suppliersHeads)->selectRaw('SUM(credit) - SUM(debit) as balance')->first();
        $supplierOpeningBalance = $supplierOpeningBalance->balance;

        $purchaseValue = Transaction::where('voucher_type', 'PURCHASE')->where('chart_of_head_id', 18)->selectRaw('SUM(debit) - SUM(credit) as balance')->first();
        $purchaseValue = $purchaseValue->balance;
        $supplierPayment = Transaction::whereIn('voucher_type', ['PURCHASE', 'PAYMENT'])->whereIn('chart_of_head_id', $cashBank)->sum('credit');

        $customersHeads = ChartOfHead::where(['master_head' => 1, 'head_id' => 1, 'sub_head_id' => 21])->whereNotNull('party_id')->pluck('id');
        $customerOpBalance = Transaction::where('voucher_type', 'OPENING-BALANCE')->whereIn('chart_of_head_id', $customersHeads)->selectRaw('SUM(debit) - SUM(credit) as balance')->first();
        $customerOpBalance = $customerOpBalance->balance;

        $expenseValue = Transaction::whereIn('voucher_type', ['EXPENSE'])->whereIn('chart_of_head_id', $cashBank)->sum('credit');

        $totalliability = 0;
        $totalAsset = 0;
        $table = '';
        $table .= '<table style="margin-bottom:10px !important;">
                            <tr>
                                <td class="text-center">
                                    <h1 class="name m-0 head_title" style="text-transform: uppercase"><b>' . (config('settings.title') ? config('settings.title') : env('APP_NAME')) . '</b></h1>
                                    <h3 class="name m-0 head_address"><b>' . (config('settings.address') ? config('settings.address') : env('APP_NAME')) . '</b></h3>
                                    <h3 class="name m-0 head_contact_no"><b>' . (config('settings.contact_no') ? "Contact No : " . config('settings.contact_no') : env('APP_NAME')) . '</b></h3>
                                    <h3 class="name m-0 head_email"><b>' . (config('settings.email') ? "Email : " . config('settings.email') : env('APP_NAME')) . '</b></h3>
                                    <p style="font-weight: normal;font-weight:bold;margin: 10px auto 5px auto;font-weight: bold;background: gray;width: 250px;color: white;text-align: center;padding:5px 0;}">' . __('file.Balance Sheet') . '</p>
                                </td>
                            </tr>
                        </table>';
        $table .= '<table>';
        $table .= '<tr>
                          <td width="49%"><button type="button" class = "btn btn-primary btn-block"><b>' . __('file.Assets') . '</b></button></td>
                          <td width="2%"></td>
                          <td width="49%"><button type="button" class = "btn btn-primary btn-block"><b>' . __('file.Equity & Liabilities') . '</b></button></td>
                        </tr>';
        $table .= '<tr><td width="49%"><table>';
        $table .= '<tr>
                          <td width="100%" colspan="2"><button type="button" class="btn btn-warning btn-block"><b>' . __('file.Non-Current Assets') . '</b></button></td>
                        </tr>';
        foreach ($cohId as $value) {
            if ($value->master_head == 2) {
                $headValue = 0;
                if ($value->name == 'Transportation') {
                    $headValue = Truck::where('status', 1)->sum('asset_price');
                }
                if ($value->name == 'Mill Building') {
                    $headValue = Mill::where('status', 1)->sum('asset_price');
                }
                $table .= '<tr>
                              <td class="text-right" width="80%"><b>' . $value->name . '</b></td>
                              <td class="text-right" width="20%"><b>' . $headValue . '</b></td>
                           </tr>';
                $totalAsset += $headValue;
            }
        }
        $table .= '<tr>
                          <td width="100%" colspan="2"><button type="button" class="btn btn-warning btn-block"><b>' . __('file.Current Assets') . '</b></button></td>
                        </tr>';
        foreach ($cohId as $value) {
            if ($value->master_head == 1) {
                $asstHeadValue = 0;
                if ($value->name == 'Cash & Bank Balance') {
                    $asstHeadValue = $cashAndBankBalance->balance + $customerOpBalance;
                }
                if ($value->name == 'Account Receivable') {
                    $asstHeadValue = $saleValue - $collection + $customerOpBalance;
                }
                $table .= '<tr>
                                   <td class="text-right" width="80%"><b>' . $value->name . '</b></td>
                                   <td class="text-right" width="20%"><b>' . $asstHeadValue . '</b></td>
                                </tr>';
                $totalAsset += $asstHeadValue;
            }
        }
        $table .= '<tr>
                          <td width="80%"><button type="button" class="btn btn-success btn-block"><b>' . __('file.Total Assets') . '</b></button></td>
                          <td width="20%"><button type="button" class="btn btn-success btn-block"><b>' . $totalAsset . '</b></button></td>
                        </tr>';
        $table .= '</table></td><td width="2%"></td><td width="49%"><table>';
        $table .= '<tr>
                          <td width="80%" colspan="2"><button type="button" class="btn btn-warning btn-block"><b>' . __('file.Equity') . '</b></button></td>
                        </tr>';
        foreach ($cohId as $value) {
            if ($value->master_head == 8) {
                $table .= '<tr>
                                   <td class="text-right" width="80%"><b>' . $value->name . '</b></td>
                                   <td class="text-right" width="20%"><b>0</b></td>
                                </tr>';
            }
        }
        $table .= '<tr>
                          <td width="100%" colspan="2"><button type="button" class="btn btn-warning btn-block"><b>' . __('file.Non-Current Liabilities') . '</b></button></td>
                        </tr>';
        foreach ($cohId as $value) {
            if ($value->master_head == 4) {

                $table .= '<tr>
                                   <td class="text-right" width="80%"><b>' . $value->name . '</b></td>
                                    <td class="text-right" width="20%"><b>0</b></td>
                                </tr>';
            }
        }
        $table .= '<tr>
                          <td width="100%" colspan="2"><button type="button" class="btn btn-warning btn-block"><b>' . __('file.Current Liabilities') . '</b></button></td>
                        </tr>';
        foreach ($cohId as $value) {
            $liabHeadValue = 0;
            if ($value->master_head == 3) {
                if ($value->name == 'Payable') {
                    $liabHeadValue = $purchaseValue - $supplierPayment + $supplierOpeningBalance;
                }

                if ($value->name == 'Accured Expense') {
                    $liabHeadValue = $expenseValue;
                }
                $table .= '<tr>
                                   <td class="text-right" width="80%"><b>' . $value->name . '</b></td>
                                   <td class="text-right" width="20%"><b>' . $liabHeadValue . '</b></td>
                                </tr>';
                $totalliability += $liabHeadValue;
            }
        }
//        $table .= '<tr>
//                          <td width="80%" colspan="2"><button type="button" class="btn btn-success btn-block"><b>' . __('file.Total Liabilities') . '</b></button></td>
//                         <td width="20%"><button type="button" class="btn btn-success btn-block"><b>0</b></button></td>
//                        </tr>';
        $table .= '<tr>
                          <td width="80%"><button type="button" class="btn btn-success btn-block"><b>' . __('file.Total Equity & Liabilities') . '</b></button></td>
                          <td width="20%"><button type="button" class="btn btn-success btn-block"><b>' . $totalliability . '</b></button></td>
                        </tr>';
        $table .= '</table></td></tr></table>';
        return $table;
    }
}
