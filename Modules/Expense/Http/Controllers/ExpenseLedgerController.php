<?php

namespace Modules\Expense\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Expense\Entities\ExpenseLedger;
use Modules\Expense\Http\Requests\ExpenseItemFormRequest;


class ExpenseLedgerController extends BaseController
{
    public function __construct(ExpenseLedger $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('expense-ledger-access')) {
            $setTitle    = __('file.Expense');
            $setSubTitle = __('file.Expense Ledger');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle], ['name' => $setSubTitle]]);
            return view('expense::expense-ledger.index');
        } else {
            return $this->access_blocked();
        }
    }
    public function ExpenseledgerData(Request $request)
    {
        $table       = '';
        $debit       = 0;
        $credit      = 0;
        $start_date  = $request->start_date;
        $end_date    = $request->end_date;
        $coh         = 'Expense';
        $request_id_all = [];

        $request_id_all  =  ChartOfHead::where('master_head', '=', 7)->pluck('id');

        $transactions = DB::table('transactions as t')
            ->join('chart_of_heads as coh', 't.chart_of_head_id', '=', 'coh.id')
            ->whereBetween('t.date', [$start_date, $end_date])
            ->where([['t.status', '=', 1]])
            ->whereIn('t.chart_of_head_id', $request_id_all)
            ->select(
                't.voucher_no as voucherNo',
                'coh.name as partyname',
                't.narration as narration',
                't.date as date',
                't.voucher_type as voucherType',
                DB::raw('SUM(t.debit) as debit'),
                DB::raw('SUM(t.credit) as credit')
            )
            ->groupBy('coh.name')->get();



        $previousBalance = DB::table('chart_of_heads as coh')
            ->join('transactions as t', 'coh.id', '=', 't.chart_of_head_id')
            ->whereDate('t.date', '<', $start_date)
            ->where([['t.status', '=', 1]])
            ->whereIn('t.chart_of_head_id', $request_id_all)
            ->select(
                DB::raw('SUM(t.debit) as totalDebit'),
                DB::raw('SUM(t.credit) as totalCredit')
            )
            ->get();

        $table      .= '<table style="margin-bottom:10px !important;">
                            <tr>
                                <td class="text-center">
                                    <h1 class="name m-0 head_title" style="text-transform: uppercase"><b>' . (config('settings.title') ? config('settings.title') : env('APP_NAME')) . '</b></h1>
                                    <h3 class="name m-0 head_address"><b>' . (config('settings.address') ?  config('settings.address') : env('APP_NAME')) . '</b></h3>
                                    <h3 class="name m-0 head_contact_no"><b>' . (config('settings.contact_no') ? "Contact No : " . config('settings.contact_no') : env('APP_NAME')) . '</b></h3>
                                    <h3 class="name m-0 head_email"><b>' . (config('settings.email') ? "Email : " . config('settings.email') : env('APP_NAME')) . '</b></h3>
                                    <p style="font-weight: normal;font-weight:bold;margin: 10px auto 5px auto;font-weight: bold;background: gray;width: 250px;color: white;text-align: center;padding:5px 0;}">' . $coh . ' ' . __('file.Ledger') . '</p>
                                    <p style="font-weight: normal;margin:0;font-weight:bold;">' . __('file.Date') . ': ' . date('d-m-Y', strtotime($start_date)) . ' ' . __('file.To') . ' ' . date('d-m-Y', strtotime($end_date)) . '</p>
                                </td>
                            </tr>
                        </table>';
        foreach ($previousBalance as $balance) {
            $table      .= '<table style="margin-bottom:10px !important;">
                            <tr>
                                <td class="text-center no text-primary" ><button class="btn btn-primary btn-block"><b>' . __('file.Previous Balance') . '</b></button></td>
                                <td class="text-center no"><h3><b>' . number_format($balance->totalDebit - $balance->totalCredit) . '</b></h3></td>
                            </tr>
                            </table>';
        }
        $table      .= '<table style="margin-bottom:10px !important;">
                            <thead>
                                <tr class="text-center">
                                    <th>' . __('file.SL') . '</th>
                                    <th>' . __('file.Name') . '</th>
                                    <th>' . __('file.Debit') . '</th>
                                    <th>' . __('file.Credit') . '</th>
                                </tr>
                            </thead>';
        $table      .= '<tbody>';
        foreach ($transactions as $key => $transaction) {
            $debit  += $transaction->debit;
            $credit += $transaction->credit;
            $table      .= '<tr class="text-center">
                                <td class="no">' . ++$key . '</td>
                                <td class="no">' . $transaction->partyname . '</td>
                                <td class="no">' . $transaction->debit . '</td>
                                <td class="no">' . $transaction->credit . '</td>
                            </tr>';
        }
        $table      .= '</tbody><tfoot>';
        $table      .= '<tr>
                           <td class="no text-right" colspan="2"><h3 class="text-success">' . __('file.Sub Total') . '</h3></td>
                           <td class="no text-center"><h3 class="text-success">' . number_format($debit, 2) . '</h3></td>
                           <td class="no text-center"><h3 class="text-success">' . number_format($credit, 2) . '</h3></td>
                        </tr>';
        $table      .= '<tr>
                           <td class="no text-right" colspan="2"><h3 class="text-danger">' . __('file.Balance') . '</h3></td>
                           <td class="no text-center" colspan="2"><h3 class="text-danger">' . number_format($debit - $credit + $previousBalance[0]->totalDebit - $previousBalance[0]->totalCredit, 2) . '</h3></td>
                        </tr>
                        </tfoot>
                        </table>';
        return $table;
    }
}
