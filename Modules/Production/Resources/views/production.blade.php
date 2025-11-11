@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/pages/wizard/wizard-4.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .wizard.wizard-4 .wizard-nav .wizard-steps {
            flex-direction: column;
        }

        .wizard.wizard-4 .wizard-nav .wizard-steps .wizard-step .wizard-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            flex-wrap: unset !important;
            color: #3F4254;
            padding: 2rem 2.5rem !important;
            height: 58px !important;
            margin-bottom: 5px;
            background-color: gainsboro;
            justify-content: center;
        }

        .wizard.wizard-4 .wizard-nav .wizard-steps .wizard-step {
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    @php $i = 1 @endphp
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <div>
                            {{-- @if (permission('production-sale-add'))
                                <span><a href="{{ route('production.sale.add', $production->id) }}"
                                        class="btn btn-danger btn-sm font-weight-bolder"><i
                                            class="fab fa-opencart"></i>&nbsp;{{ __('file.Sale') }}</a></span>
                            @endif --}}
                            @if (permission('production-product-add'))
                                <span><a href="{{ route('production.product.add', $production->id) }}"
                                        class="btn btn-info btn-sm font-weight-bolder"><i
                                            class="fas fa-boxes"></i>&nbsp;{{ __('file.Stock') }}</a></span>
                            @endif
                            @if (permission('production-show'))
                                <span><a href="{{ route('production.show', $production->id) }}"
                                        class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-eye"></i>
                                        {{ __('file.Details') }}</a></span>
                            @endif
                            @if (permission('production-access'))
                                <span><a href="{{ route('production') }}"
                                        class="btn btn-secondary btn-sm font-weight-bolder"><i
                                            class="fas fa-arrow-circle-left"></i> {{ __('file.Back') }}</a></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <form class="form mt-0 mt-lg-10 fv-plugins-bootstrap fv-plugins-framework" id="store_or_update_form"
                method="POST">
                @csrf
                <input type="hidden" name="production_id" value="{{ $production->id }}" />
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr class="text-center font-weight-bold text-primary">
                                        <td>{{ __('file.Invoice No') }}</td>
                                        <td>{{ ':' }}</td>
                                        <td>{{ $production->invoice_no }}</td>
                                        <td>{{ __('file.Start Date') }}</td>
                                        <td>{{ ':' }}</td>
                                        <td>{{ $production->start_date }}</td>
                                        <td>{{ __('file.Mill') }}</td>
                                        <td>{{ ':' }}</td>
                                        <td>{{ $production->mill->name }}</td>
                                    </tr>
                                    <tr class="text-center font-weight-bold text-primary">
                                        <td>{{ __('file.Batch No') }}</td>
                                        <td>{{ ':' }}</td>
                                        <td>{{ $production->batch_no }}</td>
                                        <td>{{ __('file.Production Type') }}</td>
                                        <td>{{ ':' }}</td>
                                        <td colspan="4">{{ $production->production_type }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="wizard wizard-4" id="kt_wizard" data-wizard-state="first" data-wizard-clickable="true">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="wizard-nav">
                                        <div class="wizard-steps">
                                            <div class="wizard-step step step-1" data-wizard-type="step"
                                                data-wizard-state="current">
                                                <div class="wizard-wrapper">
                                                    <div class="wizard-label">
                                                        <div class="wizard-title">{{ __('file.Milling') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wizard-step step step-2" data-wizard-type="step"
                                                data-wizard-state="pending">
                                                <div class="wizard-wrapper">
                                                    <div class="wizard-label">
                                                        <div class="wizard-title">{{ __('file.Expense') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wizard-step step step-3" data-wizard-type="step"
                                                data-wizard-state="pending">
                                                <div class="wizard-wrapper">
                                                    <div class="wizard-label">
                                                        <div class="wizard-title">{{ __('file.Summary') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="card card-custom card-shadowless rounded-top-0">
                                        <div class="card-body p-0">
                                            <div
                                                class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10 tenant-production-card">
                                                <div class="col-xl-12 col-xxl-12">
                                                    <div class="pb-5 step step-1" data-wizard-type="step-content"
                                                        data-wizard-state="current">
                                                        @include('production::productionForm.milling')
                                                    </div>
                                                    <div class="pb-5 step step-2" data-wizard-type="step-content">
                                                        @include('production::productionForm.expense')
                                                    </div>
                                                    <div class="pb-5 step step-3" data-wizard-type="step-content">
                                                        @include('production::productionForm.summary')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript">
        function _(x) {
            return document.getElementById(x);
        }

        function __(y) {
            return document.getElementsByClassName(y);
        }
        $(document).on('click', '.next_button', function() {
            let type = $(this).data('type');
            if (type == 'milling') {
                for (let i = 0; i < $('.rate').length; i++) {
                    if (__('useQty')[i].value == '' || __('useScale')[i].value == '' || __('useProQty')[i].value ==
                        '' || __('rate')[i].value == '') {
                        notification('error', '{{ __('file.Empty Input Field') }}');
                        return;
                    }
                }
                show_form(2);
                calculation();
            } else if (type == 'expense') {
                show_form(3);
                calculation();
            }
        });
        $(document).on('input', '.useQty', function() {
            let proQty = $(this).data('pro_qty');
            let scale = $(this).data('scale');
            let unitId = $(this).data('unit_id');
            let useScale = $(this).data('use_scale');
            _(useScale).value = $(this).val() * _(unitId).value;
            if (parseFloat($(this).val()) > parseFloat(_(proQty).value)) {
                $(this).val('');
                _(useScale).value = '';
                notification('error', 'Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            if (parseFloat(_(useScale).value) > parseFloat(_(scale).value)) {
                $(this).val('');
                _(useScale).value = '';
                notification('error', 'Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            calculation();
        });
        $(document).on('input', '.useScale', function() {
            let proQty = $(this).data('pro_qty');
            let scale = $(this).data('scale');
            let unitId = $(this).data('unit_id');
            let useQty = $(this).data('use_qty');
            _(useQty).value = $(this).val() / _(unitId).value;
            if (parseFloat(_(useQty).value) > parseFloat(_(proQty).value)) {
                $(this).val('');
                _(useQty).value = '';
                notification('error', 'Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            if (parseFloat($(this).val()) > parseFloat(_(scale).value)) {
                $(this).val('');
                _(useQty).value = '';
                notification('error', 'Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            calculation();
        });
        $(document).on('input', '.useProQty', function() {
            let proQty = $(this).data('pro_qty');
            if (parseFloat($(this).val()) > parseFloat(_(proQty).value)) {
                $(this).val('');
                notification('error', 'Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            calculation();
        });
        $(document).on('input', '.rate', function() {
            let useScale = $(this).data('use_scale');
            let milling = $(this).data('milling');
            if (_(useScale).value == '') {
                $(this).val('');
                notification('error', 'Please Give Use Scale');
                return;
            }
            _(milling).value = parseFloat($(this).val()) * parseFloat(_(useScale).value);
            calculation();
        });
        $(document).on('input', '.milling', function() {
            calculation();
        });

        // total milling cost
        _('total_milling').value = $('#total_milling_show').val();
        $(document).on('input', '#total_milling_show', function() {
            _('total_milling').value = $(this).val();
        });

        $(document).on('input', '.expense_cost', function() {
            calculation();
        })

        function calculation() {
            let productionRawAmount = 0;
            let productionRawScale = 0;
            // let milling = 0;
            let expenseCost = 0;
            // $('.milling').each(function() {
            //     if ($(this).val() == '') {
            //         milling += +0;
            //     } else {
            //         milling += +$(this).val();
            //     }
            // });
            $('.useScale').each(function() {
                if ($(this).val() == '') {
                    productionRawScale += +0;
                } else {
                    productionRawScale += +$(this).val();
                }
            });
            $('.useQty').each(function() {
                if ($(this).val() == '') {
                    productionRawAmount += +0;
                } else {
                    productionRawAmount += +$(this).val() * $(this).data('price');
                }
            });
            $('.expense_cost').each(function() {
                if ($(this).val() == '') {
                    expenseCost += +0;
                } else {
                    expenseCost += +$(this).val();
                }
            });

            // _('total_milling_show').value = milling;
            // _('total_milling').value = milling;

            _('total_expense_show').value = expenseCost;
            _('total_raw_scale').value = productionRawScale;
            _('total_raw_amount').value = productionRawAmount;


            _('total_expense').value = expenseCost;
            _('per_unit_scale_cost').value = (+productionRawAmount + +_('total_use_product_amount').value +
                // +milling +
                +
                expenseCost) / (+_('total_sale_scale').value + +_('total_stock_scale').value);
        }

        function show_form(step) {
            $('.step').attr('data-wizard-state', 'pending');
            $('.step-' + step).attr('data-wizard-state', 'current');
        }

        function store_data() {
            let form = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            if (_('total_stock_scale').value == '') {
                notification('error', 'There Is No Stock In These Production Please Entry Production Stock Entry');
                return;
            }
            let url = "{{ route('production.complete') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $('#save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete: function() {
                    $('#save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success: function(data) {
                    if (data.status == false) {
                        notification(data.status, data.message);
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            window.location.replace("{{ route('production') }}");
                        }
                    }
                },
                error: function(xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    </script>
@endpush
