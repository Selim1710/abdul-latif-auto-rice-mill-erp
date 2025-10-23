<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DueReportController extends BaseController {
    public function index(){
        if(permission('due-report-access')){
            $title   = __('file.Due Report');
            $this->setPageData($title,$title,'fas fa-file',[['name' => $title]]);
            return view('report::dueReport.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function dueReportData(Request $request){
        $table       = '';
        $amount      = 0 ;
        $dues     = DB::table('parties as p')
                    ->join('chart_of_heads as coh','coh.party_id','=','p.id')
                    ->join('transactions as t','coh.id','=','t.chart_of_head_id')
                    ->where(['t.status' => 1])
                    ->select(
                        'p.name as name','p.company_name as companyName','p.mobile as mobile','p.address as address',
                        DB::raw('SUM(t.debit) as totalDebit'),
                        DB::raw('SUM(t.credit) as totalCredit'),
                    )
                    ->groupBy('p.id')
                    ->get();
        $table      .= '<table style="margin-bottom:10px !important;">
                            <tr>
                                <td class="text-center">
                                    <h1 class="name m-0 head_title" style="text-transform: uppercase"><b>'.(config('settings.title') ? config('settings.title') : env('APP_NAME')).'</b></h1>
                                    <h3 class="name m-0 head_address"><b>'.(config('settings.address') ?  config('settings.address') : env('APP_NAME')).'</b></h3>
                                    <h3 class="name m-0 head_contact_no"><b>'.(config('settings.contact_no') ? "Contact No : ". config('settings.contact_no') : env('APP_NAME')).'</b></h3>
                                    <h3 class="name m-0 head_email"><b>'.(config('settings.email') ? "Email : ".config('settings.email') : env('APP_NAME')).'</b></h3>
                                    <p style="font-weight: normal;font-weight:bold;margin: 10px auto 5px auto;font-weight: bold;background: gray;width: 250px;color: white;text-align: center;padding:5px 0;}">'.__('file.Due Report').'</p>
                                </td>
                            </tr>
                        </table>';
        $table      .= '<table style="margin-bottom:10px !important;">
                            <thead>
                                <tr class="text-center">
                                    <th><b>'.__('file.Name').'</b></th>
                                    <th><b>'.__('file.Company').'</b></th>
                                    <th><b>'.__('file.Mobile').'</b></th>
                                    <th><b>'.__('file.Address').'</b></th>
                                    <th><b>'.__('file.Amount').'</b></th>
                                </tr>
                            </thead>';
        $table      .= '<tbody>';
        foreach ($dues as $due){
            if($due->totalDebit - $due->totalCredit != 0){
                $amount = $amount + ($due->totalDebit - $due->totalCredit);
                $table      .= '<tr class="text-center">
                                <td class="no"><b>'.$due->name.'</b></td>
                                <td class="no"><b>'.$due->companyName.'</b></td>
                                <td class="no"><b>'.$due->mobile.'</b></td>
                                <td class="no"><b>'.$due->address.'</b></td>
                                <td class="no"><b>'.number_format($due->totalDebit - $due->totalCredit,2).'</b></td>
                            </tr>';
            }
        }
        $table      .= '</tbody><tfoot>';
        $table      .= '<tr>
                           <td class="no text-center" colspan="4"><h3 class="text-danger">'.__('file.Balance').'</h3></td>
                           <td class="no text-center" colspan="2"><h3 class="text-danger">'.number_format($amount,2).'</h3></td>
                        </tr>
                        </tfoot>
                        </table>';


                        
        return $table;
    }
}
