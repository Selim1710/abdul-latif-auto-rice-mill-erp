@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}" />
    <link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .small-btn{
            width: 20px !important;
            height: 20px !important;
            padding: 0 !important;
        }
        .small-btn i{font-size: 10px !important;}
    </style>
@endpush
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
                        <form action="" id="production_product_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="production_id" value="{{$production->id}}"/>
                                <div class="form-group col-md-6 required">
                                    <label for="memo_no">{{__('file.Invoice No')}}.</label>
                                    <input type="text" class="form-control bg-primary text-white text-center" id="invoice_no" name="invoice_no" value="{{$invoice_no}}" readonly/>
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="sale_date">{{__('file.Date')}}</label>
                                    <input type="date" class="form-control date" id="date" name="date" value="{{date('Y-m-d')}}"/>
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
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Warehouse')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Category')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Product')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Unit')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Qty')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Scale')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Production Qty')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Price')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Sub Total')}}</button></th>
                                                    <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Action')}}</button></th>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>
                                                        <select class="form-control selectpicker text-center" id="production_product_0_warehouse_id" name="production_product[0][warehouse_id]" data-live-search = "true">
                                                            <option value="">{{__('Please Select')}}</option>
                                                            @foreach($warehouses as $warehouse)
                                                                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control selectpicker category text-center" id="production_product_0_category_id" data-warehouse_id="production_product_0_warehouse_id" data-product_id="production_product_0_product_id" data-live-search = "true">
                                                            <option value="">{{__('Please Select')}}</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control selectpicker product text-center" id="production_product_0_product_id" name="production_product[0][product_id]" data-category_id="production_product_0_category_id" data-unit_show="production_product_0_unit_show" data-unit_id="production_product_0_unit_id" data-price="production_product_0_price" data-live-search = "true"></select>
                                                    </td>
                                                    <td><input class="form-control bg-primary text-white text-center" id="production_product_0_unit_show" readonly/><input type="hidden" id="production_product_0_unit_id"/></td>
                                                    <td><input class="form-control qty text-center" id="production_product_0_qty" name="production_product[0][qty]" data-product_id="production_product_0_product_id" data-unit_id="production_product_0_unit_id" data-scale="production_product_0_scale" data-price="production_product_0_price" data-sub_total="production_product_0_sub_total"/></td>
                                                    <td><input class="form-control scale text-center" id="production_product_0_scale" name="production_product[0][scale]" data-product_id="production_product_0_product_id" data-unit_id="production_product_0_unit_id" data-qty="production_product_0_qty" data-price="production_product_0_price" data-sub_total="production_product_0_sub_total"/> </td>
                                                    <td><input class="form-control productionQty text-center" id="production_product_0_production_qty" name="production_product[0][production_qty]"/> </td>
                                                    <td><input class="form-control price text-center" id="production_product_0_price" name="production_product[0][price]" data-product_id="production_product_0_product_id" data-qty="production_product_0_qty" data-sub_total="production_product_0_sub_total"/> </td>
                                                    <td><input class="form-control bg-primary text-white sub_total text-center" id="production_product_0_sub_total" name="production_product[0][sub_total]" readonly/> </td>
                                                    <th  rowspan="4">
                                                        <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/>
                                                        <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button>
                                                    </th>
                                                </tr>
                                                <tr><td colspan = "9"><button type="button" class="btn btn-success btn-block">{{__('file.Packing')}}</button></td></tr>
                                                <tr>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Warehouse')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Category')}}</button></td>
                                                    <td colspan="2"><button type = "button" class="btn btn-primary btn-block">{{__('file.Product')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Unit')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Available Qty')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Qty')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Price')}}</button></td>
                                                    <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Sub Total')}}</button></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select class="form-control selectpicker text-center" id="production_product_0_use_warehouse_id" name="production_product[0][use_warehouse_id]" data-live-search = "true">
                                                            <option value="">{{__('Please Select')}}</option>
                                                            @foreach($warehouses as $warehouse)
                                                                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control selectpicker useCategory text-center" id="production_product_0_use_category_id" data-warehouse_id="production_product_0_use_warehouse_id" data-product_id="production_product_0_use_product_id" data-live-search = "true">
                                                            <option value="">{{__('Please Select')}}</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td colspan="2"><select class="form-control selectpicker useProduct text-center" id="production_product_0_use_product_id" name="production_product[0][use_product_id]" data-warehouse_id="production_product_0_use_warehouse_id" data-unit_show="production_product_0_use_unit_show" data-unit_id="production_product_0_use_unit_id" data-available_qty="production_product_0_use_available_qty" data-price="production_product_0_use_price" data-live-search = "true"></select></td>
                                                    <td><input class="form-control bg-primary text-white text-center" id="production_product_0_use_unit_show" readonly/><input type="hidden" id="production_product_0_use_unit_id"/></td>
                                                    <td><input class="form-control bg-primary available_qty text-center text-white" id="production_product_0_use_available_qty" readonly/></td>
                                                    <td><input class="form-control useQty text-center" id="production_product_0_use_qty" name="production_product[0][use_qty]" data-product_id="production_product_0_use_product_id" data-unit_id="production_product_0_use_unit_id" data-available_qty="production_product_0_use_available_qty" data-price="production_product_0_use_price" data-sub_total="production_product_0_use_sub_total"/></td>
                                                    <td><input class="form-control usePrice text-center" id="production_product_0_use_price" name="production_product[0][use_price]" data-qty="production_product_0_use_qty" data-product_id="production_product_0_use_product_id" data-sub_total="production_product_0_use_sub_total"/></td>
                                                    <td><input class="form-control useSubTotal bg-primary text-white text-center" id="production_product_0_use_sub_total" name="production_product[0][use_sub_total]" readonly/></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
    <script src="{{asset('js/jquery-ui.js')}}"></script>
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        let i = 1;
        function _(x){
            return document.getElementById(x);
        }
        $(document).on('change','.category',function(){
            let html;
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let categoryId  = $(this).find(":selected").val();
            let productId   = $(this).data('product_id');
            $('#'+ productId +'').empty();
            $('.selectpicker').selectpicker('refresh');
            if( warehouseId != '' && categoryId != '' ){
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
        });
        $(document).on('input','.productionQty',function(){
            let saleType     = $('#sale_type').find(":selected").val();
            let availableQty = $(this).data('available_qty');
            if(saleType == 1 && parseFloat($(this).val()) > parseFloat(_(availableQty).value)){
                $(this).val('');
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
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
        });
        $(document).on('click','.addRaw',function(){
            let html;
            html = `<table class="table">
                       <tbody>
                          <tr class="text-center" style="border-top: 2px solid cadetblue;">
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Warehouse')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Category')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Product')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Unit')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Qty')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Scale')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Production Qty')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Price')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Sub Total')}}</button></th>
                              <th><button type = "button" class="btn btn-primary btn-block">{{__('file.Action')}}</button></th>
                          </tr>
                          <tr class="text-center">
                              <td>
                              <select class="form-control selectpicker text-center" id="production_product_`+ i +`_warehouse_id" name="production_product[`+ i +`][warehouse_id]" data-live-search = "true">
                              <option value="">{{__('Please Select')}}</option>
                              @foreach($warehouses as $warehouse)
                              <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                              @endforeach
                              </select>
                              </td>
                              <td>
                              <select class="form-control selectpicker category text-center" id="production_product_`+ i +`_category_id" data-warehouse_id="production_product_`+ i +`_warehouse_id" data-product_id="production_product_`+ i +`_product_id" data-live-search = "true">
                              <option value="">{{__('Please Select')}}</option>
                              @foreach($categories as $category)
                              <option value="{{$category->id}}">{{$category->category_name}}</option>
                              @endforeach
                              </select>
                              </td>
                              <td>
                              <select class="form-control selectpicker product text-center" id="production_product_`+ i +`_product_id" name="production_product[`+ i +`][product_id]" data-category_id="production_product_`+ i +`_category_id" data-unit_show="production_product_`+ i +`_unit_show" data-unit_id="production_product_`+ i +`_unit_id" data-price="production_product_`+ i +`_price" data-live-search = "true"></select>
                              </td>
                              <td><input class="form-control bg-primary text-white text-center" id="production_product_`+ i +`_unit_show" readonly/><input type="hidden" id="production_product_`+ i +`_unit_id"/></td>
                              <td><input class="form-control qty text-center" id="production_product_`+ i +`_qty" name="production_product[`+ i +`][qty]" data-product_id="production_product_`+ i +`_product_id" data-unit_id="production_product_`+ i +`_unit_id" data-scale="production_product_`+ i +`_scale" data-price="production_product_`+ i +`_price" data-sub_total="production_product_`+ i +`_sub_total"/></td>
                              <td><input class="form-control scale text-center" id="production_product_`+ i +`_scale" name="production_product[`+ i +`][scale]" data-product_id="production_product_`+ i +`_product_id" data-unit_id="production_product_`+ i +`_unit_id" data-qty="production_product_`+ i +`_qty" data-price="production_product_`+ i +`_price" data-sub_total="production_product_`+ i +`_sub_total"/> </td>
                              <td><input class="form-control productionQty text-center" id="production_product_`+ i +`_production_qty" name="production_product[`+ i +`][production_qty]"/> </td>
                              <td><input class="form-control price text-center" id="production_product_`+ i +`_price" name="production_product[`+ i +`][price]" data-product_id="production_product_`+ i +`_product_id" data-qty="production_product_`+ i +`_qty" data-sub_total="production_product_`+ i +`_sub_total"/> </td>
                              <td><input class="form-control bg-primary text-white sub_total text-center" id="production_product_`+ i +`_sub_total" name="production_product[`+ i +`][sub_total]" readonly/> </td>
                              <th rowspan="4">
                                <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/>
                                <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button>
                              </th>
                          </tr>
                          <tr><td colspan = "9"><button type="button" class="btn btn-success btn-block">{{__('file.Packing')}}</button></td></tr>
                          <tr>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Warehouse')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Category')}}</button></td>
                              <td  colspan="2"><button type = "button" class="btn btn-primary btn-block">{{__('file.Product')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Unit')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Available Qty')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Qty')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Price')}}</button></td>
                              <td><button type = "button" class="btn btn-primary btn-block">{{__('file.Sub Total')}}</button></td>
                          </tr>
                          <tr>
                              <td>
                              <select class="form-control selectpicker text-center" id="production_product_`+ i +`_use_warehouse_id" name="production_product[`+ i +`][use_warehouse_id]" data-live-search = "true">
                              <option value="">{{__('Please Select')}}</option>
                              @foreach($warehouses as $warehouse)
                              <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                              @endforeach
                              </select>
                              </td>
                              <td>
                              <select class="form-control selectpicker useCategory text-center" id="production_product_`+ i +`_use_category_id" data-warehouse_id="production_product_`+ i +`_use_warehouse_id" data-product_id="production_product_`+ i +`_use_product_id" data-live-search = "true">
                              <option value="">{{__('Please Select')}}</option>
                              @foreach($categories as $category)
                              <option value="{{$category->id}}">{{$category->category_name}}</option>
                              @endforeach
                              </select>
                              </td>
                              <td  colspan="2"><select class="form-control selectpicker useProduct text-center" id="production_product_`+ i +`_use_product_id" name="production_product[`+ i +`][use_product_id]" data-warehouse_id="production_product_`+ i +`_use_warehouse_id" data-unit_show="production_product_`+ i +`_use_unit_show" data-unit_id="production_product_`+ i +`_use_unit_id" data-available_qty="production_product_`+ i +`_use_available_qty" data-price="production_product_`+ i +`_use_price" data-live-search = "true"></select></td>
                              <td><input class="form-control bg-primary text-white text-center" id="production_product_`+ i +`_use_unit_show" readonly/><input type="hidden" id="production_product_`+ i +`_use_unit_id"/></td>
                              <td><input class="form-control bg-primary available_qty text-center text-white" id="production_product_`+ i +`_use_available_qty" readonly/></td>
                              <td><input class="form-control useQty text-center" id="production_product_`+ i +`_use_qty" name="production_product[`+ i +`][use_qty]" data-product_id="production_product_`+ i +`_use_product_id" data-unit_id="production_product_`+ i +`_use_unit_id" data-available_qty="production_product_`+ i +`_use_available_qty" data-price="production_product_`+ i +`_use_price" data-sub_total="production_product_`+ i +`_use_sub_total"/></td>
                              <td><input class="form-control usePrice text-center" id="production_product_`+ i +`_use_price" name="production_product[`+ i +`][use_price]" data-qty="production_product_`+ i +`_use_qty" data-product_id="production_product_`+ i +`_use_product_id" data-sub_total="production_product_`+ i +`_use_sub_total"/></td>
                              <td><input class="form-control useSubTotal bg-primary text-white text-center" id="production_product_`+ i +`_use_sub_total" name="production_product[`+ i +`][use_sub_total]" readonly/></td>
                          </tr>
                       </tbody>
                 </table>`;
            $('#productionSaleTableAppend').append(html);
            $('.selectpicker').selectpicker('refresh');
            i++;
        });
        $(document).on('click','.deleteRaw',function(){
            $(this).parent().parent().parent().remove();
        });
        function storeData(){
            let form     = document.getElementById('production_product_form');
            let formData = new FormData(form);
            let url      = "{{route('production.product.store')}}";
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
                    $('#production_product_form').find('.is-invalid').removeClass('is-invalid');
                    $('#production_product_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#production_product_form input#' + key).addClass('is-invalid');
                            $('#production_product_form textarea#' + key).addClass('is-invalid');
                            $('#production_product_form select#' + key).parent().addClass('is-invalid');
                            $('#production_product_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
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
