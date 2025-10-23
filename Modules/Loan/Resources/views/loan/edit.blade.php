@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                </div>
            </div>
            <form id="loan-form" method="post">
                @csrf
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="row">
                            <input type = "hidden" name="update_id" value="{{$details->id}}"/>
                            <div class="col-md-4">
                                <label for="vendor_id">Loan Categories</label>
                                <select class="form-control selectpicker" id="loan_categories_id" name="loan_categories_id" data-live-search="true"  required="required">
                                    <option value="">Select Loan Categories</option>
                                    @foreach($loanCategories as $value)
                                        <option value="{{$value->id}}" @if($value->id == $details->loan_categories_id) selected="selected" @endif>{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="bank_id">Bank</label>
                                <select class="form-control selectpicker" id="bank_id" name="bank_id" data-live-search="true"  required="required">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $value)
                                        <option value="{{$value->id}}" @if($value->id == $details->bank_id) selected="selected" @endif>{{$value->bank_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="reference_no">{{__('file.Reference No')}}</label>
                                <input type="text" class="form-control" id="reference_no" name="reference_no" value="{{$details->reference_no}}" placeholder="{{__('file.Please Enter Reference No')}}"  required="required"/>
                            </div>
                            <div class="col-md-4"><br/>
                                <label for="loan_amount">{{__('file.Loan Amount')}}</label>
                                <input type="text" class="form-control input" id="loan_amount" name="loan_amount" data-type="loan_amount" value="{{$details->loan_amount}}" placeholder="{{__('file.Please Enter Loan Amount')}}"  required="required"/>
                            </div>
                            <div class="col-md-2"><br/>
                                <label for="loan_percentage_percent">{{__('file.Loan Percentage(%)')}}</label>
                                <input type="text" class="form-control input" id="loan_percentage_percent" name="loan_percentage_percent" data-type="loan_percentage_percent" value="{{$details->loan_percentage_percent}}" placeholder="{{__('file.Please Enter Loan Percentage')}}" required="required"/>
                            </div>
                            <div class="col-md-2"><br/>
                                <label for="loan_percentage_tk">{{__('file.Loan Percentage(tk)')}}</label>
                                <input type="text" class="form-control input" id="loan_percentage_tk" name="loan_percentage_tk" data-type="loan_percentage_tk" value="{{$details->loan_percentage_tk}}" placeholder="{{__('file.Please Enter Loan Percentage')}}" required="required"/>
                            </div>
                            <div class="col-md-4"><br/>
                                <label for="total_amount">{{__('file.Total Amount')}}</label>
                                <input type="text" class="form-control bg-primary text-white" id="total_amount" name="total_amount" value="{{$details->total_amount}}" readonly/>
                            </div>
                            <div class="col-md-4"><br/>
                                <label for="processing_charge">{{__('file.Processing Charge')}}</label>
                                <input type="text" class="form-control" id="processing_charge" name="processing_charge" value="{{$details->processing_charge}}" placeholder="{{__('file.Please Enter Processing Charge')}}"/>
                            </div>
                            <div class="col-md-4"><br/>
                                <label for="misc_charge">{{__('file.MISC Charge')}}</label>
                                <input type="text" class="form-control" id="misc_charge" name="misc_charge" value="{{$details->misc_charge}}" placeholder="{{__('file.Please Enter MISC Charge')}}"/>
                            </div>
                            <div class="col-md-4"><br/>
                                <label for="apply_date">{{__('file.Apply Date')}}</label>
                                <input type="date" class="form-control" id="apply_date" name="apply_date" value="{{$details->apply_date}}" required="required"/>
                            </div>
                            <div class="col-md-4"><br/>
                                <label for="total_installment_month">{{__('file.Total Installment Month')}}</label>
                                <input type="text" class="form-control input" id="total_installment_month" name="total_installment_month" data-type="total_installment_month" value="{{$details->total_installment_month}}" placeholder="{{__('file.Please Enter Total Installment Month')}}" required="required"/>
                            </div>
                            <div class="col-md-4"><br/>
                                <label for="monthly_installment">{{__('file.Monthly Installment')}}</label>
                                <input type="text" class="form-control bg-primary text-white" id="monthly_installment" name="monthly_installment" value="{{$details->monthly_installment}}" readonly/>
                            </div>
                            <div class="col-md-4"><br/>
                                <label for="document">{{__('file.Document')}}</label>
                                <input type="file" class="form-control" id="document" name="document"  required="required"/>
                            </div>
                            <div class="col-md-12"><br/>
                                <label for="note">{{__('file.Note')}}</label>
                                <textarea class="form-control" id="note" name="note" rows="6">{{$details->note}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12 pt-5 text-center">
                    <button type="button" class="btn btn-primary btn-sm mr-3" id="update-btn" onclick="update_data()"><i class="fas fa-save"></i> Update</button>
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
        $(document).on('input','.input',function(){
            let type = $(this).data('type');
            if(type == 'loan_amount'){
                _('total_amount').value = + parseInt($(this).val()) + + ( parseInt($(this).val()) * parseInt(_('loan_percentage_percent').value)) / 100;
                _('loan_percentage_tk').value = ( parseInt($(this).val()) * parseInt(_('loan_percentage_percent').value)) / 100;
                _('monthly_installment').value = _('total_amount').value / _('total_installment_month').value;
            }else if(type == 'loan_percentage_percent'){
                if(_('loan_amount').value != ''){
                    _('total_amount').value = + parseInt(_('loan_amount').value) + + ( parseInt(_('loan_amount').value)) * parseInt($(this).val()) / 100;
                    _('loan_percentage_tk').value = ( parseInt(_('loan_amount').value)) * parseInt($(this).val()) / 100;
                    _('monthly_installment').value = _('total_amount').value / _('total_installment_month').value;
                }else{
                    notification('error','{{__("file.Loan Amount Field Empty")}}');
                }
            }else if(type == 'loan_percentage_tk'){
                if(_('loan_amount').value != ''){
                    _('total_amount').value = + parseInt(_('loan_amount').value) + + parseInt($(this).val());
                    _('loan_percentage_percent').value = ( parseInt($(this).val()) * 100 / parseInt(_('loan_amount').value));
                    _('monthly_installment').value = _('total_amount').value / _('total_installment_month').value;
                }else{
                    notification('error','{{__('file.Loan Amount Field Empty')}}');
                }
            }else if(type == 'total_installment_month'){
                if(_('total_amount').value != ''){
                    _('monthly_installment').value = _('total_amount').value / $(this).val();
                }else{
                    _('total_installment_month').value = '';
                    notification('error','{{__("file.Total Amount Field Empty")}}');
                }
            }
        });
        function update_data(){
            let form     = document.getElementById('loan-form');
            let formData = new FormData(form);
            let url      = "{{url('loan/update')}}";
            $.ajax({
                url         : url,
                type        : "POST",
                data        : formData,
                dataType    : "JSON",
                contentType : false,
                processData : false,
                cache       : false,
                beforeSend  : function () {
                    $('#update-btn').addClass('spinner spinner-white spinner-right');
                },
                complete    : function () {
                    $('#update-btn').removeClass('spinner spinner-white spinner-right');
                },
                success     : function (data) {
                    $('#loan-form').find('.is-invalid').removeClass('is-invalid');
                    $('#loan-form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#loan-form input#' + key).addClass('is-invalid');
                            $('#loan-form textarea#' + key).addClass('is-invalid');
                            $('#loan-form select#' + key).parent().addClass('is-invalid');
                            $('#loan-form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ url('loan') }}");  }
                    }
                },
                error      : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
