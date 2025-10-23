@extends('layouts.app')
@section('title', $page_title)
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                @if(permission('voucher-access'))
                    <div class="card-toolbar"><a href="{{ route('voucher') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></div>
                @endif
            </div>
        </div>
        <form id="voucher_form" method="post">
            @csrf
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="form-group col-md-4 required">
                                <label for="voucher_no"> {{__('file.Voucher No')}}</label>
                                <input type="text" class="form-control bg-primary" name="voucher_no" id="voucher_no" readonly />
                            </div>
                            <div class="form-group col-md-4 required">
                                <label for="voucher_date"> {{__('file.Date')}}</label>
                                <input type="date" class="form-control" name="date" id=date" value="{{ date('Y-m-d') }}"/>
                            </div>
                            <div class="form-group col-md-4 required">
                                <label for="voucher_type"> {{__('file.Voucher Type')}}</label>
                                <select class="form-control voucher_type selectpicker" id="voucher_type" name="voucher_type" required >
                                    <option value="">Select Please</option>
                                        @foreach (VOUCHER_TYPE_VALUE as $key=>$type)
                                            <option value="{{$key }}">{{ $type }}</option>
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
                            <div class="col-md-6">
                                <table class="table table-bordered" id="debit-table">
                                    <thead  class="bg-primary">
                                    <tr class="text-right">
                                        <th width="10%"></th>
                                        <th width="55%">{{__('file.Account Name')}}</th>
                                        <th width="35%">{{__('file.Debit')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="text-center">
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm add-more" id="add_more" data-type="debit-add" data-toggle="tooltip" data-placement="top" data-original-title="Add More"><i class="fas fa-plus-square"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm remove" data-type="debit-remove" data-toggle="tooltip" data-placement="top" data-original-title="Remove" style="margin-top:3px"> <i class="fas fa-minus-square"></i></button>
                                        </td>
                                        <td>
                                            <select name="debit[0][chart_of_head_id]" id="debit_0_chart_of_head_id" class="form-control selectpicker" data-live-search="true">
                                                <option value="">Select Please</option>
                                                @foreach($heads as $head)
                                                    <option value="{{$head->id}}">{!! $head->name !!}</option>
                                                @endforeach
                                                @foreach($subHeads as $subHead)
                                                    <option value="{{$subHead->id}}">{!! $subHead->head->name.'  ---  '.$subHead->name !!}</option>
                                                @endforeach
                                                @foreach($childHeads as $child)
                                                    <option value="{{$child->id}}">{!! $child->head->name.'  ---  '.$child->subHead->name.'  ---  '.$child->name !!}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control debitAmount onInput" name="debit[0][debit]" id="debit_0_debit"/>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td class="text-right" colspan="2">Total</td>
                                        <td><input type="text" class="form-control text-right bg-primary" name="total_debit_amount" id="total_debit_amount" placeholder="0.00" readonly></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered" id="credit-table">
                                    <thead  class="bg-primary">
                                    <tr>
                                        <th width="35%">{{__('file.Credit')}}</th>
                                        <th width="55%">{{__('file.Account Name')}}</th>
                                        <th width="10%"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="text-center">
                                        <td>
                                            <input type="text" class="form-control creditAmount onInput" name="credit[0][credit]" id="credit_0_credit"/>
                                        </td>
                                        <td>
                                            <select class="form-control selectpicker" id="credit_0_chart_of_head_id" name="credit[0][chart_of_head_id]" data-live-search="true">
                                                <option value="">Select Please</option>
                                                @foreach($heads as $head)
                                                    <option value="{{$head->id}}">{!! $head->name !!}</option>
                                                @endforeach
                                                @foreach($subHeads as $subHead)
                                                    <option value="{{$subHead->id}}">{!! $subHead->head->name.'  ---  '.$subHead->name !!}</option>
                                                @endforeach
                                                @foreach($childHeads as $child)
                                                    <option value="{{$child->id}}">{!! $child->head->name.'  ---  '.$child->subHead->name.'  ---  '.$child->name !!}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm add-more" id="credit-add" data-type="credit" data-toggle="tooltip" data-placement="top" data-original-title="Add More"><i class="fas fa-plus-square"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm remove" data-type="credit-remove" data-toggle="tooltip" data-placement="top" data-original-title="Remove" style="margin-top:3px"> <i class="fas fa-minus-square"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td><input type="text" class="form-control text-right bg-primary" name="total_credit_amount" id="total_credit_amount" placeholder="0.00" readonly></td>
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
                            <x-form.textarea labelName="Narration" name="narration" col="col-md-12"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12 pt-5 text-center">
                <button type="button" class="btn btn-danger btn-sm mr-3" onclick="window.location.href = '{{route("voucher.create")}}';"><i class="fas fa-sync-alt"></i> Reset</button>
                <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="store_data()"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
        $('.date').datetimepicker({format: 'YYYY-MM-DD',ignoreReadonly: true});
        let i = 1;
        function _(x){
            return document.getElementById(x);
        }
        $(document).on('input','.onInput',function(){
            calculation();
        });
        $(document).on('change','.voucher_type',function(){
            let voucher_no        = {{round(microtime(true) * 1000)}};
            let voucher_type      = $(this).find(":selected").val();
            _('voucher_no').value = voucher_type + '-' + voucher_no;
        });
        $(document).on('click','.add-more',function(){
           let type = $(this).data('type');
           if(type == 'debit-add'){
               if($('.creditAmount').length == 1) {
                   html = `<tr class="text-center">
                             <td>
                               <button type="button" class="btn btn-primary btn-sm add-more" id="add_more" data-type="debit-add" data-toggle="tooltip" data-placement="top" data-original-title="Add More"><i class="fas fa-plus-square"></i></button>
                               <button type="button" class="btn btn-danger btn-sm remove" data-type="debit-remove" data-toggle="tooltip" data-placement="top" data-original-title="Remove" style="margin-top:3px"> <i class="fas fa-minus-square"></i></button>
                             </td>
                             <td>
                               <select name="debit[`+ i +`][chart_of_head_id]" id="debit_`+ i +`_chart_of_head_id" class="form-control selectpicker" data-live-search="true">
                               <option value="">Select Please</option>
                               @foreach($heads as $head)
                               <option value="{{$head->id}}">{!! $head->name !!}</option>
                               @endforeach
                               @foreach($subHeads as $subHead)
                               <option value="{{$subHead->id}}">{!! $subHead->head->name.'  ---  '.$subHead->name !!}</option>
                               @endforeach
                               @foreach($childHeads as $child)
                               <option value="{{$child->id}}">{!! $child->head->name.'  ---  '.$child->subHead->name.'  ---  '.$child->name !!}</option>
                               @endforeach
                               </select>
                             </td>
                             <td>
                               <input type="text" class="form-control debitAmount onInput" name="debit[`+ i +`][debit]" id="debit_`+ i +`_debit"/>
                             </td>
                          </tr>`
                   $('#debit-table tbody').append(html);
                   $('.selectpicker').selectpicker('refresh');
                   contraDebitAccount++;
               }else{
                   notification('error','You Can\'t Create Multiple Debit Field & Multiple Credit Field');
               }
           }else{
               if($('.debitAmount').length == 1) {
                   html = `<tr class="text-center">
                             <td>
                               <input type="text" class="form-control creditAmount onInput" name="credit[`+ i +`][credit]" id="credit_0_credit"/>
                             </td>
                             <td>
                               <select class="form-control selectpicker" id="credit_`+ i +`_chart_of_head_id" name="credit[`+ i +`][chart_of_head_id]" data-live-search="true">
                               <option value="">Select Please</option>
                               @foreach($heads as $head)
                               <option value="{{$head->id}}">{!! $head->name !!}</option>
                               @endforeach
                               @foreach($subHeads as $subHead)
                               <option value="{{$subHead->id}}">{!! $subHead->head->name.'  ---  '.$subHead->name !!}</option>
                               @endforeach
                               @foreach($childHeads as $child)
                               <option value="{{$child->id}}">{!! $child->head->name.'  ---  '.$child->subHead->name.'  ---  '.$child->name !!}</option>
                               @endforeach
                               </select>
                             </td>
                             <td>
                               <button type="button" class="btn btn-primary btn-sm add-more" id="credit-add" data-type="credit" data-toggle="tooltip" data-placement="top" data-original-title="Add More"><i class="fas fa-plus-square"></i></button>
                               <button type="button" class="btn btn-danger btn-sm remove" data-type="credit-remove" data-toggle="tooltip" data-placement="top" data-original-title="Remove" style="margin-top:3px"> <i class="fas fa-minus-square"></i></button>
                             </td>
                           </tr>`
                   $('#credit-table tbody').append(html);
                   $('.selectpicker').selectpicker('refresh');
                   contraCreditAccount++;
               }else{
                   notification('error','You Can\'t Create Multiple Debit Field & Multiple Credit Field');
               }
           }
        });
        $(document).on('click','.remove',function(){ $(this).parent().parent().remove(); calculation(); });
        $(document).on('input','.onInput',function(){  calculation(); });
        function calculation(){
            let debitAmount = 0;
            let creditAmount = 0;
          $('.debitAmount').each(function(){
              if($(this).val() == ''){
                  debitAmount += + 0;
              }else{
                  debitAmount += + $(this).val();
              }
          });
          $('.creditAmount').each(function(){
              if($(this).val() == ''){
                  creditAmount += + 0;
              }else{
                  creditAmount += + $(this).val();
              }
          });
          _('total_debit_amount').value  = debitAmount;
          _('total_credit_amount').value = creditAmount;
        }
        function store_data(){
            let form     = document.getElementById('voucher_form');
            let formData = new FormData(form);
            let url      = "{{route('voucher.store')}}";
            if(_('total_debit_amount').value < 0 &&  _('total_credit_amount').value < 0) {
                notification('error','{{__('file.Debit Total & Credit Total Amount Must Greater Than 0')}}');
                return;
            }
            if(_('total_debit_amount').value != _('total_credit_amount').value){
                notification('error','{{__('file.Debit Total & Credit Total Must Be Equal')}}');
                return;
            }
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
                    $('#voucher_form').find('.is-invalid').removeClass('is-invalid');
                    $('#voucher_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#voucher_form input#' + key).addClass('is-invalid');
                            $('#voucher_form textarea#' + key).addClass('is-invalid');
                            $('#voucher_form select#' + key).parent().addClass('is-invalid');
                            $('#voucher_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ url('voucher') }}"); }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
</script>
@endpush
