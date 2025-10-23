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
                        <form action="" id="purchase_receive_form" method="post" enctype="multipart/form-data">
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
                                    <label for="receive_date">{{__('file.Receive Date')}}</label>
                                    <input type="date" class="form-control text-center" name="receive_date" value="{{date('Y-m-d')}}"/>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="">
                                            <table class="table table-bordered quarterSelectTable" id="saleTable">
                                                <thead class="bg-primary text-center">
                                                <th width="15%" >{{__('file.Company')}}</th>
                                                <th width="20%" >{{__('file.Product')}}</th>
                                                <th width="5%"  >{{__('file.Unit')}}</th>
                                                <th width="10%" >{{__('file.Price')}}</th>
                                                <th width="10%" >{{__('file.Purchase Qty')}}</th>
                                                <th width="10%" >{{__('file.Received Qty')}}</th>
                                                <th width="10%" >{{__('file.Receive Qty')}}</th>
                                                <th width="10%" >{{__('file.Total Price')}}</th>
                                                </thead>
                                                <tbody>
                                                @foreach($purchase->purchaseProductList as $key => $item)
                                                    <input type="hidden" name="receive[{{$key}}][id]" value="{{$item->id}}"/>
                                                    <tr class="text-center">
                                                        <td>
                                                            <select class="form-control bg-primary text-center" id="receive_{{$key}}_warehouse_id" name="receive[{{$key}}][warehouse_id]">
                                                                <option value="{{$item->warehouse->id}}">{{$item->warehouse->name}}</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control bg-primary text-center" id="receive_{{$key}}_product_id" name="receive[{{$key}}][product_id]">
                                                                <option value="{{$item->product->id}}">{{$item->product->product_name}}</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control text-center bg-primary" id="receive_{{$key}}_unit_name" name="receive[{{$key}}][unit_name]" value="{{$item->product->unit->unit_name.'('.$item->product->unit->unit_code.')'}}" readonly/></td>
                                                        <td><input type="text" class="form-control text-center price bg-primary" id="receive_{{$key}}_price" name="receive[{{$key}}][price]" value="{{$item->sub_total / $item->rec_qty}}" readonly/></td>
                                                        <td>
                                                            <input type="hidden" name="receive[{{$key}}][scale]" value="{{$item->scale}}"/>
                                                            <input type="text" class="form-control text-center bg-primary" id="receive_{{$key}}_purchase_qty" name="receive[{{$key}}][purchase_qty]" value="{{$item->rec_qty}}" readonly/>
                                                        </td>
                                                        <td><input type="text" class="form-control text-center bg-primary" id="receive_{{$key}}_received_qty" value="{{$item->receive_qty}}" readonly/></td>
                                                        <td><input type="text" class="form-control text-center receiveQty" id="receive_{{$key}}_receive_qty" name="receive[{{$key}}][receive_qty]" data-price = "receive_{{$key}}_price" data-purchase_qty = "receive_{{$key}}_purchase_qty" data-received_qty = "receive_{{$key}}_received_qty" data-sub_total = "receive_{{$key}}_sub_total"/></td>
                                                        <td><input type="text" class="form-control text-center subTotal bg-primary" id="receive_{{$key}}_sub_total" name="receive[{{$key}}][sub_total]" readonly/></td>
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
                                            <td><button class="btn btn-primary btn-block">{{__('file.Receive Qty')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-center" id="total_receive_qty" name="total_receive_qty" readonly/> </td>
                                        </tr>
                                        <tr>
                                            <td><button class="btn btn-primary btn-block">{{__('file.Receive Total')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-center" id="total_receive_sub_total" name="total_receive_sub_total" readonly/> </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group col-md-12 text-center pt-5">
                                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="receiveData()"><i class="fas fa-save"></i>{{__('file.Receive')}}</button>
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
        $(document).on('input','.receiveQty',function(){
            let price          = _($(this).data('price')).value;
            let purchaseQty    = _($(this).data('purchase_qty')).value;
            let receivedQty    = _($(this).data('received_qty')).value;
            if(parseInt(purchaseQty) >= + receivedQty + + $(this).val()){
                _($(this).data('sub_total')).value = parseFloat(price) * $(this).val();
            }else{
                $(this).val('');
                _($(this).data('sub_total')).value = '';
                notification('error','Quantity Can\'t Be Greater Then Purchase Quantity');
            }
            calculation();
        });
        function calculation(){
            let subTotal      = 0;
            let receiveQty   = 0;
            $('.subTotal').each(function(){
                if($(this).val() == ''){
                    subTotal += + 0;
                }else{
                    subTotal += + $(this).val();
                }
            });
            $('.receiveQty').each(function(){
                if($(this).val() == ''){
                    receiveQty += + 0;
                }else{
                    receiveQty += + $(this).val();
                }
            });
            _('total_receive_qty').value                   = receiveQty;
            _('total_receive_sub_total').value             = subTotal;
        }
        function receiveData(){
            let form     = document.getElementById('purchase_receive_form');
            let formData = new FormData(form);
            let url      = "{{route('purchase.receive.store')}}";
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
                    $('#purchase_receive_form').find('.is-invalid').removeClass('is-invalid');
                    $('#purchase_receive_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#purchase_receive_form input#' + key).addClass('is-invalid');
                            $('#purchase_receive_form textarea#' + key).addClass('is-invalid');
                            $('#purchase_receive_form select#' + key).parent().addClass('is-invalid');
                            $('#purchase_receive_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
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


