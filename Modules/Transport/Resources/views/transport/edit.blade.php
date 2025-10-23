@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    @if(permission('transport-access'))
                        <div class="card-toolbar"><a href="{{ route('transport') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form id="transport-form" method="post">
                            @csrf
                            <div class="row">
                                <input type="hidden" id="update_id" name="update_id" value="{{$details->id}}"/>
                                <div class="form-group col-md-4 required">
                                    <label for="invoice_no">{{__('file.Invoice No')}}</label>
                                    <input type="text" class="form-control bg-primary text-white" name="invoice_no" id="invoice_no" value="{{ $details->invoice_no }}" readonly/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="date">{{__('file.Date')}}</label>
                                    <input type="text" class="form-control date" name="date" id="date" value="{{ $details->date }}" readonly />
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="driver_name">{{__('file.Driver Name')}}</label>
                                    <input type="text" class="form-control" name="driver_name" id="driver_name" value="{{$details->driver_name}}"/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="driver_phone">{{__('file.Driver Phone')}}</label>
                                    <input type="text" class="form-control" name="driver_phone" id="driver_phone" value="{{$details->driver_phone}}"/>
                                </div>
                                <x-form.selectbox labelName="{{__('file.Truck No')}}" name="truck_id" col="col-md-4" required="required" class="selectpicker">
                                    @if (!$trucks->isEmpty())
                                        @foreach ($trucks as $value)
                                            <option value="{{ $value->id }}" @if($value->id == $details->truck_id) selected="selected" @endif>{{ $value->truck_no }}</option>
                                        @endforeach
                                    @endif
                                </x-form.selectbox>
                                <div class="form-group col-md-4">
                                    <label for="party_type">{{__('file.Party Type')}}</label>
                                    <select class="form-control party_type selectpicker" id="party_type" name="party_type" onchange="partyType()">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach (PARTY_TYPE_VALUE as $key => $value)
                                            <option value="{{ $key }}" @if($key == $details->party_type) selected="selected" @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4" id="party_id_show">
                                    <label for="party_id">{{__('file.Party')}}</label>
                                    <select class="form-control party_id selectpicker" id="party_id" name="party_id" data-live-search = "true" onchange="partyDue()">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach($parties as $party)
                                            <option value="{{$party->id}}" @if($party->id == $details->party_id) selected="selected" @endif>{{$party->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4" id="party_name_show">
                                    <label for="party_name">{{__('file.Party Name')}}</label>
                                    <input type="text" class="form-control" id="party_name" name="party_name" value="{{$details->party_name}}"/>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-bordered" id="transportTable">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th width="20%">{{__('file.Rent')}}</th>
                                            <th width="20%" class="text-right">{{__('file.Amount')}}</th>
                                            <th width="20%">{{__('file.Expense')}}</th>
                                            <th width="20%" class="text-right">{{__('file.Amount')}}</th>
                                            <th width="10%"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($details->transportDetailsList as $key => $value)
                                        <tr class="text-center">
                                            @if($key == 0)
                                            <td><input type = "text" class="form-control" id="rent_name" name="rent_name" value="{{$details->rent_name}}" placeholder="{{__('file.Rent Location')}}"/></td>
                                            <td><input type = "text" class="form-control rentAmount" value="{{$details->rent_amount}}" placeholder="0.00"/></td>
                                            @else
                                            <td colspan="2"></td>
                                            @endif
                                            <td>
                                                <select class="form-control selectpicker" id="transport_0_expense_item_id" name="transport[0][expense_item_id]" data-live-search="true">
                                                    <option value="">{{__('file.Please Select')}}</option>
                                                    @foreach($expenseItems as $item)
                                                        <option value="{{$item->id}}" @if($item->id == $value->expense_item_id) selected="selected" @endif>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control expenseAmount" id="transport_0_amount" name="transport[0][amount]" value="{{$value->amount}}" placeholder="0.00"/>
                                            </td>
                                            @if($key == 0)
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm add"  data-toggle="tooltip" data-placement="top" data-original-title="Add More"><i class="fas fa-plus-square"></i></button>
                                            </td>
                                            @else
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm add"  data-toggle="tooltip" data-placement="top" data-original-title="Add More"><i class="fas fa-plus-square"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm remove" data-toggle="tooltip" data-placement="top" data-original-title="Remove"><i class="fas fa-minus-square"></i></button>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td class="text-right"><b>{{__('file.Total')}}({{__('file.Rent')}})</b></td>
                                            <td><input type="text" class="form-control text-right bg-primary text-white" id="rent_amount" name="rent_amount" value="{{$details->rent_amount}}" placeholder="0.00" readonly></td>
                                            <td class="text-right"><b>{{__('file.Total')}}({{__('file.Expense')}})</b></td>
                                            <td><input type="text" class="form-control text-right bg-primary text-white" id="total_expense" name="total_expense" value="{{$details->total_expense}}" placeholder="0.00" readonly></td>
                                            <td class="text-center"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" colspan="3"><b>{{__('file.Income')}}</b></td>
                                            <td><input type="text" class="form-control text-right bg-danger text-white" id="income" name="income" value="{{$details->income}}" placeholder="0.00" readonly></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <x-form.textarea labelName="{{__('file.Note')}}" name="note" col="col-md-12" value="{{$details->note}}"/>
                                <div class="form-group col-md-12 pt-5 text-center">
                                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="updateData()"><i class="fas fa-save"></i>{{__('file.Update')}}</button>
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
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        let i = {{count($details->transportDetailsList)}} + 1;
        partyType();
        function _(x){
            return document.getElementById(x);
        }
        function partyType(){
            let partyType = $('#party_type').find(":selected").val();
            if(partyType == 1){
                _('party_id_show').style.display   = 'block';
                _('party_name_show').style.display = 'none';
                $('#party_id').prop('disabled', false);
                $('#party_name').prop('disabled', true);
            }else if(partyType == 2){
                _('party_id_show').style.display   = 'none';
                _('party_name_show').style.display = 'block';
                $('#party_id').prop('disabled', true);
                $('#party_name').prop('disabled', false);
            }else{
                _('party_id_show').style.display   = 'none';
                _('party_name_show').style.display = 'none';
                $('#party_id').prop('disabled', true);
                $('#party_name').prop('disabled', true);
            }
        }
        $(document).on('click','.add',function(){
            let html;
            html = `<tr class="text-center">
                       <td colspan="2"></td>
                       <td>
                           <select class="form-control selectpicker" id="transport_`+ i +`_expense_item_id" name="transport[`+ i +`][expense_item_id]" data-live-search="true">
                           <option value="">{{__('file.Please Select')}}</option>
                           @foreach($expenseItems as $item)
                           <option value="{{$item->id}}">{{$item->name}}</option>
                           @endforeach
                           </select>
                       </td>
                       <td><input type="text" class="form-control expenseAmount" id="transport_`+ i +`_amount" name="transport[`+ i +`][amount]"/></td>
                       <td>
                           <button type="button" class="btn btn-primary btn-sm add"  data-toggle="tooltip" data-placement="top" data-original-title="Add More"><i class="fas fa-plus-square"></i></button>
                           <button type="button" class="btn btn-danger btn-sm remove" data-toggle="tooltip" data-placement="top" data-original-title="Remove"><i class="fas fa-minus-square"></i></button>
                       </td>
                    </tr>`;
            $('#transportTable tbody').append(html);
            $('.selectpicker').selectpicker('refresh');
            calculation();
        });
        $(document).on('click','.remove',function (){
            $(this).parent().parent().remove();
        });
        $(document).on('input','.rentAmount , .expenseAmount',function(){
            calculation();
        });
        function calculation(){
            let rentAmount    = 0;
            let expenseAmount = 0;
            $('.rentAmount').each(function(){
                if($(this).val() == ''){
                    rentAmount += + 0;
                }else{
                    rentAmount += + $(this).val();
                }
            });
            $('.expenseAmount').each(function(){
                if($(this).val() == ''){
                    expenseAmount += + 0;
                }else{
                    expenseAmount += + $(this).val();
                }
            });
            _('rent_amount').value   = rentAmount;
            _('total_expense').value = expenseAmount;
            _('income').value        = rentAmount - expenseAmount;
        }
        function updateData() {
            let form     = document.getElementById('transport-form');
            let formData = new FormData(form);
            let url      = "{{route('transport.update')}}";
            $.ajax({
                url         : url,
                type        : "POST",
                data        : formData,
                dataType    : "JSON",
                contentType : false,
                processData : false,
                cache       : false,
                beforeSend  : function() {
                    $('#save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete    : function() {
                    $('#save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success     : function(data) {
                    $('#transport-form').find('.is-invalid').removeClass('is-invalid');
                    $('#transport-form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function(key, value) {
                            var key = key.split('.').join('_');
                            $('#transport-form input#' + key).addClass('is-invalid');
                            $('#transport-form textarea#' + key).addClass('is-invalid');
                            $('#transport-form select#' + key).parent().addClass('is-invalid');
                            $('#transport-form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('transport') }}"); }
                    }
                },
                error       : function(xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
