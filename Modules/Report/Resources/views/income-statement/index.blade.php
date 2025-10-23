@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css"/>
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label">
                            <i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary btn-sm mr-3" id="print-labor-bill">
                            <i class="fas fa-print"></i> {{__('file.Print')}}
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-warning btn-sm font-weight-bolder">
                            <i class="fas fa-arrow-left"></i> {{__('file.Back')}}
                        </a>
                    </div>
                </div>
            </div>
            <form action="{{route('income.statement.report')}}" method="post">
                @csrf
                <div class="card card-custom gutter-b">
                    <div class="card-header flex-wrap py-5">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="date" class="form-control" name="date">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit"
                                            class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right"
                                            data-toggle="tooltip" data-theme="dark" title="{{__('file.Search')}}"><i
                                            class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </form>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div id="invoice" class="col-md-12 labor-bill">
                                <style>
                                    body,
                                    html {
                                        background: #fff !important;
                                        -webkit-print-color-adjust: exact !important;
                                    }

                                    .invoice {
                                        /* position: relative; */
                                        background: #fff !important;
                                        /* min-height: 680px; */
                                    }

                                    #print-labor-bill:last-child {
                                        page-break-after: auto !important;
                                    }

                                    .invoice header {
                                        padding: 10px 0;
                                        margin-bottom: 20px;
                                        border-bottom: 1px solid #036;
                                    }

                                    .invoice .company-details {
                                        text-align: right
                                    }

                                    .invoice .company-details .name {
                                        margin-top: 0;
                                        margin-bottom: 0;
                                    }

                                    .invoice .contacts {
                                        margin-bottom: 20px;
                                    }

                                    .invoice .invoice-to {
                                        text-align: left;
                                    }

                                    .invoice .invoice-to .to {
                                        margin-top: 0;
                                        margin-bottom: 0;
                                    }

                                    .invoice .invoice-details {
                                        text-align: right;
                                    }

                                    .invoice .invoice-details .invoice-id {
                                        margin-top: 0;
                                        color: #036;
                                    }

                                    .invoice main {
                                        padding-bottom: 50px
                                    }

                                    .invoice main .thanks {
                                        margin-top: -100px;
                                        font-size: 2em;
                                        margin-bottom: 50px;
                                    }

                                    .invoice main .notices {
                                        padding-left: 6px;
                                        border-left: 6px solid #036;
                                    }

                                    .invoice table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        border-spacing: 0;
                                        margin-bottom: 20px;
                                    }

                                    .invoice table th {
                                        background: #036;
                                        color: #fff;
                                        padding: 15px;
                                        border-bottom: 1px solid #fff
                                    }

                                    .invoice table td {
                                        padding: 10px;
                                        border-bottom: 1px solid #fff
                                    }

                                    .invoice table th {
                                        white-space: nowrap;
                                    }

                                    .invoice table td h3 {
                                        margin: 0;
                                        color: #036;
                                    }

                                    .invoice table .qty {
                                        text-align: center;
                                    }

                                    .invoice table .price,
                                    .invoice table .discount,
                                    .invoice table .tax,
                                    .invoice table .total {
                                        text-align: right;
                                    }

                                    .invoice table .no {
                                        color: #fff;
                                        background: #036
                                    }

                                    .invoice table .total {
                                        background: #036;
                                        color: #fff
                                    }

                                    .invoice table tbody tr:last-child td {
                                        border: none
                                    }

                                    .invoice table tfoot td {
                                        background: 0 0;
                                        border-bottom: none;
                                        white-space: nowrap;
                                        text-align: right;
                                        padding: 10px 20px;
                                        border-top: 1px solid #aaa;
                                        font-weight: bold;
                                    }

                                    .invoice table tfoot tr:first-child td {
                                        border-top: none
                                    }

                                    /* .invoice table tfoot tr:last-child td {
                                                            color: #036;
                                                            border-top: 1px solid #036
                                                        } */
                                    .invoice table tfoot tr td:first-child {
                                        border: none
                                    }

                                    .invoice footer {
                                        width: 100%;
                                        text-align: center;
                                        color: #777;
                                        border-top: 1px solid #aaa;
                                        padding: 8px 0
                                    }

                                    .invoice a {
                                        content: none !important;
                                        text-decoration: none !important;
                                        color: #036 !important;
                                    }

                                    .page-header,
                                    .page-header-space {
                                        height: 100px;
                                    }

                                    .page-footer,
                                    .page-footer-space {
                                        height: 20px;
                                    }

                                    .page-footer {
                                        position: fixed;
                                        bottom: 0;
                                        width: 100%;
                                        text-align: center;
                                        color: #777;
                                        border-top: 1px solid #aaa;
                                        padding: 8px 0
                                    }

                                    .page-header {
                                        position: fixed;
                                        top: 0mm;
                                        width: 100%;
                                        border-bottom: 1px solid black;
                                    }

                                    .page {
                                        page-break-after: always;
                                    }

                                    .dashed-border {
                                        width: 180px;
                                        height: 2px;
                                        margin: 0 auto;
                                        padding: 0;
                                        border-top: 1px dashed #454d55 !important;
                                    }

                                    .double-border {
                                        border-top: double black !important;
                                    }

                                    @media screen {
                                        .no_screen {
                                            display: none;
                                        }

                                        .no_print {
                                            display: block;
                                        }

                                        thead {
                                            display: table-header-group;
                                        }

                                        tfoot {
                                            display: table-footer-group;
                                        }

                                        button {
                                            display: none;
                                        }

                                        body {
                                            margin: 0;
                                        }
                                    }

                                    @media print {

                                        body,
                                        html {
                                            -webkit-print-color-adjust: exact !important;
                                            font-family: sans-serif;
                                            margin-bottom: 100px !important;
                                        }

                                        .m-0 {
                                            margin: 0 !important;
                                        }

                                        h1,
                                        h2,
                                        h3,
                                        h4,
                                        h5,
                                        h6 {
                                            margin: 0 !important;
                                        }

                                        .no_screen {
                                            display: block !important;
                                        }

                                        .no_print {
                                            display: none;
                                        }

                                        a {
                                            content: none !important;
                                            text-decoration: none !important;
                                            color: #036 !important;
                                        }

                                        .text-center {
                                            text-align: center !important;
                                        }

                                        .text-left {
                                            text-align: left !important;
                                        }

                                        .text-right {
                                            text-align: right !important;
                                        }

                                        .float-left {
                                            float: left !important;
                                        }

                                        .float-right {
                                            float: right !important;
                                        }

                                        .text-bold {
                                            font-weight: bold !important;
                                        }

                                        .invoice {
                                            overflow: hidden !important;
                                            background: #fff !important;
                                            margin-bottom: 100px !important;
                                        }

                                        .invoice footer {
                                            position: absolute;
                                            bottom: 0;
                                            left: 0;
                                        }

                                        .hidden-print {
                                            display: none !important;
                                        }

                                        .dashed-border {
                                            width: 180px;
                                            height: 2px;
                                            margin: 0 auto;
                                            padding: 0;
                                            border-top: 1px dashed #454d55 !important;
                                        }
                                    }

                                    @page {
                                        /* size: auto; */
                                        margin: 5mm 5mm;
                                    }
                                </style>
                                <div class="invoice overflow-auto">
                                    <div class="col-md-12 py-5">
                                        <table>
                                            <tr>
                                                <td class="text-left" style="width: 20%;">
                                                </td>
                                                <td class="text-center" style="width: 60%;">
                                                    <h2 class="name m-0" style="text-transform: uppercase;">
                                                        <b>{{ config('settings.title') ? config('settings.title') : env('APP_NAME') }}</b>
                                                    </h2>
                                                    @if(config('settings.address'))
                                                        <h3 style="font-weight: normal;margin:0;">{{ config('settings.address') }}</h3>
                                                    @endif
                                                    <div
                                                        style="width: 250px;background:#036;color:white;font-weight:bolder;margin:5px auto 0 auto;padding: 5px 0;border-radius: 15px;text-align:center;">{{__('file.Income Statement Report')}}</div>
                                                </td>
                                                <td class="text-right" style="width: 20%;">
                                                    @if(config('settings.contact_no'))
                                                        <div>
                                                            <span>{{__('file.Pro')}}:</span><span>{{ config('settings.contact_no') }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <span>{{__('file.Office')}}:</span><span>{{ '01716447882' }}</span>
                                                    </div>
                                                    <div>
                                                        <span>{{__('file.Director')}}:</span><span>{{ '01734657951' }}</span>
                                                    </div>
                                                    @if(config('settings.email'))
                                                        <div>
                                                            <span>{{__('file.Email')}}:</span><span>{{ config('settings.email') }}</span>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="5" class="text-center">{{__('file.Income')}}</th>
                                            </tr>
                                            @foreach($sales as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Memo No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->debit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Sale')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{number_format($totalSale)}} TK
                                                </td>
                                            </tr>
                                            @foreach($collections as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Memo No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->debit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Collection')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{isset($partyCollection) ? number_format($partyCollection):0}}
                                                    TK
                                                </td>
                                            </tr>
                                            @foreach($tenant as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Memo No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->debit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Tenant Collection')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{number_format($totalTenant)}} TK
                                                </td>
                                            </tr>
                                            @foreach($incomePersonalLoan as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Voucher No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->debit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @foreach($incomeOfficialLoan as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Voucher No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->debit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Loan')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{number_format($totalIncomePersonalLoan + $totalExpenseOfficialLoan)}}
                                                    TK
                                                </td>
                                            </tr>
                                            @foreach($machineService as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Voucher No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->debit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Machine Service')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{number_format($totalMachineService)}}
                                                    TK
                                                </td>
                                            </tr>
                                            @foreach($transportService as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Voucher No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->debit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Transport Service')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{number_format($totalTransportService)}}
                                                    TK
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-center">{{__('file.Expense')}}</th>
                                            </tr>
                                            @foreach($purchases as $value)
                                                @if($value->credit != 0)
                                                    <tr>
                                                        <td colspan="3" class="text-left">
                                                            <div>
                                                                <span>{{__('file.Memo No')}} : </span><span>{{$value->voucher_no}}</span>
                                                            </div>
                                                            <div>
                                                                <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                            </div>
                                                        </td>
                                                        <td colspan="2" class="text-left">
                                                            <div>
                                                                {{number_format($value->credit)}} TK
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Purchase')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{number_format($totalPurchase)}}
                                                    TK
                                                </td>
                                            </tr>
                                            @foreach($payments as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Voucher No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->credit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Payment')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{ isset($totalPayment) ? number_format($totalPayment) :0 }}
                                                    TK
                                                </td>
                                            </tr>
                                            @foreach($expensePersonalLoan as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Voucher No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->credit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @foreach($expenseOfficialLoan as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Voucher No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->credit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Loan')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{number_format($totalExpensePersonalLoan + $totalExpenseOfficialLoan)}}
                                                    TK
                                                </td>
                                            </tr>
                                            @foreach($machinePurchase as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Voucher No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->credit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Machine Purchase')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{number_format($totalMachinePurchase)}}
                                                    TK
                                                </td>
                                            </tr>
                                            @foreach($laborBill as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Voucher No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->credit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Labor Bill')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{number_format($totalLaborBill)}}
                                                    TK
                                                </td>
                                            </tr>
                                            @foreach($expense as $value)
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        <div>
                                                            <span>{{__('file.Voucher No')}} : </span><span>{{$value->voucher_no}}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{__('file.Description')}} : </span><span>{{$value->description}}</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-left">
                                                        <div>
                                                            {{number_format($value->credit)}} TK
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-left">{{__('file.Other Expense')}}</td>
                                                <td colspan="1"
                                                    class="text-right double-border">{{number_format($totalExpense)}} TK
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="5" class="text-right">{{__('file.Cash In Hand')}}
                                                    : {{$cash}} tk
                                                </th>
                                            </tr>
                                            @foreach($banks as $bank)
                                                <tr>
                                                    <th colspan="5"
                                                        class="text-right">{{__('file.Bank')}} {{$bank->name}}
                                                        : {{$bank->calculation($date,$bank->id)}} tk
                                                    </th>
                                                </tr>
                                            @endforeach
                                            @foreach($mobileBanks as $bank)
                                                <tr>
                                                    <th colspan="5"
                                                        class="text-right">{{__('file.Bank')}} {{$bank->name}}
                                                        : {{$bank->calculation($date,$bank->id)}} tk
                                                    </th>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('js/jquery.printarea.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '#print-labor-bill', function () {
                var mode = 'iframe'; // popup
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $(".labor-bill").printArea(options);
            });

        });
    </script>
@endpush

