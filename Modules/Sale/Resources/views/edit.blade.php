@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    @if(permission('sale-access'))
                        <div class="card-toolbar"><a href="{{ route('sale') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form action="" id="sale_store_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="update_id" value="{{$edit->id}}"/>
                                <div class="form-group col-md-3 required">
                                    <label for="memo_no">{{__('file.Invoice No')}}.</label>
                                    <input type="text" class="form-control bg-primary text-white text-center" id="invoice_no" name="invoice_no" value="{{$edit->invoice_no}}" readonly/>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="sale_date">{{__('file.Sale Date')}}</label>
                                    <input type="date" class="form-control date" id="sale_date" name="sale_date" value="{{$edit->sale_date}}"/>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="sale_status">{{__('file.Sale Status')}}</label>
                                    <select class="form-control selectpicker" id="sale_status" name="sale_status" data-live-search = "true">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach(SALE_STATUS_VALUE as $key => $value)
                                            <option value="{{$key}}" @if($key == $edit->sale_status) selected="selected" @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="document">{{__('file.Attach Document')}}</label>
                                    <input type="file" class="form-control" name="document" id="document">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="sale_type">{{__('file.Sale Type')}}</label>
                                    <select class="form-control bg-primary text-white" id="sale_type" disabled>
                                        <option value = "">{{__('file.Please Select')}}</option>
                                        @foreach(SALE_TYPE_VALUE as $key => $value)
                                            <option value="{{$key}}" @if($key == $edit->sale_type) selected="selected" @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="sale_type_hidden" name="sale_type" value="{{$edit->sale_type}}"/>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="party_type">{{__('file.Party Type')}}</label>
                                    <select class="form-control party_type selectpicker" id="party_type" name="party_type" onchange="partyType()">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach (PARTY_TYPE_VALUE as $key => $value)
                                            <option value="{{ $key }}" @if($key == $edit->party_type) selected="selected" @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6" id="party_id_show">
                                    <label for="party_id">{{__('file.Party')}}</label>
                                    <select class="form-control party_id selectpicker" id="party_id" name="party_id" data-live-search = "true" onchange="partyDue()">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach($parties as $party)
                                            <option value="{{$party->id}}" @if($party->id == $edit->party_id) selected="selected" @endif>{{$party->name.'('.$party->address.')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6" id="party_name_show">
                                    <label for="party_name">{{__('file.Party Name')}}</label>
                                    <input type="text" class="form-control" id="party_name" name="party_name" value="{{$edit->party_name}}"/>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="payment_status">{{__('file.Payment Status')}}</label>
                                    <select class="form-control payment_status selectpicker" id="payment_status" name="payment_status" onchange="paymentStatus()">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach (PAYMENT_STATUS as $key => $value)
                                            <option value="{{ $key }}" @if($key == $edit->payment_status) selected="selected" @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_method">{{__('file.Payment Method')}}</label>
                                    <select class="form-control payment_method" id="payment_method" name="payment_method" onchange="paymentMethod()">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach (SALE_PAYMENT_METHOD as $key => $value)
                                            <option value="{{ $key }}" @if($key == $edit->payment_method) selected="selected" @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="account_id">{{__('file.Account')}}</label>
                                    <select class="form-control" id="account_id" name="account_id">
                                        <option value="">{{__('file.Please Select')}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="discount">{{__('file.Discount')}}</label>
                                    <input type="text" class="form-control text-center" name="discount" id="discount" value="{{$edit->discount}}">
                                </div>
                                <div class="form-group col-md-3 required">
                                    <div id="previous_due_status"></div>
                                    <label for="previous_due">{{__('file.Previous Due')}}</label>
                                    <input type="text" class="form-control bg-primary text-white text-center" name="previous_due" id="previous_due" readonly>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="net_total">{{__('file.Net Total')}}</label>
                                    <input type="text" class="form-control bg-primary text-white text-center" name="net_total" id="net_total" readonly>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="paid_amount">{{__('file.Paid Amount')}}</label>
                                    <input type="text" class="form-control text-center" name="paid_amount" id="paid_amount" value="{{$edit->paid_amount}}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="due_amount">{{__('file.Due Amount')}}</label>
                                    <input type="text" class="form-control bg-primary text-white text-center" name="due_amount" id="due_amount" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="transport_name">{{__('file.Transport Name')}}</label>
                                    <input type="text" class="form-control" name="transport_name" id="transport_name" value="{{$edit->transport_name}}">
                                </div>
                                <div class="col-md-12">
                                    <hr style="border-top: 5px dotted cadetblue;"/>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="saleTableAppend" class="table-responsive">
                                            @foreach($edit->saleProductList as $key => $value)
                                            <table class="table">
                                                <tbody>
                                                <tr class="text-center" @if($key != 0) style="border-top: 2px solid cadetblue;" @endif>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Company')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Category')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Product')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Unit')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Available Qty')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Qty')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Scale')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Sel Qty')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Action')}}</button></th>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>
                                                        <select class="form-control selectpicker text-center" id="sale_{{$key}}_warehouse_id" name="sale[{{$key}}][warehouse_id]" data-live-search = "true">
                                                            <option value="{{$value->warehouse_id}}">{{$value->warehouse->name}}</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control selectpicker category text-center" id="sale_{{$key}}_category_id" data-warehouse_id="sale_{{$key}}_warehouse_id" data-product_id="sale_{{$key}}_product_id" data-live-search = "true">
                                                            <option value="{{$value->product->category_id}}">{{$value->product->category->category_name}}</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control selectpicker product text-center" id="sale_{{$key}}_product_id" name="sale[{{$key}}][product_id]" data-warehouse_id="sale_{{$key}}_warehouse_id" data-unit_show="sale_{{$key}}_unit_show" data-unit_id="sale_{{$key}}_unit_id" data-available_qty="sale_{{$key}}_available_qty" data-price="sale_{{$key}}_price" data-live-search = "true">
                                                            <option value="{{$value->product_id}}">{{$value->product->product_name}}</option>
                                                        </select>
                                                    </td>
                                                    <td><input class="form-control bg-primary text-white text-center" id="sale_{{$key}}_unit_show" value="{{$value->product->unit->unit_name.'('.$value->product->unit->unit_code.')'}}" readonly/><input type="hidden" id="sale_{{$key}}_unit_id" value="{{$value->product->unit->unit_name}}"/></td>
                                                    <td><input class="form-control bg-primary available_qty text-center text-white" id="sale_{{$key}}_available_qty" value="{{$value->availableQty($value->warehouse_id,$value->product_id)->qty ?? 0}}" readonly/></td>
                                                    <td><input class="form-control qty text-center" id="sale_{{$key}}_qty" name="sale[{{$key}}][qty]" value="{{$value->qty}}" data-product_id="sale_{{$key}}_product_id" data-unit_id="sale_{{$key}}_unit_id" data-available_qty="sale_{{$key}}_available_qty" data-scale="sale_{{$key}}_scale" data-price="sale_{{$key}}_price" data-sub_total="sale_{{$key}}_sub_total"/></td>
                                                    <td><input class="form-control scale text-center" id="sale_{{$key}}_scale" name="sale[{{$key}}][scale]" value="{{$value->scale}}" data-product_id="sale_{{$key}}_product_id" data-unit_id="sale_{{$key}}_unit_id" data-available_qty="sale_{{$key}}_available_qty" data-qty="sale_{{$key}}_qty" data-price="sale_{{$key}}_price" data-sub_total="sale_{{$key}}_sub_total"/> </td>
                                                    <td><input class="form-control selQty text-center" id="sale_{{$key}}_sel_qty" name="sale[{{$key}}][sel_qty]" value="{{$value->sel_qty}}" data-available_qty="sale_{{$key}}_available_qty"/> </td>
                                                    <th  rowspan="3">
                                                        <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/>
                                                        <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button>
                                                    </th>
                                                </tr>
                                                <tr class="text-center">
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Price')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Sub Total')}}</button></th>
                                                    <th colspan="6"><button type = "button" class="btn btn-primary btn-block">{{__('file.Note')}}</button></th>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-control price text-center" id="sale_{{$key}}_price" name="sale[{{$key}}][price]" value="{{$value->price}}" data-product_id="sale_{{$key}}_product_id" data-qty="sale_{{$key}}_qty" data-sub_total="sale_{{$key}}_sub_total"/> </td>
                                                    <td><input class="form-control bg-primary text-white sub_total text-center" id="sale_{{$key}}_sub_total" name="sale[{{$key}}][sub_total]" value="{{$value->sub_total}}" readonly/> </td>
                                                    <td colspan="6"><input class="form-control text-center" id="sale_{{$key}}_note" name="sale[{{$key}}][note]" value="{{$value->note}}"/> </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <table class="table">
                                        <tr>
                                            <td><button type="button" class="btn btn-primary btn-block">{{__('file.Total Quantity')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-white text-center" id="total_sale_qty" name="total_sale_qty" value="{{$edit->total_sale_qty}}" readonly/></td>
                                        </tr>
                                        <tr>
                                            <td><button type="button" class="btn btn-primary btn-block">{{__('file.Total Price')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-white text-center" id="total_sale_sub_total" name="total_sale_sub_total" value="{{$edit->total_sale_sub_total}}" readonly/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group col-md-12 text-center pt-5">
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
    <script>
        partyType();
        partyDue();
        paymentMethod();
        paymentStatus();
        calculation();
        let i = {{count($edit->saleProductList)}} + 1;
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
        $(document).on('change','.category',function(){
            let html;
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let categoryId  = $(this).find(":selected").val();
            let productId   = $(this).data('product_id');
            $('#'+ productId +'').empty();
            $('.selectpicker').selectpicker('refresh');
            if( warehouseId != '' && categoryId != ''){
                $.ajax({
                    url     : "{{url('sale-category-product')}}/" + categoryId,
                    method  : 'GET',
                    success : function(data){
                        if(data != ''){
                            html = `<option value="">Select Please</option>`;
                            $.each(data,function(key,value){ html += '<option value="'+ value.id +'">'+ value.product_name +'</option>';});
                            $('#'+ productId +'').append(html);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                });
            }else{
                notification('error','Warehouse Or Category Not Selected');
            }
        });
        $(document).on('change','.product',function(){
            let saleType    = $('#sale_type').find(":selected").val();
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let productId   = $(this).find(":selected").val();
            let unitId      = $(this).data('unit_id');
            let unitShow    = $(this).data('unit_show');
            let availableQty= $(this).data('available_qty');
            let price       = $(this).data('price');
            if(saleType != '' && productId != ''){
                $.ajax({
                    url     : "{{url('sale-product-details')}}/" + warehouseId + "/" + productId,
                    method  : 'GET',
                    success : function(data){
                        if(data){
                            $('#'+ unitId +'').val(data.unitId);
                            $('#'+ unitShow +'').val(data.unitShow);
                            $('#'+ availableQty +'').val(data.availableQty);
                            $('#'+ price +'').val(data.salePrice);
                        }
                    }
                });
            }else{
                notification('error','Sale Type Or Product Not Selected')
            }
        });
        $(document).on('input','.qty',function(){
            let saleType     = $('#sale_type').find(":selected").val();
            let productId    = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let availableQty = $(this).data('available_qty');
            let unitId       = $(this).data('unit_id');
            let scale        = $(this).data('scale');
            let price        = $(this).data('price');
            let subTotal     = $(this).data('sub_total');
            if( _(price).value == '' || productId == '' ){
                $(this).val('');
                _(scale).value    = '';
                _(subTotal).value = '';
                notification('error','Price Or Product Not Selected');
                return;
            }
            if(saleType == 1 && parseFloat($(this).val()) > parseFloat(_(availableQty).value)){
                $(this).val('');
                _(scale).value    = '';
                _(subTotal).value = '';
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            _(scale).value     = $(this).val() * _(unitId).value;
            _(subTotal).value  = $(this).val() * _(price).value;
            calculation();
        });
        $(document).on('input','.scale',function(){
            let saleType     = $('#sale_type').find(":selected").val();
            let productId    = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let availableQty = $(this).data('available_qty');
            let unitId       = $(this).data('unit_id');
            let qty          = $(this).data('qty');
            let price        = $(this).data('price');
            let subTotal     = $(this).data('sub_total');
            if( _(price).value == '' || productId == '' ){
                $(this).val('');
                _(qty).value      = '';
                _(subTotal).value = '';
                notification('error','Price Or Product Not Selected');
                return;
            }
            _(qty).value       = $(this).val() / _(unitId).value;
            if(saleType == 1 && parseFloat(_(qty).value) > parseFloat(_(availableQty).value)){
                $(this).val('');
                _(qty).value      = '';
                _(subTotal).value = '';
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            _(subTotal).value  = $(this).val() / _(unitId).value * _(price).value;
            calculation();
        });
        $(document).on('input','.selQty',function(){
            let saleType     = $('#sale_type').find(":selected").val();
            let availableQty = $(this).data('available_qty');
            if(saleType == 1 && parseFloat($(this).val()) > parseFloat(_(availableQty).value)){
                $(this).val('');
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            calculation();
        });
        $(document).on('input','.price',function(){
            let productId = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let qty       = $(this).data('qty');
            let subTotal  = $(this).data('sub_total');
            if(productId != ''){
                $('#' + subTotal + '').val($(this).val() * $('#' + qty + '').val());
            }else{
                $(this).val('');
                notification('error','{{__("file.Please Select Product")}}');
            }
            calculation();
        });
        $(document).on('click','.addRaw',function(){
            let html;
            html = `<table class="table">
                       <tbody>
                          <tr class="text-center" style="border-top: 2px solid cadetblue;">
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Company')}}</button></th>
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Category')}}</button></th>
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Product')}}</button></th>
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Unit')}}</button></th>
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Available Qty')}}</button></th>
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Qty')}}</button></th>
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Scale')}}</button></th>
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Sel Qty')}}</button></th>
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Action')}}</button></th>
                          </tr>
                          <tr class="text-center">
                             <td>
                             <select class="form-control selectpicker text-center" id="sale_`+ i +`_warehouse_id" name="sale[`+ i +`][warehouse_id]" data-live-search = "true">
                             <option value="">{{__('Please Select')}}</option>
                             @foreach($warehouses as $warehouse)
                             <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                             @endforeach
                             </select>
                             </td>
                             <td>
                             <select class="form-control selectpicker category text-center" id="sale_`+ i +`_category_id" data-warehouse_id="sale_`+ i +`_warehouse_id" data-product_id="sale_`+ i +`_product_id" data-live-search = "true">
                             <option value="">{{__('Please Select')}}</option>
                             @foreach($categories as $category)
                             <option value="{{$category->id}}">{{$category->category_name}}</option>
                             @endforeach
                             </select>
                             </td>
                             <td><select class="form-control selectpicker product text-center" id="sale_`+ i +`_product_id" name="sale[`+ i +`][product_id]" data-warehouse_id="sale_`+ i +`_warehouse_id" data-unit_show="sale_`+ i +`_unit_show" data-unit_id="sale_`+ i +`_unit_id" data-available_qty="sale_`+ i +`_available_qty" data-price="sale_`+ i +`_price" data-live-search = "true"></select></td>
                             <td><input class="form-control bg-primary text-white text-center" id="sale_`+ i +`_unit_show" readonly/><input type="hidden" id="sale_`+ i +`_unit_id"/></td>
                             <td><input class="form-control bg-primary available_qty text-center text-white" id="sale_`+ i +`_available_qty" readonly/></td>
                             <td><input class="form-control qty text-center" id="sale_`+ i +`_qty" name="sale[`+ i +`][qty]" data-product_id="sale_`+ i +`_product_id" data-unit_id="sale_`+ i +`_unit_id" data-available_qty="sale_`+ i +`_available_qty" data-scale="sale_`+ i +`_scale" data-price="sale_`+ i +`_price" data-sub_total="sale_`+ i +`_sub_total"/></td>
                             <td><input class="form-control scale text-center" id="sale_`+ i +`_scale" name="sale[`+ i +`][scale]" data-product_id="sale_`+ i +`_product_id" data-unit_id="sale_`+ i +`_unit_id" data-available_qty="sale_`+ i +`_available_qty" data-qty="sale_`+ i +`_qty" data-price="sale_`+ i +`_price" data-sub_total="sale_`+ i +`_sub_total"/> </td>
                             <td><input class="form-control selQty text-center" id="sale_`+ i +`_sel_qty" name="sale[`+ i +`][sel_qty]" data-available_qty="sale_`+ i +`_available_qty"/> </td>
                             <th  rowspan="3">
                             <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/>
                             <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button>
                             </th>
                          </tr>
                          <tr class="text-center">
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Price')}}</button></th>
                             <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Sub Total')}}</button></th>
                             <th colspan="6"><button type = "button" class="btn btn-primary btn-block">{{__('file.Note')}}</button></th>
                          </tr>
                          <tr>
                             <td><input class="form-control price text-center" id="sale_`+ i +`_price" name="sale[`+ i +`][price]" data-product_id="sale_`+ i +`_product_id" data-qty="sale_`+ i +`_qty" data-sub_total="sale_`+ i +`_sub_total"/> </td>
                             <td><input class="form-control bg-primary text-white sub_total text-center" id="sale_`+ i +`_sub_total" name="sale[`+ i +`][sub_total]" readonly/> </td>
                             <td colspan="6"><input class="form-control text-center" id="sale_`+ i +`_note" name="sale[`+ i +`][note]"/> </td>
                          </tr>
                       </tbody>
                  </table>`;
            $('#saleTableAppend').append(html);
            $('.selectpicker').selectpicker('refresh');
            i++;
        });
        $(document).on('click','.deleteRaw',function(){
            $(this).parent().parent().parent().remove();
            calculation();
        });
        function partyDue(){
            let partyId = $('#party_id').find('option:selected').val();
            if(partyId != ''){
                $.ajax({
                    url    : "{{url('party/due')}}/" + partyId,
                    type     : "GET",
                    dataType : "JSON",
                    success  : function(data){
                        if(data > 0){
                            _('previous_due').value            = Math.abs(data);
                            _('previous_due_status').innerHTML = '<span class = "text-success">{{__('file.Your Receivable Old Outstanding')}}</span>';
                        }else{
                            _('previous_due').value            = -Math.abs(data);
                            _('previous_due_status').innerHTML = '<span class = "text-danger">{{__('file.Your Payable Old Outstanding')}}</span>';
                        }
                        calculation();
                    }
                })
            }else{
                notification('error','Party Not Selected');
            }
            calculation();
        }
        function paymentStatus(){
            let paymentStatus = $('#payment_status').find('option:selected').val();
            if(paymentStatus == 1){
                $('#paid_amount').addClass( 'bg-primary text-white' );
                $('#paid_amount').prop('readonly', true);
                $('#payment_method').removeClass( 'bg-primary text-white' );
                $('#payment_method').prop( "disabled", false );
                $('#account_id').removeClass( 'bg-primary text-white' );
                $('#account_id').prop( "disabled", false );
                _('paid_amount').value = _('net_total').value;
                calculation();
            }else if(paymentStatus == 3){
                $('#paid_amount').addClass( 'bg-primary text-white' );
                $('#paid_amount').prop('readonly', true);
                $('#payment_method').addClass( 'bg-primary text-white' );
                $('#payment_method').prop( "disabled", true );
                $('#account_id').addClass( 'bg-primary text-white' );
                $('#account_id').prop( "disabled", true );
                _('paid_amount').value = '';
                calculation();
            }else{
                $('#paid_amount').removeClass( 'bg-primary text-white' );
                $('#paid_amount').prop('readonly', false);
                $('#payment_method').removeClass( 'bg-primary text-white' );
                $('#payment_method').prop( "disabled", false );
                $('#account_id').removeClass( 'bg-primary text-white' );
                $('#account_id').prop( "disabled", false );
                _('paid_amount').value = '';
                calculation();
            }
        }
        function paymentMethod(){
            let paymentMethod =  $('#payment_method').find('option:selected').val();
            if(paymentMethod != ''){
                $.ajax({
                    url       : "{{url('account-list')}}/" + paymentMethod,
                    type      : 'GET',
                    dataType  : 'JSON',
                    success   : function(data){
                        console.log(data);
                        if(data != ''){
                            html = `<option value="">Select Please</option>`;
                            $.each(data, function(key, value) { html += '<option value="'+ value.id +'">'+ value.name +'</option>'; });
                            $('#account_id').empty();
                            $('#account_id').append(html);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                })
            }
        }
        $(document).on('input','#discount,#paid_amount',function(){
            calculation();
        })
        function calculation(){
            let qty             = 0;
            let subTotal        = 0;
            let paymentStatus   = $('#payment_status').find('option:selected').val();
            $('.selQty').each(function(){
                if($(this).val() == ''){
                    qty += + 0;
                }else{
                    qty += + $(this).val();
                }
            });
            $('.sub_total').each(function(){
                if($(this).val() == ''){
                    subTotal += + 0;
                }else{
                    subTotal += + $(this).val();
                }
            });
            if(paymentStatus == 1){
                _('total_sale_qty').value            = qty;
                _('total_sale_sub_total').value      = subTotal - _('discount').value;
                _('net_total').value                 = + _('previous_due').value + + subTotal - _('discount').value;
                _('paid_amount').value               = + _('previous_due').value + + subTotal - _('discount').value;
                _('due_amount').value                = + _('previous_due').value + + subTotal - _('discount').value - _('paid_amount').value;
            }else{
                _('total_sale_qty').value            = qty;
                _('total_sale_sub_total').value      = subTotal - _('discount').value;
                _('net_total').value                 = + _('previous_due').value + + subTotal - _('discount').value;
                _('due_amount').value                = + _('previous_due').value + + subTotal - _('discount').value - _('paid_amount').value;
            }
        }
        function updateData(){
            let form     = document.getElementById('sale_store_form');
            let formData = new FormData(form);
            let url      = "{{route('sale.update')}}";
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
                    $('#sale_store_form').find('.is-invalid').removeClass('is-invalid');
                    $('#sale_store_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#sale_store_form input#' + key).addClass('is-invalid');
                            $('#sale_store_form textarea#' + key).addClass('is-invalid');
                            $('#sale_store_form select#' + key).parent().addClass('is-invalid');
                            $('#sale_store_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('sale') }}"); }
                    }
                },
                error        : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
        
        $(document).ready(function() {
            setTimeout(function() {
                $('#account_id').val({{ $edit->account_id }}).trigger('change');
            }, 800);
        });
    </script>
@endpush
