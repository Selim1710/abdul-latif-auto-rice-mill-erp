@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    <div class="card-toolbar"><a href="{{ route('purchase') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form action="" id="purchase_return_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type = "hidden" id = "update_id" name="purchase_id" value="{{$purchase->id}}"/>
                            <div class="row">
                                <div class="form-group col-md-4 required">
                                    <label for="invoice_no">{{__('file.Invoice No')}}.</label>
                                    <input type="text" class="form-control bg-primary text-center" id="invoice_no" name="invoice_no" value="{{$invoiceNo}}" readonly/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="purchase_date">{{__('file.Purchase Date')}}</label>
                                    <input type="date" class="form-control date bg-primary text-center" id="purchase_date" name="purchase_date" value="{{$purchase->purchase_date}}" readonly/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="party_type">{{__('file.Party Type')}}</label>
                                    <input type="text" class="form-control bg-primary text-center" id="party_type" name="party_type" value="{{PARTY_TYPE_VALUE[$purchase->party_type]}}"/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="party">{{__('file.Party')}}</label>
                                    <input type="text" class="form-control bg-primary text-center" id="party" name="party" value="{{$purchase->party_type == 1 ? $purchase->party->name : $purchase->party_name}}"/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="return_date">{{__('file.Return Date')}}</label>
                                    <input type="date" class="form-control" name="return_date" value="{{date('Y-m-d')}}"/>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="">
                                            <table class="table table-bordered quarterSelectTable" id="saleTable">
                                                <thead class="bg-primary text-center">
                                                <th width="15%" >{{__('file.Warehouse')}}</th>
                                                <th width="20%" >{{__('file.Product')}}</th>
                                                <th width="5%"  >{{__('file.Unit')}}</th>
                                                <th width="10%" >{{__('file.Price')}}</th>
                                                <th width="10%" >{{__('file.Received Qty')}}</th>
                                                <th width="10%" >{{__('file.Returned Qty')}}</th>
                                                <th width="10%" >{{__('file.Return Qty')}}</th>
                                                <th width="10%" >{{__('file.Total Price')}}</th>
                                                </thead>
                                                <tbody>
                                                @foreach($purchase->purchaseProductList as $key => $item)
                                                    <tr class="text-center">
                                                        <input type="hidden" name="return[{{$key}}][id]" value="{{$item->id}}"/>
                                                        <td>
                                                            <select class="form-control bg-primary text-center" id="return_{{$key}}_warehouse_id" name="return[{{$key}}][warehouse_id]">
                                                                <option value="{{$item->warehouse->id}}">{{$item->warehouse->name}}</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control bg-primary text-center" id="return_{{$key}}_product_id" name="return[{{$key}}][product_id]">
                                                                <option value="{{$item->product->id}}">{{$item->product->product_name}}</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control bg-primary text-center" id="return_{{$key}}_unit_name" name="return[{{$key}}][unit_name]" value="{{$item->product->unit->unit_name.'('.$item->product->unit->unit_code.')'}}" readonly/></td>
                                                        <td><input type="text" class="form-control price bg-primary text-center" id="return_{{$key}}_price" name="return[{{$key}}][price]" value="{{$item->sub_total / $item->rec_qty}}" readonly/></td>
                                                        <input type="hidden" name="return[{{$key}}][scale]" value="{{$item->scale}}"/>
                                                        <input type="hidden" name="return[{{$key}}][rec_qty]" value="{{$item->rec_qty}}"/>
                                                        <td><input type="text" class="form-control bg-primary text-center" id="return_{{$key}}_received_qty" value="{{$item->receive_qty}}" readonly/></td>
                                                        <td><input type="text" class="form-control bg-primary text-center" id="return_{{$key}}_returned_qty" value="{{$item->return_qty}}" readonly/></td>
                                                        <td><input type="text" class="form-control returnQty text-center" id="return_{{$key}}_return_qty" name="return[{{$key}}][return_qty]" data-price = "return_{{$key}}_price" data-received_qty = "return_{{$key}}_received_qty" data-returned_qty = "return_{{$key}}_returned_qty" data-sub_total = "return_{{$key}}_sub_total"/></td>
                                                        <td><input type="text" class="form-control subTotal bg-primary text-center" id="return_{{$key}}_sub_total" name="return[{{$key}}][sub_total]" readonly/></td>
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
                                            <td><input type="text" class="form-control text-center" id="total_return_qty" name="total_return_qty" readonly/></td>
                                        </tr>
                                        <tr>
                                            <td><button class="btn btn-primary btn-block">{{__('file.Return Price')}}</button></td>
                                            <td><input type="text" class="form-control text-center" id="total_return_sub_total" name="total_return_sub_total" readonly/></td>
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
        $(document).on('input','.returnQty',function(){
            let price          = _($(this).data('price')).value;
            let receivedQty    = _($(this).data('received_qty')).value;
            let returnedQty    = _($(this).data('returned_qty')).value;
            if(parseInt(receivedQty) >=  + returnedQty + +$(this).val()){
                _($(this).data('sub_total')).value = parseFloat(price) * $(this).val();
            }else{
                $(this).val('');
                _($(this).data('sub_total')).value = '';
                notification('error','Quantity Can\'t Be Greater Then Received Quantity');
            }
            calculation();
        });
        function calculation(){
            let subTotal      = 0;
            let returnQty     = 0;
            $('.subTotal').each(function(){
                if($(this).val() == ''){
                    subTotal += + 0;
                }else{
                    subTotal += + $(this).val();
                }
            });
            $('.returnQty').each(function(){
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
            let form     = document.getElementById('purchase_return_form');
            let formData = new FormData(form);
            let url      = "{{route('purchase.return.store')}}";
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
                    $('#purchase_return_form').find('.is-invalid').removeClass('is-invalid');
                    $('#purchase_return_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#purchase_return_form input#' + key).addClass('is-invalid');
                            $('#purchase_return_form textarea#' + key).addClass('is-invalid');
                            $('#purchase_return_form select#' + key).parent().addClass('is-invalid');
                            $('#purchase_return_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('purchase') }}"); }
                    }
                },
                error        : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush


