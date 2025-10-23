@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    @if(permission('opening-balance-access'))
                        <div class="card-toolbar"><a href="{{ route('opening.balance') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></div>
                    @endif
                </div>
            </div>
            <form id="opening_balance_form" method="post">
                @csrf
                <div class="card card-custom">
                    <div class="card-body">
                        <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="form-group col-md-4 required">
                                    <label for="voucher_no"> {{__('file.Voucher No')}}</label>
                                    <input type="text" class="form-control bg-primary text-white" name="voucher_no" id="voucher_no" value="{{$voucher_no}}" readonly />
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="date"> {{__('file.Date')}}</label>
                                    <input type="date" class="form-control" name="date" id=date" value="{{ date('Y-m-d') }}"/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="chart_of_head_id">{{__('file.Account')}}</label>
                                    <select name="chart_of_head_id" id="chart_of_head_id" class="form-control selectpicker" data-live-search="true">
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
                                <div class="col-md-6 required">
                                    <label for="amount">{{__('file.Amount')}}</label>
                                    <input type="text" class="form-control" id="amount" name="amount"/>
                                </div>
                                <div class="col-md-6 required">
                                    <label for="balance_type">{{__('file.Balance Type')}}</label>
                                    <select class="form-control selectpicker" id="balance_type" name="balance_type" data-live-search="true">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach(OPENING_BALANCE_VALUE as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
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
                                <x-form.textarea labelName="Narration" name="narration" col="col-md-12"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12 pt-5 text-center">
                    <button type="button" class="btn btn-danger btn-sm mr-3" onclick="window.location.href = '{{route("opening.balance.create")}}';"><i class="fas fa-sync-alt"></i> Reset</button>
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
        function store_data(){
            let form     = _('opening_balance_form');
            let formData = new FormData(form);
            let url      = "{{route('opening.balance.store.or.update')}}";
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
                    $('#opening_balance_form').find('.is-invalid').removeClass('is-invalid');
                    $('#opening_balance_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#opening_balance_form input#' + key).addClass('is-invalid');
                            $('#opening_balance_form textarea#' + key).addClass('is-invalid');
                            $('#opening_balance_form select#' + key).parent().addClass('is-invalid');
                            $('#opening_balance_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('opening.balance') }}"); }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
