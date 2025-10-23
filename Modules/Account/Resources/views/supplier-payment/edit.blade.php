@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    @if(permission('supplier-payment-access'))
                        <div class="card-toolbar"><a href="{{ route('supplier.payment') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form id="supplier-payment-form" method="post">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="update_id" value="{{$voucherNo}}"/>
                                <div class="form-group col-md-6 required">
                                    <label for="voucher_no">{{__('file.Voucher No')}}</label>
                                    <input type="text" class="form-control bg-primary" name="voucher_no" id="voucher_no" value="{{ $voucherNo }}" readonly />
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="voucher_date">{{__('file.Date')}}</label>
                                    <input type="date" class="form-control" name="date" id="date" value="{{ $date }}"/>
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="party_id">{{__('file.Party')}}</label>
                                    <select class="form-control selectpicker" id="party_id" name="party_id" onchange="partyDue()" data-live-search="true">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach($parties as $party)
                                            <option value="{{$party->id}}" @if($partyId == $party->id) selected="selected" @endif>{{$party->name.'('.$party->address.')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <div id="previous_due_status"></div>
                                    <label for="previous_due">{{ __('file.Due Amount') }}</label>
                                    <input type="text" class="form-control bg-primary text-center" name="previous_due" id="previous_due" readonly>
                                </div>
                                <x-form.selectbox labelName="{{__('file.Payment Method')}}" name="payment_method" required="required"  col="col-md-6" class="selectpicker" onchange="paymentMethod()">
                                    @foreach (PAYMENT_METHOD as $key => $value)
                                        <option value="{{ $key }}" @if($paymentMethod == $key) selected="selected" @endif>{{ $value }}</option>
                                    @endforeach
                                </x-form.selectbox>
                                <x-form.selectbox labelName="{{__('file.Account')}}" name="account_id" required="required"  col="col-md-6" class="selectpicker"/>
                                <x-form.textbox labelName="{{__('file.Amount')}}" name="amount" value="{{$amount}}" required="required" col="col-md-6" placeholder="0.00"/>
                                <x-form.textarea labelName="{{__('file.Narration')}}" name="narration" value="{{$narration}}" col="col-md-6"/>
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
        @if(!empty($partyId)) partyDue(); paymentMethod(); @endif
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
        function partyDue(){
            let partyId = $('#party_id').find('option:selected').val();
            if(partyId != ''){
                $.ajax({
                    url      : "{{url('party/due')}}/" + partyId,
                    type     : "GET",
                    dataType : "JSON",
                    success  : function(data){
                        if(data > 0){
                            _('previous_due').value            = -Math.abs(data);
                            _('previous_due_status').innerHTML = '<span class = "text-success">{{__('file.Your Receivable Old Outstanding')}}</span>';
                        }else{
                            _('previous_due').value            = Math.abs(data);
                            _('previous_due_status').innerHTML = '<span class = "text-danger">{{__('file.Your Payable Old Outstanding')}}</span>';
                        }
                    }
                })
            }else{
                notification('error','Party Not Selected');
            }
        }
        function updateData(){
            let form     = document.getElementById('supplier-payment-form');
            let formData = new FormData(form);
            let url      = "{{route('supplier.payment.update')}}";
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
                    $('#supplier-payment-form').find('.is-invalid').removeClass('is-invalid');
                    $('#supplier-payment-form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#supplier-payment-form input#' + key).addClass('is-invalid');
                            $('#supplier-payment-form textarea#' + key).addClass('is-invalid');
                            $('#supplier-payment-form select#' + key).parent().addClass('is-invalid');
                            $('#supplier-payment-form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('supplier.payment') }}"); }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
