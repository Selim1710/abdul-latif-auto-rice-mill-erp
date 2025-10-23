@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    @if(permission('expense-access'))
                        <div class="card-toolbar"><a href="{{ route('expense') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form id="expense-form" method="post">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="update_id" value="{{$voucherNo}}"/>
                                <div class="form-group col-md-6 required">
                                    <label for="voucher_no">{{__('file.Voucher No')}}</label>
                                    <input type="text" class="form-control bg-primary text-white" name="voucher_no" id="voucher_no" value="{{ $voucherNo }}" readonly />
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="voucher_date">{{__('file.Date')}}</label>
                                    <input type="date" class="form-control" name="date" id="date" value="{{ $date }}"/>
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="expense_item_id">{{__('file.Expense Item')}}</label>
                                    <select class="form-control selectpicker" id="expense_item_id" name="expense_item_id" data-live-search="true">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach($expenseItems as $item)
                                            <option value="{{$item->id}}" @if($item->id == $expenseItemId) selected="selected" @endif>{{$item->name.'('.EXPENSE_TYPE_VALUE[$item->expense_type].')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-form.selectbox labelName="{{__('file.Payment Method')}}" name="payment_method" required="required"  col="col-md-6" class="selectpicker" onchange="paymentMethod()">
                                    @foreach (PAYMENT_METHOD as $key => $value)
                                        <option value="{{ $key }}" @if($paymentMethod == $key) selected="selected" @endif>{{ $value }}</option>
                                    @endforeach
                                </x-form.selectbox>
                                <x-form.selectbox labelName="{{__('file.Account')}}" name="account_id" required="required"  col="col-md-6" class="selectpicker"/>
                                <x-form.textbox labelName="{{__('file.Amount')}}" name="amount" value="{{$amount}}" required="required" col="col-md-6" placeholder="0.00"/>
                                <x-form.textarea labelName="{{__('file.Narration')}}" name="narration" value="{{$narration}}" col="col-md-12"/>
                                <div class="form-group col-md-12 text-center">
                                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="updateData()"><i class="fas fa-save"></i> {{__('file.Update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        @if(!empty($expenseItemId)) paymentMethod(); @endif
        function _(x){
            return document.getElementById(x);
        }
        function paymentMethod(){
            let paymentMethod =  $('#payment_method').find('option:selected').val();
            let html;
            if(paymentMethod != ''){
                $.ajax({
                    url       : "{{url('account-list')}}/" + paymentMethod,
                    type      : 'GET',
                    dataType  : 'JSON',
                    success   : function(data){
                        if(data != ''){
                            html = `<option value="">Select Please</option>`;
                            $.each(data, function(key, value) { html += '<option value="'+ value.id +'">'+ value.name +'</option>'; });
                            $('#account_id').empty();
                            $('#account_id').append(html);
                            $('#account_id').val({{$account}});
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                })
            }
        }
        function updateData(){
            let form     = document.getElementById('expense-form');
            let formData = new FormData(form);
            let url      = "{{route('expense.update')}}";
            $.ajax({
                url         : url,
                type        : "POST",
                data        : formData,
                dataType    : "JSON",
                contentType : false,
                processData : false,
                cache       : false,
                beforeSend  : function(){
                    $('#save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete    : function(){
                    $('#save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success     : function (data) {
                    $('#expense-form').find('.is-invalid').removeClass('is-invalid');
                    $('#expense-form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#expense-form input#' + key).addClass('is-invalid');
                            $('#expense-form textarea#' + key).addClass('is-invalid');
                            $('#expense-form select#' + key).parent().addClass('is-invalid');
                            $('#expense-form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('expense') }}"); }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
