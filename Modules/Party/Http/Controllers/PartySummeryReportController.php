<?php

namespace Modules\Party\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Party\Entities\Party;

class PartySummeryReportController extends BaseController
{

    public function index()
    {
        if (permission('party-ledger-access')) {
            $title = __('file.Party Transaction Summery');
            $this->setPageData($title, $title, 'fas fa-file', [['name' => $title]]);
            return view('party::summery.index');
        } else {
            return $this->access_blocked();
        }
    }

    public function partySummary(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // Base query for the date range
        $summary = DB::table('chart_of_heads as coh')
            ->join('transactions as t', 'coh.id', '=', 't.chart_of_head_id')
            ->join('parties as p', 'p.id', '=', 'coh.party_id')
            ->whereBetween('t.date', [$start_date, $end_date])
            ->where('t.status', 1)
            ->select(
                'p.id as party_id',
                'p.name as party_name',
                'p.company_name as company_name',
                DB::raw('SUM(t.debit) as total_debit'),
                DB::raw('SUM(t.credit) as total_credit')
            )
            ->groupBy('p.id', 'p.name', 'p.company_name')
            ->orderBy('p.name', 'asc')
            ->get();

        // Calculate balance and build table
        $table = '<table class="table table-bordered" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr class="text-center bg-primary text-white">
                        <th>' . __('file.Party Name') . '</th>
                        <th>' . __('file.Company Name') . '</th>
                        <th>' . __('file.Total Debit') . '</th>
                        <th>' . __('file.Total Credit') . '</th>
                        <th>' . __('file.Balance') . '</th>
                    </tr>
                </thead>
                <tbody>';

        $grandDebit = 0;
        $grandCredit = 0;

        foreach ($summary as $row) {
            $balance = $row->total_debit - $row->total_credit;
            $grandDebit += $row->total_debit;
            $grandCredit += $row->total_credit;

            $table .= '<tr class="text-center">
                        <td>' . e($row->party_name) . '</td>
                        <td>' . e($row->company_name) . '</td>
                        <td>' . number_format($row->total_debit, 2) . '</td>
                        <td>' . number_format($row->total_credit, 2) . '</td>
                        <td>' . number_format($balance, 2) . '</td>
                    </tr>';
        }

        $table .= '<tr class="text-center bg-light">
                    <td colspan="2"><b>' . __('file.Grand Total') . '</b></td>
                    <td><b>' . number_format($grandDebit, 2) . '</b></td>
                    <td><b>' . number_format($grandCredit, 2) . '</b></td>
                    <td><b>' . number_format($grandDebit - $grandCredit, 2) . '</b></td>
                </tr>';

        $table .= '</tbody></table>';

        return $table;
    }

}
