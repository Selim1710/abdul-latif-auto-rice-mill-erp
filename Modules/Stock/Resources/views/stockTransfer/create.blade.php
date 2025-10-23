@extends('layouts.app')
@section('title', $page_title)
@push('styles') <link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" /> @endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    <div class="card-toolbar"><a href="{{ route('stock.transfer') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i>{{'Back'}}</a></div>
                </div>
            </div>
            <div class="card card-custom" style="padding-bottom: 100px !important;">
                <div class="card-body">
                    <form id="transfer_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label for="invoice_no">{{__('file.Invoice No')}}</label>
                                <input type="text" class="form-control bg-primary text-white" name="invoice_no" value="{{$invoiceNo}}"/>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="transfer_date">{{__('file.Transfer Date')}}</label>
                                <input type="date" class="form-control" id="transfer_date" name="transfer_date" value="{{date("Y-m-d")}}"/>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="transfer_warehouse_id">{{__('file.Transfer Company')}}</label>
                                <select class="form-control transfer_warehouse_id" id="transfer_warehouse_id" name="transfer_warehouse_id">
                                    <option value="">{{__('file.Please Select')}}</option>
                                    @foreach($warehouses as $value)
                                        <option value = "{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="receive_warehouse_id">{{__('file.Receive Company')}}</label>
                                <select class="form-control" id="receive_warehouse_id" name="receive_warehouse_id">
                                    <option value="">{{__('file.Please Select')}}</option>
                                    @foreach($warehouses as $value)
                                        <option value = "{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 pb-5">
                                <table class="table table-bordered" id="stock-transfer-table">
                                    <thead class="bg-primary">
                                    <tr class="text-center">
                                        <th width="20%">{{'Name'}}</th>
                                        <th width="10%">{{'Unit'}}</th>
                                        <th width="20%">{{'Stock Scale'}}</th>
                                        <th width="10%">{{'Stock Qty'}}</th>
                                        <th width="20%">{{'Receive Scale'}}</th>
                                        <th width="20%">{{'Receive Qty'}}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row pt-5">
                            <div class="form-group col-md-12 text-center px-0">
                                <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="transferData()"><i class="fas fa-save"></i>{{'Submit'}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        let i = 1;
        function _(x){
            return document.getElementById(x);
        }
        $(document).on('change','.transfer_warehouse_id',function(){
            let html;
            let warehouseId  = $(this).find('option:selected').val();
            $.ajax({
                url     : "{{url('stock-transfer/warehouse-product')}}/" + warehouseId,
                type    : 'GET',
                success : function(data) {
                    $('#stock-transfer-table tbody').empty();
                    if (data != '') {
                        $(data).each(function (index, item) {
                            html = `<tr class = "text-center">
                                       <td><input type="text" class="form-control bg-primary text-white" id="transfer_` + i + `_product_name" value="` + item.productName + `" readonly/><input type="hidden" id="transfer_` + i + `_product_id" name="transfer[` + i + `][product_id]" value = "` + item.productId + `"/></td>
                                       <td><input type="text" class="form-control bg-primary text-white" id="transfer_` + i + `_unit_name" value="` + item.unitName + `" readonly/></td>
                                       <td><input type="text" class="form-control bg-primary text-white" id="transfer_` + i + `_stock_scale" value="` + item.scale + `" readonly/></td>
                                       <td><input type="text" class="form-control bg-primary text-white" id="transfer_` + i + `_stock_qty" value="` + item.qty + `" readonly/></td>
                                       <td><input type="text" class="form-control scale" id="transfer_` + i + `_receive_scale" data-stock_scale="transfer_` + i + `_stock_scale" name="transfer[` + i + `][scale]"/></td>
                                       <td><input type="text" class="form-control qty" id="transfer_` + i + `_receive_qty" data-stock_qty="transfer_` + i + `_stock_qty" name="transfer[` + i + `][qty]"/></td>
                                    </tr>`
                            $('#stock-transfer-table tbody').append(html);
                            i++;
                        });
                    }else{
                        notification('error','Product Not Available');
                    }
                }
            });
        });
        $(document).on('input','.scale',function(){
            if($(this).val() > parseFloat(_($(this).data('stock_scale')).value)){
                $(this).val('');
                notification('error','Transfer Quantity Can\'t Be Greater Then Stock Scale');
                return;
            }
        });
        $(document).on('input','.qty',function(){
            if($(this).val() > parseFloat(_($(this).data('stock_qty')).value)){
                $(this).val('');
                notification('error','Transfer Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
        });
        function transferData(){
            let form     = _('transfer_form');
            let formData = new FormData(form);
            let url      = "{{route('stock.transfer.store')}}";
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
                    $('#transfer_form').find('.is-invalid').removeClass('is-invalid');
                    $('#transfer_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#transfer_form input#' + key).addClass('is-invalid');
                            $('#transfer_form textarea#' + key).addClass('is-invalid');
                            $('#transfer_form select#' + key).parent().addClass('is-invalid');
                            $('#transfer_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('stock.transfer') }}"); }
                    }
                },
                error       : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
