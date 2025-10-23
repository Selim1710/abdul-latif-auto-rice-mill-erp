@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    @if(permission('purchase-access'))
                        <div class="card-toolbar"><a href="{{ route('distribution') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a></div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form action="" id="distribution_store_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-4 required">
                                    <label for="invoice_no">{{__('file.Invoice No')}}.</label>
                                    <input type="text" class="form-control bg-primary text-center" id="invoice_no" name="invoice_no" value="{{$invoice_no}}" readonly/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="date">{{__('file.Date')}}.</label>
                                    <input type="date" class="form-control text-center" id="date" name="date" value="{{date('Y-m-d')}}"/>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="receiver">{{__('file.Receiver')}}.</label>
                                    <input type="text" class="form-control text-center" id="receiver_name" name="receiver_name"/>
                                </div>
                                <div class="col-md-12">
                                    <hr style="border-top: 5px dotted cadetblue;"/>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="">
                                            <table class="table table-bordered distributionTableAppend" id="distributionTableAppend">
                                                <thead class="bg-primary text-center">
                                                <th width="10%" >{{__('file.Company')}}</th>
                                                <th width="10%" >{{__('file.Category')}}</th>
                                                <th width="12%" >{{__('file.Product')}}</th>
                                                <th width="8%" >{{__('file.Unit')}}</th>
                                                <th width="10%" >{{__('file.Available Qty')}}</th>
                                                <th width="10%" >{{__('file.Qty')}}</th>
                                                <th width="10%" >{{__('file.Scale')}}</th>
                                                <th width="10%" >{{__('file.Dis Qty')}}</th>
                                                <th width="10%" >{{__('file.Action')}}</th>
                                                </thead>
                                                <tbody>
                                                <tr class="text-center">
                                                    <td>
                                                        <select class="form-control selectpicker" id="distribution_0_warehouse_id" name="distribution[0][warehouse_id]" data-product_id = "distribution_0_product_id" data-live-search="true">
                                                            <option value="" selected>{{__('file.Select Please')}}</option>
                                                            @if (!$warehouses->isEmpty())
                                                                @foreach ($warehouses as $value)
                                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control selectpicker category" id="distribution_0_category_id" data-warehouse_id="distribution_0_warehouse_id" data-product_id = "distribution_0_product_id" data-live-search="true">
                                                            <option value="" selected>{{__('file.Select Please')}}</option>
                                                            @if (!$categories->isEmpty())
                                                                @foreach ($categories as $value)
                                                                    <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td><select class="form-control product selectpicker" id="distribution_0_product_id" data-warehouse_id="distribution_0_warehouse_id" data-unit_id_show = "distribution_0_unit_id_show" data-unit_id = "distribution_0_unit_id" data-available_qty = "distribution_0_available_qty" name="distribution[0][product_id]" data-live-search="true"></select></td>
                                                    <td>
                                                        <input type="text" class="form-control bg-primary" id="distribution_0_unit_id_show" readonly/>
                                                        <input type="hidden" class="form-control bg-primary" id="distribution_0_unit_id" readonly/>
                                                    </td>
                                                    <td><input type="text" class="form-control bg-primary" id="distribution_0_available_qty" readonly/></td>
                                                    <td><input type="number" class="form-control qty" id="distribution_0_qty" name="distribution[0][qty]" data-product_id = "distribution_0_product_id" data-available_qty = "distribution_0_available_qty" data-unit_id = "distribution_0_unit_id" data-scale="distribution_0_scale"/></td>
                                                    <td><input type="text" class="form-control scale" id="distribution_0_scale" name="distribution[0][scale]" data-product_id = "distribution_0_product_id" data-available_qty = "distribution_0_available_qty" data-unit_id = "distribution_0_unit_id" data-qty="distribution_0_qty"/></td>
                                                    <td><input type="text" class="form-control disQty" id="distribution_0_dis_qty" name="distribution[0][dis_qty]" data-product_id = "distribution_0_product_id" data-available_qty = "distribution_0_available_qty"/></td>
                                                    <td><button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/><button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 text-center pt-5">
                                    <a class = "btn btn-danger btn-sm mr-3" href="{{route('purchase.add')}}"><i class="fas fa-sync-alt"></i>{{__('file.Reset')}}</a>
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
        $(document).on('change','.category',function(){
            let html;
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let categoryId  = $(this).find(":selected").val();
            let productId   = $(this).data('product_id');
            $('#'+ productId +'').empty();
            $('.selectpicker').selectpicker('refresh');
            if( warehouseId != '' && categoryId != ''){
                $.ajax({
                    url     : "{{url('distribution-category-product')}}/" + categoryId,
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
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let productId   = $(this).find(":selected").val();
            let unitIdShow  = $(this).data('unit_id_show');
            let unitId      = $(this).data('unit_id');
            let availableQty= $(this).data('available_qty');
            if(productId != ''){
                $.ajax({
                    url     : "{{url('distribution-product-details')}}/" + warehouseId + "/" + productId,
                    method  : 'GET',
                    success : function(data){
                        if(data){
                            $('#'+ unitIdShow +'').val(data.unitIdShow);
                            $('#'+ unitId +'').val(data.unitId);
                            $('#'+ availableQty +'').val(data.availableQty);
                        }
                    }
                });
            }else{
                notification('error','Sale Type Or Product Not Selected')
            }
        });
        $(document).on('input','.qty',function(){
            let productId    = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let availableQty = $(this).data('available_qty');
            let unitId       = $(this).data('unit_id');
            let scale        = $(this).data('scale');
            if(  productId == '' ){
                $(this).val('');
                _(scale).value    = '';
                notification('error','Product Not Selected');
                return;
            }
            if(parseFloat($(this).val()) > parseFloat(_(availableQty).value)){
                $(this).val('');
                _(scale).value    = '';
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            _(scale).value     = $(this).val() * _(unitId).value;
        });
        $(document).on('input','.scale',function(){
            let productId    = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let availableQty = $(this).data('available_qty');
            let unitId       = $(this).data('unit_id');
            let qty          = $(this).data('qty');
            if( productId == '' ){
                $(this).val('');
                _(qty).value      = '';
                notification('error','Price Or Product Not Selected');
                return;
            }
            _(qty).value       = $(this).val() / _(unitId).value;
            if(parseFloat(_(qty).value) > parseFloat(_(availableQty).value)){
                $(this).val('');
                _(qty).value   = '';
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
        });
        $(document).on('input','.disQty',function(){
            let productId    = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let availableQty = $(this).data('available_qty');
            if(  productId == '' ){
                $(this).val('');
                notification('error','Product Not Selected');
                return;
            }
            if(parseFloat($(this).val()) > parseFloat(_(availableQty).value)){
                $(this).val('');
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
        });
        $(document).on('click','.addRaw',function(){
            let html;
            html = `<tr class="text-center">
                      <td>
                      <select class="form-control selectpicker" id="distribution_`+ i +`_warehouse_id" name="distribution[`+ i +`][warehouse_id]" data-product_id = "distribution_`+ i +`_product_id" data-live-search="true">
                      <option value="" selected>{{__('file.Select Please')}}</option>
                      @if (!$warehouses->isEmpty())
                      @foreach ($warehouses as $value)
                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endforeach
                      @endif
                      </select>
                      </td>
                      <td>
                      <select class="form-control selectpicker category" id="distribution_`+ i +`_category_id" data-warehouse_id="distribution_`+ i +`_warehouse_id" data-product_id = "distribution_`+ i +`_product_id" data-live-search="true">
                      <option value="" selected>{{__('file.Select Please')}}</option>
                      @if (!$categories->isEmpty())
                      @foreach ($categories as $value)
                      <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                      @endforeach
                      @endif
                      </select>
                      </td>
                      <td><select class="form-control product selectpicker" id="distribution_`+ i +`_product_id" data-warehouse_id="distribution_`+ i +`_warehouse_id" data-unit_id_show = "distribution_`+ i +`_unit_id_show" data-unit_id = "distribution_`+ i +`_unit_id" data-available_qty = "distribution_`+ i +`_available_qty" name="distribution[`+ i +`][product_id]" data-live-search="true"></select></td>
                      <td>
                      <input type="text" class="form-control bg-primary" id="distribution_`+ i +`_unit_id_show" readonly/>
                      <input type="hidden" class="form-control bg-primary" id="distribution_`+ i +`_unit_id" readonly/>
                      </td>
                      <td><input type="text" class="form-control bg-primary" id="distribution_`+ i +`_available_qty" readonly/></td>
                      <td><input type="number" class="form-control qty" id="distribution_`+ i +`_qty" name="distribution[`+ i +`][qty]" data-product_id = "distribution_`+ i +`_product_id" data-available_qty = "distribution_`+ i +`_available_qty" data-unit_id = "distribution_`+ i +`_unit_id" data-scale="distribution_`+ i +`_scale"/></td>
                      <td><input type="text" class="form-control scale" id="distribution_`+ i +`_scale" name="distribution[`+ i +`][scale]" data-product_id = "distribution_`+ i +`_product_id" data-available_qty = "distribution_`+ i +`_available_qty" data-unit_id = "distribution_`+ i +`_unit_id" data-qty="distribution_`+ i +`_qty"/></td>
                      <td><input type="text" class="form-control disQty" id="distribution_`+ i +`_dis_qty" name="distribution[`+ i +`][dis_qty]" data-product_id = "distribution_`+ i +`_product_id" data-available_qty = "distribution_`+ i +`_available_qty"/></td>
                      <td><button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/><button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button></td>
                   </tr>`;
            $('#distributionTableAppend tbody').append(html);
            $('.selectpicker').selectpicker('refresh');
            i++;
        });
        $(document).on('click','.deleteRaw',function(){
            $(this).parent().parent().remove();
            calculation();
        });
        function storeData(){
            let form     = document.getElementById('distribution_store_form');
            let formData = new FormData(form);
            let url      = "{{route('distribution.store')}}";
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
                    $('#distribution_store_form').find('.is-invalid').removeClass('is-invalid');
                    $('#distribution_store_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#distribution_store_form input#' + key).addClass('is-invalid');
                            $('#distribution_store_form textarea#' + key).addClass('is-invalid');
                            $('#distribution_store_form select#' + key).parent().addClass('is-invalid');
                            $('#distribution_store_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('distribution') }}"); }
                    }
                },
                error        : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush
