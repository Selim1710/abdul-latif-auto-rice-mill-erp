<?php

namespace Modules\Party\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Party\Entities\Party;

class PartyLedgerController extends BaseController {
    public function index(){
        if(permission('party-ledger-access')){
            $title   = __('file.Party Ledger');
            $this->setPageData($title,$title,'fas fa-file',[['name' => $title]]);
            $parties = Party::all();
            return view('party::ledger.index',compact('parties'));
        }else{
            return $this->access_blocked();
        }
    }
    public function ledgerData(Request $request){
        $table           = '';
        $debit           = 0;
        $credit          = 0;
        $start_date      = $request->start_date;
        $end_date        = $request->end_date;
        $party           = Party::findOrFail($request->party_id);
        $transactions    = DB::table('chart_of_heads as coh')
                           ->join('transactions as t','coh.id','=','t.chart_of_head_id')
                           ->join('parties as p','p.id','=','coh.party_id')
                           ->whereBetween('t.date',[$start_date,$end_date])
                           ->where(['coh.party_id' => $request->party_id,'t.status' => 1])
                           ->select(
                               't.voucher_no as voucherNo','t.narration as narration','t.date as date',
                               't.voucher_type as voucherType','t.debit as debit','t.credit as credit'
                           )
                           ->get();
        $previousBalance = DB::table('chart_of_heads as coh')
                           ->join('transactions as t','coh.id','=','t.chart_of_head_id')
                           ->whereDate('t.date','<',$start_date)
                           ->where(['coh.party_id' => $request->party_id,'t.status' => 1])
                           ->select(
                               DB::raw('SUM(t.debit) as totalDebit'),
                               DB::raw('SUM(t.credit) as totalCredit'),
                           )
                           ->get();
        $table      .= '<table style="margin-bottom:10px !important;">
                            <tr>
                                <td class="text-center">
                                    <h1 class="name m-0 head_title" style="text-transform: uppercase"><b>'.(config('settings.title') ? config('settings.title') : env('APP_NAME')).'</b></h1>
                                    <h3 class="name m-0 head_address"><b>'.(config('settings.address') ?  config('settings.address') : env('APP_NAME')).'</b></h3>
                                    <h3 class="name m-0 head_contact_no"><b>'.(config('settings.contact_no') ? "Contact No : ". config('settings.contact_no') : env('APP_NAME')).'</b></h3>
                                    <h3 class="name m-0 head_email"><b>'.(config('settings.email') ? "Email : ".config('settings.email') : env('APP_NAME')).'</b></h3>
                                    <p style="font-weight: normal;font-weight:bold;margin: 10px auto 5px auto;font-weight: bold;background: gray;width: 250px;color: white;text-align: center;padding:5px 0;}">'.__('file.Party Ledger').'</p>
                                    <p style="font-weight: normal;margin:0;font-weight:bold;">'.__('file.Date').': '.date('d-m-Y',strtotime($start_date)).' '.__('file.To').' '.date('d-m-Y',strtotime($end_date)).'</p>
                                </td>
                            </tr>
                        </table>';
        $table      .= '<table style="margin-bottom:10px !important;">
                            <tr>
                                <td class="text-center no text-primary"><b>'.__('file.Party').'</b></td>
                                <td class="text-center no">'.$party->name.'</td>
                                <td class="text-center no text-primary"><b>'.__('file.Company Name').'</b></td>
                                <td class="text-center no">'.$party->company_name.'</td>
                                <td class="text-center no text-primary"><b>'.__('file.Mobile').'</b></td>
                                <td class="text-center no">'.$party->mobile.'</td>
                                <td class="text-center no text-primary"><b>'.__('file.Address').'</b></td>
                                <td class="text-center no">'.$party->address.'</td>
                            </tr>
                        </table>';
        foreach ($previousBalance as $balance){
            $table      .= '<table style="margin-bottom:10px !important;">
                            <tr>
                                <td class="text-center no text-primary"><button class="btn btn-primary btn-block"><b>'.__('file.Previous Balance').'</b></button></td>
                                <td class="text-center no"><h3><b>'.number_format($balance->totalDebit - $balance->totalCredit).'</b></h3></td>
                            </tr>
                            </table>';
            }
        $table      .= '<table style="margin-bottom:10px !important;">
                            <thead>
                                <tr class="text-center">
                                    <th>'.__('file.Date').'</th>
                                    <th>'.__('file.Voucher No').'</th>
                                    <th>'.__('file.Narration').'</th>
                                    <th>'.__('file.Voucher Type').'</th>
                                    <th>'.__('file.Debit').'</th>
                                    <th>'.__('file.Credit').'</th>
                                </tr>
                            </thead>';
        $table      .= '<tbody>';
        foreach ($transactions as $transaction){
            $debit      += $transaction->debit;
            $credit     += $transaction->credit;
            $table      .= '<tr class="text-center">
                                <td class="no">'.date('d-M-Y',strtotime($transaction->date)).'</td>
                                <td class="no">'.$transaction->voucherNo.'</td>
                                <td class="no">'.$transaction->narration.'</td>
                                <td class="no">'.$transaction->voucherType.'</td>
                                <td class="no">'.number_format($transaction->debit).'</td>
                                <td class="no">'.number_format($transaction->credit).'</td>
                            </tr>';
            }
            $table      .= '</tbody><tfoot>';
            $table      .= '<tr>
                                <td class="no text-right" colspan="4"><h3 class="text-primary"><b>'.__('file.Sub Total').'</b></h3></td>
                                <td class="no text-center"><h3 class="text-primary"><b>'.number_format($debit).'</b></h3></td>
                                <td class="no text-center"><h3 class="text-primary"><b>'.number_format($credit).'</b></h3></td>
                            </tr>';
            $table      .= '<tr>
                                <td class="no text-right" colspan="4"><h3 class="text-danger"><b>'.__('file.Balance').'</b></h3></td>
                                <td class="no text-center" colspan="2"><h3 class="text-danger"><b>'.number_format($debit - $credit + $previousBalance[0]->totalDebit - $previousBalance[0]->totalCredit).'</b></h3></td>
                            </tr></tfoot></table>';
        return $table;
    }
}
