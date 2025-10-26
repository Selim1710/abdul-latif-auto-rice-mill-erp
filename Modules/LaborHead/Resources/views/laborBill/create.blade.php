@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    @if(permission('labor-bill-access'))
                        <div class="card-toolbar"><a href="{{ route('labor.bill') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></div>
                    @endif
                </div>
            </div>
            <form id="labor_bill_form" method="post">
                @csrf
                <div class="card card-custom">
                    <div class="card-body">
                        <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="form-group col-md-4 required">
                                    <label for="invoice_no"> {{__('file.Invoice No')}}</label>
                                    <input type="text" class="form-control bg-primary" id="invoice_no" name="invoice_no" value="{{$invoice_no}}" readonly />
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="voucher_date"> {{__('file.Date')}}</label>
                                    <input type="date" class="form-control" name="date" id="date" value="{{ date('Y-m-d') }}"/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="labor_head_id"> {{__('file.Labor')}}</label>
                                    <select class="form-control labor_head_id selectpicker" id="labor_head_id" name="labor_head_id" required data-live-search = "true">
                                        <option value="">Select Please</option>
                                        @foreach ($laborHeads as $labor)
                                            <option value="{{$labor->id }}">{{ $labor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="card card-custom">
                    <div class="card-body">
                        <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead class="bg-primary">
                                        <tr class="text-center">
                                            <th style="width:40%">{{__('file.Name')}}</th>
                                            <th style="width:15%">{{__('file.Rate')}}</th>
                                            <th style="width:15%">{{__('file.Qty')}}</th>
                                            <th style="width:30%">{{__('file.Amount')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($laborBillRates as $key => $bill)
                                            <tr class="text-center">
                                                <td>
                                                    <input type="text" class="form-control bg-primary" value="{{$bill->name}}" readonly/>
                                                    <input type="hidden" id="bill_{{$key}}_labor_bill_rate_id" name="bill[{{$key}}][labor_bill_rate_id]" value="{{$bill->id}}"/>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control rate" id="bill_{{$key}}_rate" name="bill[{{$key}}][rate]" data-qty="bill_{{$key}}_qty" data-amount="bill_{{$key}}_amount" value="{{$bill->rate}}"/>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control qty" id="bill_{{$key}}_qty" name="bill[{{$key}}][qty]" data-rate="bill_{{$key}}_rate" data-amount="bill_{{$key}}_amount"/>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control bg-primary amount" id="bill_{{$key}}_amount" name="bill[{{$key}}][amount]" readonly/>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr class="text-center">
                                            <td colspan="3"><button type="button" class="btn btn-primary btn-block">{{__('file.Total Amount')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary" id="total_amount"/></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="card card-custom">
                    <div class="card-body">
                        <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <x-form.textarea labelName="Narration" name="narration" col="col-md-12"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12 pt-5 text-center">
                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="storeData()"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function _(x){
            return document.getElementById(x);
        }
        $(document).on('input','.rate',function(){
            let qty    = $(this).data('qty');
            let amount = $(this).data('amount');
            if(_(qty).value == ''){
                notification('error','Quantity Field Is Empty');
                return;
            }
            _(amount).value = $(this).val() * _(qty).value;
            calculation();
        });
        $(document).on('input','.qty',function(){
            let rate   = $(this).data('rate');
            let amount = $(this).data('amount');
            if(_(rate).value == ''){
                notification('error','Rate Field Is Empty');
                return;
            }
            _(amount).value = $(this).val() * _(rate).value;
            calculation();
        })
        function calculation(){
            let amount = 0;
            $('.amount').each(function(){
                if($(this).val() == ''){
                    amount += + 0;
                }else{
                    amount += + $(this).val();
                }
            });
            _('total_amount').value = amount;
        }
        function storeData(){
            let form     = document.getElementById('labor_bill_form');
            let formData = new FormData(form);
            let url      = "{{route('labor.bill.store')}}";
            $.ajax({
                url         : url,
                type        : "POST",
                data        : formData,
                dataType    : "JSON",
                contentType : false,
                processData : false,
                cache       : false,
                beforeSend  : function () {
                    $('#save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete    : function () {
                    $('#save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success     : function (data) {
                    $('#labor_bill_form').find('.is-invalid').removeClass('is-invalid');
                    $('#labor_bill_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#labor_bill_form input#' + key).addClass('is-invalid');
                            $('#labor_bill_form textarea#' + key).addClass('is-invalid');
                            $('#labor_bill_form select#' + key).parent().addClass('is-invalid');
                            $('#labor_bill_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('labor.bill') }}"); }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
