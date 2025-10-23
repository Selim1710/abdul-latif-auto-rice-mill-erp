@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    @if(permission('tenant-return-access'))
                        <div class="card-toolbar"><a href="{{ route('tenant.return.product') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form action="" id="tenant_return_product_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="update_id" value="{{$tenantReturn->id}}"/>
                            <div class="row">
                                <div class="form-group col-md-4 required">
                                    <label for="memo_no">{{__('file.Invoice No')}}.</label>
                                    <input type="text" class="form-control bg-primary text-white text-center" id="invoice_no" name="invoice_no" value="{{$tenantReturn->invoice_no}}" readonly/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="date">{{__('file.Date')}}</label>
                                    <input type="date" class="form-control date" id="date" name="date" value="{{$tenantReturn->date}}"/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="tenant_id">{{__('file.Tenant')}}</label>
                                    <select class="form-control selectpicker" id="tenant_id" name="tenant_id" data-live-search = "true">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @foreach($tenants as $tenant)
                                            <option value="{{$tenant->id}}" @if($tenant->id == $tenantReturn->tenant_id) selected="selected" @endif>{{$tenant->name.'('.$tenant->mobile.')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <hr style="border-top: 5px dotted cadetblue;"/>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <table class="table" id="tenantReturnTable">
                                            <thead class="bg-primary">
                                            <tr class="text-center">
                                                <th width="8%">{{__('file.Company')}}</th>
                                                <th width="8%">{{__('file.Category')}}</th>
                                                <th width="16%">{{__('file.Product')}}</th>
                                                <th width="8%">{{__('file.Unit')}}</th>
                                                <th width="10%">{{__('file.Av Qty')}}</th>
                                                <th width="12%">{{__('file.Av Scale')}}</th>
                                                <th width="10%">{{__('file.Qty')}}</th>
                                                <th width="10%">{{__('file.Scale')}}</th>
                                                <th width="10%">{{__('file.Ret Qty')}}</th>
                                                <th width="8%">{{__('file.Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($tenantReturn->tenantReturnProductList))
                                            @foreach($tenantReturn->tenantReturnProductList as $key => $item)
                                            <tr class="text-center">
                                                <td>
                                                    <select class="form-control selectpicker text-center" id="tenant_return_{{$key}}_warehouse_id" name="tenant_return[{{$key}}][warehouse_id]" data-live-search = "true">
                                                        <option value = "{{$item->warehouse_id}}">{{$item->warehouse->name}}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control selectpicker category text-center" id="tenant_return_{{$key}}_category_id" data-warehouse_id = "tenant_return_{{$key}}_warehouse_id" data-product_id="tenant_return_{{$key}}_product_id" data-live-search = "true">
                                                        <option value = "{{$item->product->category_id}}">{{$item->product->category->category_name}}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control selectpicker product text-center" id="tenant_return_{{$key}}_product_id" name="tenant_return[{{$key}}][product_id]" data-warehouse_id = "tenant_return_{{$key}}_warehouse_id" data-unit_show="tenant_return_{{$key}}_unit_show" data-unit_id="tenant_return_{{$key}}_unit_id" data-av_qty="tenant_return_{{$key}}_av_qty" data-av_scale="tenant_return_{{$key}}_av_scale" data-live-search = "true">
                                                        <option value = "{{$item->product_id}}">{{$item->product->product_name}}</option>
                                                    </select>
                                                </td>
                                                <td><input class="form-control bg-primary text-white text-center" id="tenant_return_{{$key}}_unit_show" value="{{$item->product->unit->unit_name.'('.$item->product->unit->unit_code.')'}}" readonly/><input type="hidden" id="tenant_return_{{$key}}_unit_id" value="{{$item->product->unit->unit_name}}"/></td>
                                                <td><input class="form-control av_qty text-center bg-primary text-white" id="tenant_return_{{$key}}_av_qty" value="{{$item->availableQty($item->warehouse_id,$item->product_id)->qty ?? 0}}" readonly/></td>
                                                <td><input class="form-control av_scale text-center bg-primary text-white" id="tenant_return_{{$key}}_av_scale" value="{{$item->availableQty($item->warehouse_id,$item->product_id)->scale ?? 0}}" readonly/></td>
                                                <td><input class="form-control qty text-center" id="tenant_return_{{$key}}_qty" name="tenant_return[{{$key}}][qty]" value="{{$item->qty}}" data-product_id="tenant_return_{{$key}}_product_id" data-unit_id="tenant_return_{{$key}}_unit_id" data-av_qty="tenant_return_{{$key}}_av_qty" data-av_scale="tenant_return_{{$key}}_av_scale" data-scale="tenant_return_{{$key}}_scale"/></td>
                                                <td><input class="form-control scale text-center" id="tenant_return_{{$key}}_scale" name="tenant_return[{{$key}}][scale]" value="{{$item->scale}}" data-product_id="tenant_return_{{$key}}_product_id" data-unit_id="tenant_return_{{$key}}_unit_id" data-av_qty="tenant_return_{{$key}}_av_qty" data-av_scale="tenant_return_{{$key}}_av_scale" data-qty="tenant_return_{{$key}}_qty"/> </td>
                                                <td><input class="form-control retQty" id="tenant_return_{{$key}}_ret_qty" name="tenant_return[{{$key}}][ret_qty]" value="{{$item->ret_qty}}" data-av_qty="tenant_return_{{$key}}_av_qty"/></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/>
                                                    <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="note">{{__('file.Note')}}</label>
                                    <textarea class="form-control" id="note" name="note">{{$tenantReturn->note}}</textarea>
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
        let i = {{count($tenantReturn->tenantReturnProductList)}} + 1;
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
            if(warehouseId == '' || categoryId == ''){
                notification('error','Warehouse Or Category Not Selected');
                return;
            }
            $.ajax({
                url     : "{{route('tenant.return.product.category')}}",
                data    : {categoryId : categoryId},
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
        });
        $(document).on('change','.product',function(){
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let productId = $(this).find(":selected").val();
            let unitId    = $(this).data('unit_id');
            let unitShow  = $(this).data('unit_show');
            let avScale   = $(this).data('av_scale');
            let avQty     = $(this).data('av_qty');
            let tenantId  = $('#tenant_id').find(":selected").val();
            if(tenantId == ''){
                notification('error','Tenant Select First');
            }
            if(warehouseId == '' || productId == ''){
                notification('error','Company Or Product Not Selected');
                return;
            }
            if(warehouseId != null){
                $.ajax({
                    url     : "{{route('tenant.return.product.details')}}",
                    data    : {warehouseId : warehouseId , productId : productId,tenantId : tenantId},
                    method  : 'GET',
                    success : function(data){
                        if(data){
                            $('#'+ unitId +'').val(data.unitId);
                            $('#'+ unitShow +'').val(data.unitShow);
                            $('#'+ avScale +'').val(data.scale);
                            $('#'+ avQty +'').val(data.availableQty);
                        }else{
                            notification('error','Product Stock Not Found');
                        }
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
            }
        });
        $(document).on('input','.qty',function(){
            let productId = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let unitId    = $(this).data('unit_id');
            let avScale   = $(this).data('av_scale');
            let scale     = $(this).data('scale');
            if(productId == null){
                $(this).val('');
                _(scale).value    = '';
                notification('error','{{__("file.Please Select Product")}}');
                return;
            }
            if(parseFloat(scale) > parseFloat(_(avScale).value)){
                $(this).val('');
                _(scale).value    = '';
                notification('error','{{__("file.Scale Cannot Be Greater Then Available Quantity")}}');
                return;
            }
            _(scale).value     = $(this).val() * _(unitId).value;
        });
        $(document).on('input','.scale',function(){
            let productId = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let unitId    = $(this).data('unit_id');
            let avScale   = $(this).data('av_scale');
            let qty       = $(this).data('qty');
            if(productId == null){
                $(this).val('');
                _(qty).value      = '';
                notification('error','{{__("file.Please Select Product")}}');
                return;
            }
            if(parseFloat($(this).val()) > parseFloat(_(avScale).value)){
                $(this).val('');
                _(qty).value      = '';
                notification('error','{{__("file.Scale Cannot Be Greater Then Available Quantity")}}');
                return;
            }
            _(qty).value       = $(this).val() / _(unitId).value;
        });
        $(document).on('input','.retQty',function(){
            let avQty = $(this).data('av_qty');
            if(_(avQty).value == '' || $(this).val() > parseFloat(_(avQty).value)){
                $(this).val('');
                notification('error','Available Quantity Not Found Or Quantity Greater Then Available Qty');
                return;
            }
        });
        $(document).on('click','.addRaw',function(){
            let html;
            html = `<tr class="text-center">
                      <td>
                      <select class="form-control selectpicker text-center" id="tenant_return_`+ i +`_warehouse_id" name="tenant_return[`+ i +`][warehouse_id]" data-live-search = "true">
                      <option value="">{{__('Please Select')}}</option>
                      @foreach($warehouses as $warehouse)
                      <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                      @endforeach
                      </select>
                      </td>
                      <td>
                      <select class="form-control selectpicker category text-center" id="tenant_return_`+ i +`_category_id" data-warehouse_id = "tenant_return_`+ i +`_warehouse_id" data-product_id="tenant_return_`+ i +`_product_id" data-live-search = "true">
                      <option value="">{{__('Please Select')}}</option>
                      @foreach($categories as $category)
                      <option value="{{$category->id}}">{{$category->category_name}}</option>
                      @endforeach
                      </select>
                      </td>
                      <td>
                      <select class="form-control selectpicker product text-center" id="tenant_return_`+ i +`_product_id" name="tenant_return[`+ i +`][product_id]" data-warehouse_id = "tenant_return_`+ i +`_warehouse_id" data-unit_show="tenant_return_`+ i +`_unit_show" data-unit_id="tenant_return_`+ i +`_unit_id" data-av_qty="tenant_return_`+ i +`_av_qty" data-av_scale="tenant_return_`+ i +`_av_scale" data-live-search = "true"></select>
                      </td>
                      <td><input class="form-control bg-primary text-white text-center" id="tenant_return_`+ i +`_unit_show" readonly/><input type="hidden" id="tenant_return_`+ i +`_unit_id"/></td>
                      <td><input class="form-control av_qty text-center bg-primary text-white" id="tenant_return_`+ i +`_av_qty" readonly/></td>
                      <td><input class="form-control av_scale text-center bg-primary text-white" id="tenant_return_`+ i +`_av_scale" readonly/></td>
                      <td><input class="form-control qty text-center" id="tenant_return_`+ i +`_qty" name="tenant_return[`+ i +`][qty]" data-product_id="tenant_return_`+ i +`_product_id" data-unit_id="tenant_return_`+ i +`_unit_id" data-av_qty="tenant_return_`+ i +`_av_qty" data-av_scale="tenant_return_`+ i +`_av_scale" data-scale="tenant_return_`+ i +`_scale"/></td>
                      <td><input class="form-control scale text-center" id="tenant_return_`+ i +`_scale" name="tenant_return[`+ i +`][scale]" data-product_id="tenant_return_`+ i +`_product_id" data-unit_id="tenant_return_`+ i +`_unit_id" data-av_qty="tenant_return_`+ i +`_av_qty" data-av_scale="tenant_return_`+ i +`_av_scale" data-qty="tenant_return_`+ i +`_qty"/> </td>
                      <td><input class="form-control retQty" id="tenant_return_`+ i +`_ret_qty" name="tenant_return[`+ i +`][ret_qty]" data-av_qty="tenant_return_`+ i +`_av_qty"/></td>
                      <td>
                      <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/>
                      <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button>
                      </td>
                   </tr>`;
            $('#tenantReturnTable tbody').append(html);
            $('.selectpicker').selectpicker('refresh');
            i++;
        });
        $(document).on('click','.deleteRaw',function(){
            $(this).parent().parent().remove();
        });
        function updateData(){
            let form     = document.getElementById('tenant_return_product_form');
            let formData = new FormData(form);
            let url      = "{{route('tenant.return.product.update')}}";
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
                    $('#tenant_return_product_form').find('.is-invalid').removeClass('is-invalid');
                    $('#tenant_return_product_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#tenant_return_product_form input#' + key).addClass('is-invalid');
                            $('#tenant_return_product_form textarea#' + key).addClass('is-invalid');
                            $('#tenant_return_product_form select#' + key).parent().addClass('is-invalid');
                            $('#tenant_return_product_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('tenant.return.product') }}"); }
                    }
                },
                error        : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
