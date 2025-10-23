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
                        <form action="" id="sale_return_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type = "hidden" id = "update_id" name="sale_id" value="{{$sale->id}}"/>
                            <div class="row">
                                <div class="form-group col-md-4 required">
                                    <label for="memo_no">{{__('file.Invoice No')}}.</label>
                                    <input type="text" class="form-control bg-primary text-white" id="invoice_no" name="invoice_no" value="{{$invoiceNo}}" readonly/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="return_date">{{__('file.Return Date')}}</label>
                                    <input type="date" class="form-control date" id="return_date" name="return_date" value="{{date("Y-m-d")}}"/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="party">{{__('file.Party')}}</label>
                                    <input type="text" class="form-control bg-primary text-white text-center" value="{{$sale->party_type == 1 ? $sale->party->name : $sale->party_name}}" readonly/>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="">
                                            <table class="table table-bordered quarterSelectTable" id="saleTable">
                                                <thead class="bg-primary text-center">
                                                <th width="13%" >{{__('file.Warehouse')}}</th>
                                                <th width="12%" >{{__('file.Product')}}</th>
                                                <th width="5%" >{{__('file.Unit')}}</th>
                                                <th width="10%" >{{__('file.Price')}}</th>
                                                <th width="10%" >{{__('file.Delivered Qty')}}</th>
                                                <th width="10%" >{{__('file.Returned Qty')}}</th>
                                                <th width="10%" >{{__('file.Return Qty')}}</th>
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
                                                        <td><input type="text" class="form-control price bg-primary text-white" id="sale_{{$key}}_price" name="sale[{{$key}}][price]" value="{{ $item->sub_total / $item->sel_qty }}" readonly/></td>
                                                        <input type="hidden" name="sale[{{$key}}][scale]" value="{{$item->scale}}"/>
                                                        <input type="hidden" name="sale[{{$key}}][sel_qty]" value="{{$item->sel_qty}}"/>
                                                        <td><input type="text" class="form-control bg-primary text-white" id="sale_{{$key}}_delivery_qty" value="{{$item->delivery_qty}}" readonly/></td>
                                                        <td><input type="text" class="form-control bg-primary text-white" id="sale_{{$key}}_returned_qty" value="{{$item->return_qty}}" readonly/></td>
                                                        <td><input type="text" class="form-control return_qty" id="sale_{{$key}}_return_qty" data-price = "sale_{{$key}}_price" data-delivery_qty = "sale_{{$key}}_delivery_qty" data-returned_qty = "sale_{{$key}}_returned_qty" data-sub_total = "sale_{{$key}}_sub_total" name="sale[{{$key}}][return_qty]"/></td>
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
                                            <td><button class="btn btn-primary btn-block">{{__('file.Return Qty')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-white text-center" id="total_return_qty" name="total_return_qty" readonly/> </td>
                                        </tr>
                                        <tr>
                                            <td><button class="btn btn-primary btn-block">{{__('file.Return Amount')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-white text-center" id="total_return_sub_total" name="total_return_sub_total" readonly/> </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group col-md-12 text-center pt-5">
                                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="returnData()"><i class="fas fa-save"></i>{{__('file.Return')}}</button>
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
        $(document).on('input','.return_qty',function(){
            let price         = parseFloat(_($(this).data('price')).value);
            let deliveryQty   = parseFloat(_($(this).data('delivery_qty')).value);
            let returnedQty   = _($(this).data('returned_qty')).value;
            let value         = $(this).val();
            if(deliveryQty >= + returnedQty + + value){
                _($(this).data('sub_total')).value = price * value;
            }else{
                $(this).val('');
                _($(this).data('sub_total')).value = '';
                notification('error','Quantity Can\'t Be Greater Then Delivery Quantity');
            }
            calculation();
        });
        function calculation(){
            let subTotal      = 0;
            let returnQty     = 0;
            $('.sub_total').each(function(){
                if($(this).val() == ''){
                    subTotal += + 0;
                }else{
                    subTotal += + $(this).val();
                }
            });
            $('.return_qty').each(function(){
                if($(this).val() == ''){
                    returnQty += + 0;
                }else{
                    returnQty += + $(this).val();
                }
            });
            _('total_return_qty').value                   = returnQty;
            _('total_return_sub_total').value             = subTotal;
        }
        function returnData(){
            let form     = document.getElementById('sale_return_form');
            let formData = new FormData(form);
            let url      = "{{route('sale.return.store')}}";
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
                    $('#sale_return_form').find('.is-invalid').removeClass('is-invalid');
                    $('#sale_return_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#sale_return_form input#' + key).addClass('is-invalid');
                            $('#sale_return_form textarea#' + key).addClass('is-invalid');
                            $('#sale_return_form select#' + key).parent().addClass('is-invalid');
                            $('#sale_return_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
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

