@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    @if(permission('production-access'))
                        <div class="card-toolbar">
                            <div>
                                <span><button type="button" class="btn btn-primary btn-sm">{{$production->invoice_no}}</button></span>
                                <span><a href="{{ route("production.product",$production->id) }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form action="" id="sale_store_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="production_id" value="{{$production->id}}"/>
                                <div class="form-group col-md-3 required">
                                    <label for="memo_no">{{__('file.Invoice No')}}.</label>
                                    <input type="text" class="form-control bg-primary text-center" id="invoice_no" name="invoice_no" value="{{$invoice_no}}" readonly/>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="sale_date">{{__('file.Sale Date')}}</label>
                                    <input type="date" class="form-control date" id="sale_date" name="sale_date" value="{{date('Y-m-d')}}"/>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="document">{{__('file.Attach Document')}}</label>
                                    <input type="file" class="form-control" name="document" id="document">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="party_type">{{__('file.Party Type')}}</label>
                                    <select class="form-control party_type selectpicker" id="party_type" name="party_type" onchange="partyType()">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach (PARTY_TYPE_VALUE as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3" id="party_id_show">
                                    <label for="party_id">{{__('file.Party')}}</label>
                                    <select class="form-control party_id selectpicker" id="party_id" name="party_id" data-live-search = "true" onchange="partyDue()">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach($parties as $party)
                                            <option value="{{$party->id}}">{{$party->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3" id="party_name_show">
                                    <label for="party_name">{{__('file.Party Name')}}</label>
                                    <input type="text" class="form-control" id="party_name" name="party_name"/>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="payment_status">{{__('file.Payment Status')}}</label>
                                    <select class="form-control payment_status selectpicker" id="payment_status" name="payment_status" onchange="paymentStatus()">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach (PAYMENT_STATUS as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_method">{{__('file.Payment Method')}}</label>
                                    <select class="form-control payment_method" id="payment_method" name="payment_method" onchange="paymentMethod()">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach (SALE_PAYMENT_METHOD as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
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
                                    <input type="text" class="form-control text-center" name="discount" id="discount">
                                </div>
                                <div class="form-group col-md-3 required">
                                    <div id="previous_due_status"></div>
                                    <label for="previous_due">{{__('file.Previous Due')}}</label>
                                    <input type="text" class="form-control bg-primary text-center" name="previous_due" id="previous_due" readonly>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="net_total">{{__('file.Net Total')}}</label>
                                    <input type="text" class="form-control bg-primary text-center" name="net_total" id="net_total" readonly>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="paid_amount">{{__('file.Paid Amount')}}</label>
                                    <input type="text" class="form-control text-center" name="paid_amount" id="paid_amount" >
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="due_amount">{{__('file.Due Amount')}}</label>
                                    <input type="text" class="form-control bg-primary text-center" name="due_amount" id="due_amount" readonly>
                                </div>
                                <div class="col-md-12">
                                    <hr style="border-top: 5px dotted cadetblue;"/>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="productionSaleTableAppend">
                                            <table class="table">
                                                <tbody>
                                                <tr class="text-center">
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Category')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Product')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Unit')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Qty')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Scale')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Sel Qty')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Price')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Sub Total')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Action')}}</button></th>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>
                                                        <select class="form-control selectpicker category text-center" id="production_sale_0_category_id" data-warehouse_id="production_sale_0_warehouse_id" data-product_id="production_sale_0_product_id" data-live-search = "true">
                                                            <option value="">{{__('Please Select')}}</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control selectpicker product text-center" id="production_sale_0_product_id" name="production_sale[0][product_id]" data-category_id="production_sale_0_category_id" data-unit_show="production_sale_0_unit_show" data-unit_id="production_sale_0_unit_id" data-price="production_sale_0_price" data-live-search = "true"></select>
                                                    </td>
                                                    <td><input class="form-control bg-primary text-center" id="production_sale_0_unit_show" readonly/><input type="hidden" id="production_sale_0_unit_id"/></td>
                                                    <td><input class="form-control qty text-center" id="production_sale_0_qty" name="production_sale[0][qty]" data-product_id="production_sale_0_product_id" data-unit_id="production_sale_0_unit_id" data-scale="production_sale_0_scale" data-price="production_sale_0_price" data-sub_total="production_sale_0_sub_total"/></td>
                                                    <td><input class="form-control scale text-center" id="production_sale_0_scale" name="production_sale[0][scale]" data-product_id="production_sale_0_product_id" data-unit_id="production_sale_0_unit_id" data-qty="production_sale_0_qty" data-price="production_sale_0_price" data-sub_total="production_sale_0_sub_total"/> </td>
                                                    <td><input class="form-control selQty text-center" id="production_sale_0_sel_qty" name="production_sale[0][sel_qty]"/> </td>
                                                    <td><input class="form-control price text-center" id="production_sale_0_price" name="production_sale[0][price]" data-product_id="production_sale_0_product_id" data-qty="production_sale_0_qty" data-sub_total="production_sale_0_sub_total"/> </td>
                                                    <td><input class="form-control bg-primary sub_total text-center" id="production_sale_0_sub_total" name="production_sale[0][sub_total]" readonly/> </td>
                                                    <th  rowspan="4">
                                                        <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/>
                                                        <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button>
                                                    </th>
                                                </tr>
                                                <tr><td colspan = "8"><button type="button" class="btn btn-success btn-block">{{__('file.Packing')}}</button></td></tr>
                                                <tr>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Warehouse')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Category')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Product')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Unit')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Available Qty')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Qty')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Price')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Sub Total')}}</button></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select class="form-control selectpicker text-center" id="production_sale_0_use_warehouse_id" name="production_sale[0][use_warehouse_id]" data-live-search = "true">
                                                            <option value="">{{__('Please Select')}}</option>
                                                            @foreach($warehouses as $warehouse)
                                                                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control selectpicker useCategory text-center" id="production_sale_0_use_category_id" data-warehouse_id="production_sale_0_use_warehouse_id" data-product_id="production_sale_0_use_product_id" data-live-search = "true">
                                                            <option value="">{{__('Please Select')}}</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><select class="form-control selectpicker useProduct text-center" id="production_sale_0_use_product_id" name="production_sale[0][use_product_id]" data-warehouse_id="production_sale_0_use_warehouse_id" data-unit_show="production_sale_0_use_unit_show" data-unit_id="production_sale_0_use_unit_id" data-available_qty="production_sale_0_use_available_qty" data-price="production_sale_0_use_price" data-live-search = "true"></select></td>
                                                    <td><input class="form-control bg-primary text-center" id="production_sale_0_use_unit_show" readonly/><input type="hidden" id="production_sale_0_use_unit_id"/></td>
                                                    <td><input class="form-control bg-primary available_qty text-center" id="production_sale_0_use_available_qty" readonly/></td>
                                                    <td><input class="form-control useQty text-center" id="production_sale_0_use_qty" name="production_sale[0][use_qty]" data-product_id="production_sale_0_use_product_id" data-unit_id="production_sale_0_use_unit_id" data-available_qty="production_sale_0_use_available_qty" data-price="production_sale_0_use_price" data-sub_total="production_sale_0_use_sub_total"/></td>
                                                    <td><input class="form-control usePrice text-center" id="production_sale_0_use_price" name="production_sale[0][use_price]" data-qty="production_sale_0_use_qty" data-product_id="production_sale_0_use_product_id" data-sub_total="production_sale_0_use_sub_total"/></td>
                                                    <td><input class="form-control useSubTotal bg-primary text-center" id="production_sale_0_use_sub_total" name="production_sale[0][use_sub_total]" readonly/></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <table class="table">
                                        <tr>
                                            <td><button type="button" class="btn btn-primary btn-block">{{__('file.Total Quantity')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-center" id="total_sale_qty" name="total_sale_qty" readonly/></td>
                                        </tr>
                                        <tr>
                                            <td><button type="button" class="btn btn-primary btn-block">{{__('file.Total Scale')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-center" id="total_sale_scale" name="total_sale_scale" readonly/></td>
                                        </tr>
                                        <tr>
                                            <td><button type="button" class="btn btn-primary btn-block">{{__('file.Total Price')}}</button></td>
                                            <td><input type="text" class="form-control bg-primary text-center" id="total_sale_sub_total" name="total_sale_sub_total" readonly/></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group col-md-12 text-center pt-5">
                                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="storeData()"><i class="fas fa-save"></i>{{__('file.Save')}}</button>
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
        let i = 1;
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
            let categoryId  = $(this).find(":selected").val();
            let productId   = $(this).data('product_id');
            $('#'+ productId +'').empty();
            $('.selectpicker').selectpicker('refresh');
            if( categoryId != '' ){
                $.ajax({
                    url     : "{{url('category-product')}}/" + categoryId,
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
            let categoryId  = $('#' + $(this).data('category_id') + '').find(":selected").val();
            let productId   = $(this).find(":selected").val();
            let unitId      = $(this).data('unit_id');
            let unitShow    = $(this).data('unit_show');
            let price       = $(this).data('price');
            if(categoryId != '' && productId != ''){
                $.ajax({
                    url     : "{{url('product-details')}}/" + productId,
                    method  : 'GET',
                    success : function(data){
                        if(data){
                            $('#'+ unitId +'').val(data.unitId);
                            $('#'+ unitShow +'').val(data.unitShow);
                            $('#'+ price +'').val(data.salePrice);
                        }
                    }
                });
            }else{
                notification('error','Category Or Product Not Selected')
            }
        });
        $(document).on('change','.useCategory',function(){
            let html;
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let categoryId  = $(this).find(":selected").val();
            let productId   = $(this).data('product_id');
            if( warehouseId != '' && categoryId != ''){
                $.ajax({
                    url     : "{{url('category-product')}}/" + categoryId,
                    method  : 'GET',
                    success : function(data){
                        if(data != ''){
                            html = `<option value="">Select Please</option>`;
                            $.each(data,function(key,value){ html += '<option value="'+ value.id +'">'+ value.product_name +'</option>';});
                            $('#'+ productId +'').empty();
                            $('#'+ productId +'').append(html);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                });
            }else{
                notification('error','Warehouse Or Category Not Selected');
            }
        });
        $(document).on('change','.useProduct',function(){
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let productId   = $(this).find(":selected").val();
            let price       = $(this).data('price');
            let unitId      = $(this).data('unit_id');
            let unitShow    = $(this).data('unit_show');
            let availableQty= $(this).data('available_qty');
            if(productId != ''){
                $.ajax({
                    url     : "{{url('warehouse-product')}}/" + warehouseId + "/" + productId,
                    method  : 'GET',
                    success : function(data){
                        if(data){
                            $('#'+ price +'').val(data.purchasePrice);
                            $('#'+ unitId +'').val(data.unitId);
                            $('#'+ unitShow +'').val(data.unitShow);
                            $('#'+ availableQty +'').val(data.availableQty);
                        }
                    }
                });
            }else{
                notification('error','Product Not Selected')
            }
        });
        $(document).on('input','.qty',function(){
            let productId    = $('#' + $(this).data('product_id') + '').find(":selected").val();
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
        $(document).on('input','.useQty',function(){
            let price        = $(this).data('price');
            let subTotal     = $(this).data('sub_total');
            let productId    = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let availableQty = $(this).data('available_qty');
            if( _(price).value == '' || productId == '' ){
                $(this).val('');
                notification('error','Price Or Product Not Selected');
                return;
            }
            if( parseFloat($(this).val()) > parseFloat(_(availableQty).value)){
                $(this).val('');
                notification('error','Product Not Select 0r Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            _(subTotal).value  = $(this).val() * _(price).value;
            calculation();
        });
        $(document).on('input','.usePrice',function(){
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
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Category')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Product')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Unit')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Qty')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Scale')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Sel Qty')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Price')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Sub Total')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Action')}}</button></th>
                          </tr>
                          <tr class="text-center">
                              <td>
                              <select class="form-control selectpicker category text-center" id="production_sale_`+ i +`_category_id" data-warehouse_id="production_sale_`+ i +`_warehouse_id" data-product_id="production_sale_`+ i +`_product_id" data-live-search = "true">
                              <option value="">{{__('Please Select')}}</option>
                              @foreach($categories as $category)
                              <option value="{{$category->id}}">{{$category->category_name}}</option>
                              @endforeach
                              </select>
                              </td>
                              <td>
                              <select class="form-control selectpicker product text-center" id="production_sale_`+ i +`_product_id" name="production_sale[`+ i +`][product_id]" data-category_id="production_sale_`+ i +`_category_id" data-unit_show="production_sale_`+ i +`_unit_show" data-unit_id="production_sale_`+ i +`_unit_id" data-price="production_sale_`+ i +`_price" data-live-search = "true"></select>
                              </td>
                              <td><input class="form-control bg-primary text-center" id="production_sale_`+ i +`_unit_show" readonly/><input type="hidden" id="production_sale_`+ i +`_unit_id"/></td>
                              <td><input class="form-control qty text-center" id="production_sale_`+ i +`_qty" name="production_sale[`+ i +`][qty]" data-product_id="production_sale_`+ i +`_product_id" data-unit_id="production_sale_`+ i +`_unit_id" data-scale="production_sale_`+ i +`_scale" data-price="production_sale_`+ i +`_price" data-sub_total="production_sale_`+ i +`_sub_total"/></td>
                              <td><input class="form-control scale text-center" id="production_sale_`+ i +`_scale" name="production_sale[`+ i +`][scale]" data-product_id="production_sale_`+ i +`_product_id" data-unit_id="production_sale_`+ i +`_unit_id" data-qty="production_sale_`+ i +`_qty" data-price="production_sale_`+ i +`_price" data-sub_total="production_sale_`+ i +`_sub_total"/> </td>
                              <td><input class="form-control selQty text-center" id="production_sale_`+ i +`_sel_qty" name="production_sale[`+ i +`][sel_qty]"/> </td>
                              <td><input class="form-control price text-center" id="production_sale_`+ i +`_price" name="production_sale[`+ i +`][price]" data-product_id="production_sale_`+ i +`_product_id" data-qty="production_sale_`+ i +`_qty" data-sub_total="production_sale_`+ i +`_sub_total"/> </td>
                              <td><input class="form-control bg-primary sub_total text-center" id="production_sale_`+ i +`_sub_total" name="production_sale[`+ i +`][sub_total]" readonly/> </td>
                              <th rowspan="4">
                                <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/>
                                <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button>
                              </th>
                          </tr>
                          <tr><td colspan = "8"><button type="button" class="btn btn-success btn-block">{{__('file.Packing')}}</button></td></tr>
                          <tr>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Warehouse')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Category')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Product')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Unit')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Available Qty')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Qty')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Price')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Sub Total')}}</button></td>
                          </tr>
                          <tr>
                              <td>
                              <select class="form-control selectpicker text-center" id="production_sale_`+ i +`_use_warehouse_id" name="production_sale[`+ i +`][use_warehouse_id]" data-live-search = "true">
                              <option value="">{{__('Please Select')}}</option>
                              @foreach($warehouses as $warehouse)
                              <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                              @endforeach
                              </select>
                              </td>
                              <td>
                              <select class="form-control selectpicker useCategory text-center" id="production_sale_`+ i +`_use_category_id" data-warehouse_id="production_sale_`+ i +`_use_warehouse_id" data-product_id="production_sale_`+ i +`_use_product_id" data-live-search = "true">
                              <option value="">{{__('Please Select')}}</option>
                              @foreach($categories as $category)
                              <option value="{{$category->id}}">{{$category->category_name}}</option>
                              @endforeach
                              </select>
                              </td>
                              <td><select class="form-control selectpicker useProduct text-center" id="production_sale_`+ i +`_use_product_id" name="production_sale[`+ i +`][use_product_id]" data-warehouse_id="production_sale_`+ i +`_use_warehouse_id" data-unit_show="production_sale_`+ i +`_use_unit_show" data-unit_id="production_sale_`+ i +`_use_unit_id" data-available_qty="production_sale_`+ i +`_use_available_qty" data-price="production_sale_`+ i +`_use_price" data-live-search = "true"></select></td>
                              <td><input class="form-control bg-primary text-center" id="production_sale_`+ i +`_use_unit_show" readonly/><input type="hidden" id="production_sale_`+ i +`_use_unit_id"/></td>
                              <td><input class="form-control bg-primary available_qty text-center" id="production_sale_`+ i +`_use_available_qty" readonly/></td>
                              <td><input class="form-control useQty text-center" id="production_sale_`+ i +`_use_qty" name="production_sale[`+ i +`][use_qty]" data-product_id="production_sale_`+ i +`_use_product_id" data-unit_id="production_sale_`+ i +`_use_unit_id" data-available_qty="production_sale_`+ i +`_use_available_qty" data-price="production_sale_`+ i +`_use_price" data-sub_total="production_sale_`+ i +`_use_sub_total"/></td>
                              <td><input class="form-control usePrice text-center" id="production_sale_`+ i +`_use_price" name="production_sale[`+ i +`][use_price]" data-qty="production_sale_`+ i +`_use_qty" data-product_id="production_sale_`+ i +`_use_product_id" data-sub_total="production_sale_`+ i +`_use_sub_total"/></td>
                              <td><input class="form-control useSubTotal bg-primary text-center" id="production_sale_`+ i +`_use_sub_total" name="production_sale[`+ i +`][use_sub_total]" readonly/></td>
                          </tr>
                       </tbody>
                 </table>`;
            $('#productionSaleTableAppend').append(html);
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
                $('#paid_amount').addClass( 'bg-primary' );
                $('#paid_amount').prop('readonly', true);
                $('#payment_method').removeClass( 'bg-primary' );
                $('#payment_method').prop( "disabled", false );
                $('#account_id').removeClass( 'bg-primary' );
                $('#account_id').prop( "disabled", false );
                _('paid_amount').value = _('net_total').value;
                calculation();
            }else if(paymentStatus == 3){
                $('#paid_amount').addClass( 'bg-primary' );
                $('#paid_amount').prop('readonly', true);
                $('#payment_method').addClass( 'bg-primary' );
                $('#payment_method').prop( "disabled", true );
                $('#account_id').addClass( 'bg-primary' );
                $('#account_id').prop( "disabled", true );
                _('paid_amount').value = '';
                calculation();
            }else{
                $('#paid_amount').removeClass( 'bg-primary' );
                $('#paid_amount').prop('readonly', false);
                $('#payment_method').removeClass( 'bg-primary' );
                $('#payment_method').prop( "disabled", false );
                $('#account_id').removeClass( 'bg-primary' );
                $('#account_id').prop( "disabled", false );
                _('paid_amount').value = '';
                calculation();
            }
        }
        function paymentMethod(){
            $('#account_id').empty();
            let paymentMethod =  $('#payment_method').find('option:selected').val();
            if(paymentMethod != ''){
                $.ajax({
                    url       : "{{url('account-list')}}/" + paymentMethod,
                    type      : 'GET',
                    dataType  : 'JSON',
                    success   : function(data){
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
            let scale           = 0;
            let subTotal        = 0;
            let paymentStatus   = $('#payment_status').find('option:selected').val();
            $('.selQty').each(function(){
                if($(this).val() == ''){
                    qty += + 0;
                }else{
                    qty += + $(this).val();
                }
            });
            $('.scale').each(function(){
                if($(this).val() == ''){
                    scale += + 0;
                }else{
                    scale += + $(this).val();
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
                _('total_sale_scale').value          = scale;
                _('total_sale_sub_total').value      = subTotal - _('discount').value;
                _('net_total').value                 = + _('previous_due').value + + subTotal - _('discount').value;
                _('paid_amount').value               = + _('previous_due').value + + subTotal - _('discount').value;
                _('due_amount').value                = + _('previous_due').value + + subTotal - _('discount').value - _('paid_amount').value;
            }else{
                _('total_sale_qty').value            = qty;
                _('total_sale_scale').value          = scale;
                _('total_sale_sub_total').value      = subTotal - _('discount').value;
                _('net_total').value                 = + _('previous_due').value + + subTotal - _('discount').value;
                _('due_amount').value                = + _('previous_due').value + + subTotal - _('discount').value - _('paid_amount').value;
            }
        }
        function storeData(){
            let form     = document.getElementById('sale_store_form');
            let formData = new FormData(form);
            let url      = "{{route('production.sale.store')}}";
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
                        if (data.status == 'success') { window.location.replace("{{ route('production') }}"); }
                    }
                },
                error        : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
