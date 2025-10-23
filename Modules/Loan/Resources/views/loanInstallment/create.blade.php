@extends('layouts.app')
@section('title', $page_title)
@push('styles')<link href="{{asset('css/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />@endpush
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
            <form id="contra-voucher-form" method="post">
                @csrf
                <div class="card card-custom">
                    <div class="card-body">
                        <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="form-group col-md-4 required">
                                    <label for="voucher_no"> {{__('file.Voucher No')}}</label>
                                    <input type="text" class="form-control" name="voucher_no" id="voucher_no" value="{{ $voucher_no }}" readonly />
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="voucher_date"> {{__('file.Date')}}</label>
                                    <input type="text" class="form-control date" name="voucher_date" id="voucher_date" value="{{ date('Y-m-d') }}" readonly />
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
                                <div class="col-md-6">
                                    <table class="table table-bordered" id="contra-voucher-debit-table">
                                        <thead  class="bg-primary">
                                        <tr class="text-center">
                                            <th width="10%"></th>
                                            <th width="55%">{{__('file.Account Name')}}</th>
                                            <th width="35%">{{__('file.Debit')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-center">
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm add-more" id="add_more_debit_account" data-type="debit" data-toggle="tooltip" data-placement="top" data-original-title="Add More"><i class="fas fa-plus-square"></i></button>
                                            </td>
                                            <td>
                                                <select name="loan[1][debitId]" id="loan_1_debitId" class="form-control selectpicker" data-live-search="true">
                                                    <option value="">Select Please</option>
                                                    @if (!$transactional_accounts->isEmpty())
                                                        @foreach ($transactional_accounts as $coa)
                                                            <option value="{{ $coa->id }}">{{ $coa->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control debit-amount onInput" name="loan[1][debitAmount]" id="loan_1_debitAmount"/>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td class="text-right" colspan="2">Total</td>
                                            <td><input type="text" class="form-control text-right bg-primary" name="debit_grand_total" id="debit_grand_total" placeholder="0.00" readonly></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered" id="contra-voucher-credit-table">
                                        <thead  class="bg-primary">
                                        <tr class="text-center">
                                            <th width="35%">{{__('file.Amount')}}</th>
                                            <th width="55%">{{__('file.Loan')}}</th>
                                            <th width="10%"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-center">
                                            <td>
                                                <input type="text" class="form-control text-right bg-primary" id="amount" name="amount" />
                                            </td>
                                            <td>
                                                <select class="form-control selectpicker" id="loan_id" name="loan_id" data-live-search="true">
                                                    <option value="">Select Please</option>
                                                    @if (!$loans->isEmpty())
                                                        @foreach ($loans as $value)
                                                            <option value="{{ $value->id }}">{{ $value->loanCategories->name . '-' . $value->bank->bank_name . '-' . $value->reference_no }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td class="bg-primary"></td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td><input type="text" class="form-control text-left bg-primary" id="amount_total" name="amount_total" placeholder="0.00" readonly></td>
                                            <td class="text-left" colspan="2">Total</td>
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
                                <x-form.textarea labelName="Remarks" name="remarks" col="col-md-12"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12 pt-5 text-center">
                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="store_data()"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('js/moment.js')}}"></script>
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        $('.date').datetimepicker({format: 'YYYY-MM-DD',ignoreReadonly: true});
        function _(x){
            return document.getElementById(x);
        }
        let loanDebit  = 100;
        $(document).on('click','.add-more',function(){
            html = `<tr class="text-center">
                          <td><button type="button" class="btn btn-danger btn-sm remove" data-type="debit-remove" data-toggle="tooltip" data-placement="top" data-original-title="Remove"> <i class="fas fa-minus-square"></i></button></td>
                          <td>
                          <select name="loan[` + loanDebit + `][debitId]" id="loan_` + loanDebit + `_debitId" class="form-control selectpicker" data-live-search="true">
                          <option value="">Select Please</option>
                          @if (!$transactional_accounts->isEmpty())
                          @foreach ($transactional_accounts as $coa)
                          <option value="{{ $coa->id }}">{{ $coa->name }}</option>
                          @endforeach
                          @endif
                          </select>
                          </td>
                          <td>
                          <input type="text" class="form-control debit-amount onInput" name="loan[` + loanDebit + `][debitAmount]" id="loan_` + loanDebit + `_debitAmount"/>
                          </td>
                    </tr>`
            $('#contra-voucher-debit-table tbody').append(html);
            $('.selectpicker').selectpicker('refresh');
            loanDebit++;
        });
        $(document).on('click','.remove',function(){ $(this).parent().parent().remove(); calculation(); });
        $(document).on('input','.onInput',function(){  calculation(); });
        function calculation(){
            let debitAmount = 0;
            $('.debit-amount').each(function(){
                if($(this).val() == ''){
                    debitAmount += + 0;
                }else{
                    debitAmount += + $(this).val();
                }
            });
            _('debit_grand_total').value = debitAmount;
            _('amount').value            = debitAmount;
            _('amount_total').value      = debitAmount;
        }
        function store_data(){
            let form = document.getElementById('contra-voucher-form');
            let formData = new FormData(form);
            let url = "{{url('loan-installment/store')}}";
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
                    $('#contra-voucher-form').find('.is-invalid').removeClass('is-invalid');
                    $('#contra-voucher-form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#contra-voucher-form input#' + key).addClass('is-invalid');
                            $('#contra-voucher-form textarea#' + key).addClass('is-invalid');
                            $('#contra-voucher-form select#' + key).parent().addClass('is-invalid');
                            $('#contra-voucher-form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ url('loan-installment') }}"); }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
