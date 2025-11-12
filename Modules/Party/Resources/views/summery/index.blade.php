@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    <div class="card-toolbar"><button type="button" class="btn btn-primary btn-sm mr-3" id="print-invoice"> <i class="fas fa-print"></i>{{__('file.Print')}}</button></div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="start_date">{{__('file.Start Date')}}</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"/>
                            </div>
                            <div class="col-md-2">
                                <label for="end_date">{{__('file.End Date')}}</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"/>
                            </div>
                            <div class="col-md-2">
                                <div style="margin-top:28px;">
                                    <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon mr-2 float-left custom-btn" type="button" data-toggle="tooltip" data-theme="dark" title="Reset"><i class="fas fa-undo-alt"></i></button>
                                    <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon float-left custom-btn" type="button" data-toggle="tooltip" data-theme="dark" onclick="report_data()" title="Search"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="col-md-12 px-0" style="position: relative;">
                            <div id="invoice" style="width: 100%;">
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
                                    .dashed-border{
                                        width:180px;height:2px;margin:0 auto;padding:0;border-top:1px dashed #454d55 !important;
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
                                            /* font-size: 11px!important; */
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
                                <div class="invoice overflow-auto" id="report_data"></div>
                                <table style="width: 100%;">
                                    <tr>
                                        <td class="text-center">
                                            <div class="font-size-10" style="width:250px;float:left;">
                                                <p style="margin:0;padding:0;"></p>
                                                <p class="dashed-border"></p>
                                                <p style="margin:0;padding:0;">{{__('file.Signature')}}</p>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="font-size-10" style="width:250px;float:right;">
                                                <p style="margin:35px 0 0 0;padding:0;"></p>
                                                <p class="dashed-border"></p>
                                                <p style="margin:0;padding:0;">{{__('file.Signature')}}</p>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="col-md-12 d-none" id="table-loader" style="position: absolute;top:80px;left:45%;">
                                    <div><i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i></div>
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
        function _(x){
            return document.getElementById(x);
        }
        $(document).ready(function () {
            $(document).on('click','#print-invoice',function(){
                var mode    = 'iframe'; // popup
                var close   = mode == "popup";
                var options = {
                    mode    : mode,
                    popClose: close
                };
                $("#invoice").printArea(options);
            });
            $('#btn-reset').click(function () {
                $('input[name="start_date"]').val("");
                $('input[name="end_date"]').val("");
                $('#party_id').val('');
                $('#party_id.selectpicker').selectpicker('refresh');
                $('#report_data').empty();
            });
        });
        function report_data() {
            let start_date  = _('start_date').value;
            let end_date    = _('end_date').value;
            if( _('start_date').value == '' || _('end_date').value == ''){
                notification('error','Please choose date!');
                return;
            }
            $.ajax({
                url        :"{{ route('party.summery.data') }}",
                type       :"POST",
                data       :{start_date:start_date,end_date:end_date,_token:_token},
                beforeSend : function(){
                    $('#table-loader').removeClass('d-none');
                },
                complete   : function(){
                    $('#table-loader').addClass('d-none');
                },
                success    : function(data){
                    $('#report_data').empty().html(data);
                },
                error      : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
