@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary btn-sm mr-3" id="print-invoice"> <i class="fas fa-print"></i>{{__('file.Print')}}</button>
                        <a href="{{ route('loan') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a>
                    </div>
                </div>
            </div>
            <div class="card card-custom" style="padding-bottom: 100px !important;">
                <div class="card-body" style="padding-bottom: 100px !important;">
                    <div class="row" id="invoice">
                        <style>
                            body,html {
                                background: #fff !important;
                                -webkit-print-color-adjust: exact !important;
                            }
                            .invoice {
                                background: #fff !important;
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
                                background: white;
                                color: black;
                                padding: 15px;
                                border : 2px solid black;
                            }
                            .invoice table td {
                                padding: 15px;
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
                                color: black;
                                background: white;
                                border : 2px solid black !important;
                            }
                            .invoice table .total {
                                background: white;
                                color: black;
                                border : 2px solid black !important;
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
                            .dashed-border{
                                width:180px;height:2px;margin:0 auto;padding:0;border-top:1px dashed #454d55 !important;
                            }
                            .header-title{
                                background: #D6EEEE;
                                color: black;
                                font-weight:bold;
                                text-align: center;
                                height: 53px;
                                padding: 10px;
                                font-size: 20px;
                            }
                            .myTable {
                                border-collapse: collapse;
                                width: 50%;
                            }

                            .myTable th, td {
                                text-align: left;
                                padding: 8px;
                                font-weight:bold;
                            }

                            .myTable tr:nth-child(even) {
                                background-color: #D6EEEE;
                                font-weight:bold;
                            }
                            .myTableTwo {
                                border-collapse: collapse;
                                width: 48%;
                            }

                            .myTableTwo th, td {
                                text-align: left;
                                padding: 8px;
                                font-weight:bold;
                            }

                            .myTableTwo tr:nth-child(even) {
                                background-color: #D6EEEE;
                                font-weight:bold;
                            }
                            @media screen {
                                .no_screen {display: none;}
                                .no_print {display: block;}
                                thead {display: table-header-group;}
                                tfoot {display: table-footer-group;}
                                button {display: none;}
                                body {margin: 0;}
                            }
                            @media print {
                                body,
                                html {
                                    -webkit-print-color-adjust: exact !important;
                                    font-family: sans-serif;
                                    /*margin-bottom: 100px !important;*/
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
                                .header-title{
                                    background: #D6EEEE;
                                    color: black;
                                    font-weight:bold;
                                    text-align: center;
                                    height: 53px;
                                    padding: 10px;
                                    font-size: 20px;
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
                                .dashed-border{
                                    width:180px;height:2px;margin:0 auto;padding:0;border-top:1px dashed #454d55 !important;
                                }
                            }
                            @page {
                                margin: 5mm 5mm;
                            }
                        </style>
                        <div class="col-md-12">
                            <table class="float-right">
                                <tr>
                                    <td width="20%"></td>
                                    <td width="80%" class="text-right">
                                        <h2 class="name m-0" style="text-transform: uppercase;"><b>{{ config('settings.title') ? config('settings.title') : env('APP_NAME') }}</b></h2>
                                        @if(config('settings.contact_no'))<p style="font-weight: normal;margin:0;"><b>{{__('file.Contact No')}}.: </b>{{ config('settings.contact_no') }}, @if(config('settings.email'))<b>{{__('file.Email')}}: </b>{{ config('settings.email') }}@endif</p>@endif
                                        <h5 style="font-weight: normal;margin:0;"><b>Corporate Office :</b> Ga 81/C Gulshan Badda Link Road,Opposite Side Off Primer Bank Ltd,Dhaka Bangladesh</h5>
                                        <h5 style="font-weight: normal;margin:0;"><b>Head Office And Factory : </b>I.K Road ,BoroyChara,Ishwardi,Pabna-6620,Bangladesh</h5>
                                        <h5 style="font-weight: normal;margin:0;"><b>Global Office : </b>318 Arenco Office Building No-#04,Dubai Investment Park 01 (DIP 01),UAE</h5>
                                        <p style="font-weight: normal;margin:0;"><b>{{__('file.Date')}}: </b>{{ date('d-M-Y') }}</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="width: 100%;height:3px;border-top:1px solid #036;border-bottom:1px solid #036;margin:15px 0"></div><br/>
                        <div class="col-md-12"><div class="header-title">Loan Detail's</div></div>
                        <div class="col-md-12"><br/>
                            <table class="myTableTwo float-left">
                                <tr>
                                    <td>{{__('file.Bank')}}</td>
                                    <td>:</td>
                                    <td>{{$data->bank->bank_name}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.Branch')}}</td>
                                    <td>:</td>
                                    <td>{{$data->bank->branch}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.Account Number')}}</td>
                                    <td>:</td>
                                    <td>{{$data->bank->account_number}}</td>
                                </tr>
                            </table>
                            <table class="myTableTwo float-right">
                                <tr>
                                    <td>{{__('file.Category')}}</td>
                                    <td>:</td>
                                    <td>{{$data->loanCategories->name}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.Apply Date')}}</td>
                                    <td>:</td>
                                    <td>{{$data->apply_date}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.Reference No')}}</td>
                                    <td>:</td>
                                    <td>{{$data->reference_no}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <br/><br/>
                            <table class="myTable float-right">
                                <tr>
                                    <td>{{__('file.Loan Amount')}}</td>
                                    <td>:</td>
                                    <td>{{number_format($data->loan_amount)}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.Loan Percentage(%)')}}</td>
                                    <td>:</td>
                                    <td>{{$data->loan_percentage_percent}}%</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.Loan Percentage(tk)')}}</td>
                                    <td>:</td>
                                    <td>{{number_format($data->loan_percentage_tk)}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.MISC Charge')}}</td>
                                    <td>:</td>
                                    <td>{{number_format($data->misc_charge)}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.Processing Charge')}}</td>
                                    <td>:</td>
                                    <td>{{number_format($data->processing_charge)}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.Total Amount')}}</td>
                                    <td>:</td>
                                    <td>{{number_format($data->total_amount)}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.Total Installment Month')}}</td>
                                    <td>:</td>
                                    <td>{{number_format($data->total_installment_month)}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('file.Monthly Installment')}}</td>
                                    <td>:</td>
                                    <td>{{number_format($data->monthly_installment)}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-12"><br/>
                            <div><span class="text-danger text-bold" style="font-size:22px">Note : </span><span class="text-bold" style="font-size:18px">{{$data->note}}</span></div>
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
            //QR Code Print
            $(document).on('click','#print-invoice',function(){
                var mode = 'iframe'; // popup
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $("#invoice").printArea(options);
            });
        });
    </script>
@endpush

