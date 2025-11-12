@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary btn-sm mr-3" id="print-invoice"> <i
                                class="fas fa-print"></i>{{ __('file.Print') }}</button>
                        <a href="{{ route('production') }}" class="btn btn-warning btn-sm font-weight-bolder"><i
                                class="fas fa-arrow-left"></i> {{ __('file.Back') }}</a>
                    </div>
                </div>
            </div>
            <div class="card card-custom" style="padding-bottom: 100px !important;">
                <div class="card-body" style="padding-bottom: 100px !important;">
                    <div class="col-md-12 col-lg-12" style="width: 100%;">
                        <div id="invoice">
                            <style>
                                body,
                                html {
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
                                    border: 2px solid black;
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
                                    border: 2px solid black !important;
                                }

                                .invoice table .total {
                                    background: white;
                                    color: black;
                                    border: 2px solid black !important;
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

                                    .dashed-border {
                                        width: 180px;
                                        height: 2px;
                                        margin: 0 auto;
                                        padding: 0;
                                        border-top: 1px dashed #454d55 !important;
                                    }
                                }

                                @page {
                                    margin: 5mm 5mm;
                                }
                            </style>
                            <div class="invoice overflow-auto">
                                <div>
                                    <table>
                                        <tr>
                                            <td width="20%"><img
                                                    src="{{ asset('storage/' . LOGO_PATH . config('settings.logo')) }}"
                                                    style="max-width: 200px;max-height:100px;" alt="Logo" /></td>
                                            <td width="80%" class="text-right">
                                                <h2 class="name m-0" style="text-transform: uppercase;">
                                                    <b>{{ config('settings.title') ? config('settings.title') : env('APP_NAME') }}</b>
                                                </h2>
                                                @if (config('settings.contact_no'))
                                                    <p style="font-weight: normal;margin:0;">
                                                        <b>{{ __('file.Contact No') }}.:
                                                        </b>{{ config('settings.contact_no') }}, @if (config('settings.email'))
                                                            <b>{{ __('file.Email') }}: </b>{{ config('settings.email') }}
                                                        @endif
                                                    </p>
                                                @endif
                                                <p style="font-weight: normal;margin:0;"><b>{{ __('file.Date') }}:
                                                    </b>{{ date('d-M-Y') }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                    <div
                                        style="width: 100%;height:3px;border-top:1px solid #036;border-bottom:1px solid #036;">
                                    </div><br />
                                    <table>
                                        <tr>
                                            <td width="50%">
                                                <div class="invoice-to">
                                                    <div class="text-grey-light"><b>{{ __('file.Production') }}</b></div>
                                                    <div class="to">{{ $data->mill->name }}</div>
                                                </div>
                                            </td>
                                            <td width="50%" class="text-right">
                                                <h4 class="name m-0">#{{ $data->invoice_no }}</h4>
                                                <div class="m-0 date"><b>{{ __('file.Date') }}:
                                                    </b>{{ date('d-M-Y', strtotime($data->date)) }}</div>
                                            </td>
                                        </tr>
                                    </table>

                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Total Raw Scale') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->total_raw_scale }}</h5>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Total Raw Amount') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->total_raw_amount }}</h5>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Bag Qty') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->total_use_product_qty }}</h5>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Bag Amount') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->total_use_product_amount }}</h5>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Total Milling Cost') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->total_milling }}</h5>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Total Expense') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->total_expense }}</h5>
                                                </td>
                                            </tr>
                                            {{-- <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Total Sale Scale') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->total_sale_scale }}</h5>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Total Sale Amount') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->total_sale_amount }}</h5>
                                                </td>
                                            </tr> --}}
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Finish Stock Scale') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->finish_stock_scale }}</h5>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Finish Stock Amount') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->finish_stock_amount }}</h5>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.By Product Stock Scale') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->by_product_stock_scale }}</h5>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.By Product Stock Amount') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->by_product_stock_amount }}</h5>
                                                </td>
                                            </tr>
                                            @php
                                                $transport_cost = 0;
                                                $data->productionRawProductList->each(function ($item) use (
                                                    &$transport_cost,
                                                ) {
                                                    $transport_cost +=
                                                        $item->scale * $item->purchase->per_scale_transportation_cost ??
                                                        0;
                                                });
                                            @endphp
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Total Transportation Cost') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $transport_cost }}</h5>
                                                </td>
                                            </tr>
                                            @php
                                                $porta =
                                                    $data->total_raw_amount +
                                                    $transport_cost +
                                                    $data->total_milling +
                                                    $data->total_use_product_amount -
                                                    $data->by_product_stock_amount;

                                            @endphp
                                            <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Porta') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ number_format($porta / $data->finish_stock_scale, 3) }}</h5>
                                                </td>
                                            </tr>

                                            {{-- <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Total Scale') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->total_stock_scale + $data->total_sale_scale }}</h5>
                                                </td>
                                            </tr> --}}
                                            {{-- <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Total Waste') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->total_raw_scale - $data->total_stock_scale + $data->total_sale_scale }}
                                                    </h5>
                                                </td>
                                            </tr> --}}
                                            {{-- <tr class="text-center">
                                                <td class="no"><button
                                                        class="btn btn-primary btn-block">{{ __('file.Per Unit Scale Cost') }}</button>
                                                </td>
                                                <td class="no">
                                                    <h5>{{ $data->per_unit_scale_cost }}</h5>
                                                </td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td class="text-center">
                                                <div class="font-size-10" style="width:250px;float:right;">
                                                    <p style="margin:35px 0 0 0;padding:0;"><b
                                                            class="text-uppercase">{{ $data->created_by }}</b><br>
                                                        {{ date('d-M-Y h:i:s A', strtotime($data->created_at)) }}</p>
                                                    <p class="dashed-border"></p>
                                                    <p style="margin:0;padding:0;">{{ __('file.Generated By') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
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
    <script src="{{ asset('js/jquery.printarea.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '#print-invoice', function() {
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
