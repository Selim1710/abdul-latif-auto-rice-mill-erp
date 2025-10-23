@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('sale') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a>
                    </div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form action="" id="sale_delivery_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type = "hidden" name="sale_id" value="{{$sale->id}}"/>
                            <div class="row">
                                <div class="form-group col-md-3 required">
                                    <label for="memo_no">{{__('file.Invoice No')}}.</label>
                                    <input type="text" class="form-control bg-primary text-white text-center" id="invoice_no" name="invoice_no" value="{{$invoiceNo}}" readonly/>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="sale_date">{{__('file.Sale Date')}}</label>
                                    <input type="date" class="form-control date bg-primary text-white text-center" id="sale_date" name="sale_date" value="{{$sale->sale_date}}" readonly/>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="party">{{__('file.Party')}}</label>
                                    <input type="text" class="form-control bg-primary text-white text-center" value="{{$sale->party_type == 1 ? $sale->party->name : $sale->party_name}}" readonly/>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="delivery_date">{{__('file.Delivery Date')}}</label>
                                    <input type="date" class="form-control date bg-primary text-white text-center" id="delivery_date" name="delivery_date" value="{{date('Y-m-d')}}"/>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="">
                                            <table class="table table-bordered quarterSelectTable" id="saleTable">
                                                <thead class="bg-primary text-center">
                                                <th width="15%" >{{__('file.Warehouse')}}</th>
                                                <th width="20%" >{{__('file.Product')}}</th>
                                                <th width="5%" >{{__('file.Unit')}}</th>
                                                <th width="10%" >{{__('file.Price')}}</th>
                                                <th width="10%" >{{__('file.Available Qty')}}</th>
                                                <th width="10%" >{{__('file.Sale Qty')}}</th>
                                                <th width="10%" >{{__('file.Delivered Qty')}}</th>
                                                <th width="10%" >{{__('file.Delivery Qty')}}</th>
                                                <th width="10%" >{{__('file.Total Price')}}</th>
                                                </thead>
                                                <tbody>
                                                @foreach($sale->saleProductList as $key => $item)
                                                    <tr class="text-center">
                                                        <input type="hidden" name="sale[{{$key}}][id]" value="{{$item->id}}"/>
                                                        <td>
                                                            <select class="form-control warehouseProduct bg-primary text-white" id="sale_{{$key}}_warehouse_id" name="sale[{{$key}}][warehouse_id]">
                                                                <option value="{{$item->warehouse->id}}">{{$item->warehouse->name}}</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control productDetails bg-primary text-white" id="sale_{{$key}}_product_id" name="sale[{{$key}}][product_id]">
                                                                <option value="{{$item->product->id}}">{{$item->product->product_name}}</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control bg-primary text-white" id="sale_{{$key}}_unit_name" name="sale[{{$key}}][unit_name]" value="{{$item->product->unit->unit_name.'('.$item->product->unit->unit_code.')'}}" readonly/></td>
                                                        <td><input type="text" class="form-control price bg-primary text-white" id="sale_{{$key}}_price" name="sale[{{$key}}][price]" value="{{$item->sub_total / $item->sel_qty}}" readonly/></td>
                                                        <td><input type="text" class="form-control bg-primary text-white" id="sale_{{$key}}_stock_qty" name="sale[{{$key}}][stock_qty]" value="{{$item->availableQty($item->warehouse_id,$item->product_id)->qty ?? 0}}" readonly/></td>
                                                        <input type="hidden" name="sale[{{$key}}][scale]" value="{{$item->scale}}"/>
                                                        <td><input type="text" class="form-control bg-primary text-white" id="sale_{{$key}}_qty" name="sale[{{$key}}][qty]" value="{{$item->sel_qty}}" readonly/></td>
                                                        <td><input type="text" class="form-control bg-primary text-white" id="sale_{{$key}}_delivered_qty" value="{{$item->delivery_qty}}" readonly/></td>
                                                        <td><input type="text" class="form-control delivery_qty" id="sale_{{$key}}_delivery_qty" data-price = "sale_{{$key}}_price" data-stock_qty = "sale_{{$key}}_stock_qty" data-sale_qty = "sale_{{$key}}_qty" data-delivered_qty = "sale_{{$key}}_delivered_qty" data-sub_total = "sale_{{$key}}_sub_total" name="sale[{{$key}}][delivery_qty]"/></td>
                                                        <td><input type="text" class="form-control sub_total bg-primary text-white" id="sale_{{$key}}_sub_total" name="sale[{{$key}}][sub_total]" readonly/></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                    <table class="table">
                                        <tr>
                                            <td><button class="btn btn-primary btn-block">{{__('file.Delivery Qty')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-white text-center" id="total_delivery_qty" name="total_delivery_qty" readonly/> </td>
                                        </tr>
                                        <tr>
                                            <td><button class="btn btn-primary btn-block">{{__('file.Delivery Total')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-white text-center" id="total_delivery_sub_total" name="total_delivery_sub_total" readonly/> </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group col-md-12 text-center pt-5">
                                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="deliveryData()"><i class="fas fa-save"></i>{{__('file.Delivery')}}</button>
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
        function _(x){
            return document.getElementById(x);
        }
        $(document).on('input','.delivery_qty',function(){
            let price         = parseFloat(_($(this).data('price')).value);
            let stockQty      = parseFloat(_($(this).data('stock_qty')).value);
            let saleQty       = parseFloat(_($(this).data('sale_qty')).value);
            let deliveredQty  = parseFloat(_($(this).data('delivered_qty')).value);
            let value         = $(this).val();
            if(stockQty >= + deliveredQty + + value){
                _($(this).data('sub_total')).value = price * value;
            }else{
                $(this).val('');
                _($(this).data('sub_total')).value = '';
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
            }
            if(saleQty >= + deliveredQty + + value){
                _($(this).data('sub_total')).value = price * value;
            }else{
                $(this).val('');
                _($(this).data('sub_total')).value = '';
                notification('error','Quantity Can\'t Be Greater Then Sale Quantity');
            }
            calculation();
        });
        function calculation(){
            let subTotal      = 0;
            let deliveryQty   = 0;
            $('.sub_total').each(function(){
                if($(this).val() == ''){
                    subTotal += + 0;
                }else{
                    subTotal += + $(this).val();
                }
            });
            $('.delivery_qty').each(function(){
                if($(this).val() == ''){
                    deliveryQty += + 0;
                }else{
                    deliveryQty += + $(this).val();
                }
            });
            _('total_delivery_qty').value                   = deliveryQty;
            _('total_delivery_sub_total').value             = subTotal;
        }
        function deliveryData(){
            let form     = document.getElementById('sale_delivery_form');
            let formData = new FormData(form);
            let url      = "{{route('sale.delivery.store')}}";
            $.ajax({
                url           : url,
                type          : "POST",
                data          : formData,
                dataType      : "JSON",
                contentType   : false,
                processData   : false,
                cache         : false,
                beforeSend    : function(){
                    $('#save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete      : function(){
                    $('#save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success       : function (data) {
                    $('#sale_delivery_form').find('.is-invalid').removeClass('is-invalid');
                    $('#sale_delivery_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#sale_delivery_form input#' + key).addClass('is-invalid');
                            $('#sale_delivery_form textarea#' + key).addClass('is-invalid');
                            $('#sale_delivery_form select#' + key).parent().addClass('is-invalid');
                            $('#sale_delivery_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('sale') }}"); }
                    }
                },
                error        : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush

