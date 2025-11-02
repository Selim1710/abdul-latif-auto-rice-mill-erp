@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/pages/wizard/wizard-4.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .wizard.wizard-4 .wizard-nav .wizard-steps{
            flex-direction: column;
        }
        .wizard.wizard-4 .wizard-nav .wizard-steps .wizard-step .wizard-wrapper{
            flex: 1;
            display: flex;
            align-items: center;
            flex-wrap: unset !important;
            color: #3F4254;
            padding: 2rem 2.5rem !important;
            height: 58px !important;
            margin-bottom: 5px;
            background-color: gainsboro;
            justify-content: center;
        }
        .wizard.wizard-4 .wizard-nav .wizard-steps .wizard-step{
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    @php $i = 1 @endphp
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <div>
                            @if(permission('tenant-production-delivery-add'))
                            <span><a href="{{route('tenant.production.delivery.add',$production->id)}}" class="btn btn-danger btn-sm font-weight-bolder"><i class="fab fa-opencart"></i>&nbsp;{{__('file.Delivery')}}</a></span>
                            @endif
                            @if(permission('tenant-production-product-add'))
                            <span><a href="{{route('tenant.production.product.add',$production->id)}}" class="btn btn-info btn-sm font-weight-bolder"><i class="fas fa-boxes"></i>&nbsp;{{__('file.Stock')}}</a></span>
                            @endif
                            @if(permission('tenant-production-show'))
                            <span><a href="{{route('tenant.production.show',$production->id)}}" class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-eye"></i> {{__('file.Details')}}</a></span>
                            @endif
                            @if(permission('tenant-production-access'))
                            <span><a href="{{route('tenant.production')}}" class="btn btn-secondary btn-sm font-weight-bolder"><i class="fas fa-arrow-circle-left"></i> {{__('file.Back')}}</a></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <form class="form mt-0 mt-lg-10 fv-plugins-bootstrap fv-plugins-framework" id="store_or_update_form" method="POST">
                @csrf
                <input type="hidden" name="tenant_production_id" value="{{$production->id}}"/>
                <input type="hidden" name="tenant_id" value="{{$production->tenant_id}}"/>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr class="text-center font-weight-bold text-primary">
                                        <td>{{__('file.Invoice No')}}</td>
                                        <td>{{':'}}</td>
                                        <td>{{$production->invoice_no}}</td>
                                        <td>{{__('file.Start Date')}}</td>
                                        <td>{{':'}}</td>
                                        <td>{{$production->start_date}}</td>
                                        <td>{{__('file.Mill')}}</td>
                                        <td>{{':'}}</td>
                                        <td>{{$production->mill->name}}</td>
                                        <td>{{__('file.Tenant')}}</td>
                                        <td>{{':'}}</td>
                                        <td>{{$production->tenant->name}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="wizard wizard-4" id="kt_wizard" data-wizard-state="first" data-wizard-clickable="true">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="wizard-nav">
                                        <div class="wizard-steps">
                                            <div class="wizard-step step step-1" data-wizard-type="step" data-wizard-state="current">
                                                <div class="wizard-wrapper">
                                                    <div class="wizard-label">
                                                        <div class="wizard-title">{{__('file.Milling')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wizard-step step step-2" data-wizard-type="step" data-wizard-state="pending">
                                                <div class="wizard-wrapper">
                                                    <div class="wizard-label">
                                                        <div class="wizard-title">{{__('file.Expense')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wizard-step step step-3" data-wizard-type="step" data-wizard-state="pending">
                                                <div class="wizard-wrapper">
                                                    <div class="wizard-label">
                                                        <div class="wizard-title">{{__('file.Summary')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="card card-custom card-shadowless rounded-top-0">
                                        <div class="card-body p-0">
                                            <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10 tenant-production-card">
                                                <div class="col-xl-12 col-xxl-12">
                                                    <div class="pb-5 step step-1" data-wizard-type="step-content" data-wizard-state="current">
                                                        @include('tenantproduction::productionForm.milling')
                                                    </div>
                                                    <div class="pb-5 step step-2" data-wizard-type="step-content">
                                                        @include('tenantproduction::productionForm.expense')
                                                    </div>
                                                    <div class="pb-5 step step-3" data-wizard-type="step-content">
                                                        @include('tenantproduction::productionForm.summary')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
        function __(y){
            return document.getElementsByClassName(y);
        }
        $(document).on('click','.next_button',function(){
            let type = $(this).data('type');
            if(type == 'milling'){
                for(let j = 0 ; j < $('.rate').length ; j++){
                    if(__('useQty')[j].value == '' || __('useScale')[j].value == '' || __('useProQty')[j].value == '' || __('rate')[j].value == ''){
                        notification('error','{{__('file.Empty Input Field')}}');
                        return ;
                    }
                }
                show_form(2);
                calculation();
            }else if(type == 'expense'){
                show_form(3);
                calculation();
            }
        });
        $(document).on('input','.useQty',function(){
            let proQty        = $(this).data('pro_qty');
            let scale         = $(this).data('scale');
            let unitId        = $(this).data('unit_id');
            let useScale      = $(this).data('use_scale');
            _(useScale).value = $(this).val() * _(unitId).value;
            if( parseFloat($(this).val()) > parseFloat(_(proQty).value) ){
                $(this).val('');
                _(useScale).value = '';
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            if( parseFloat(_(useScale).value) > parseFloat(_(scale).value) ){
                $(this).val('');
                _(useScale).value = '';
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            calculation();
        });
        $(document).on('input','.useScale',function(){
            let proQty       = $(this).data('pro_qty');
            let scale        = $(this).data('scale');
            let unitId       = $(this).data('unit_id');
            let useQty       = $(this).data('use_qty');
            _(useQty).value  = $(this).val() / _(unitId).value;
            if(parseFloat(_(useQty).value) > parseFloat(_(proQty).value)){
                $(this).val('');
                _(useQty).value = '';
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            if( parseFloat($(this).val()) > parseFloat(_(scale).value) ){
                $(this).val('');
                _(useQty).value = '';
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            calculation();
        });
        $(document).on('input','.useProQty',function(){
            let proQty       = $(this).data('pro_qty');
            if(parseFloat($(this).val()) > parseFloat(_(proQty).value)){
                $(this).val('');
                notification('error','Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            calculation();
        });
        $(document).on('change','.mergeCategory',function(){
            let html;
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let categoryId  = $(this).find(":selected").val();
            let productId   = $(this).data('product_id');
            if( warehouseId != '' && categoryId != ''){
                $.ajax({
                    url     : "{{url('tenant-category-product')}}/" + categoryId,
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
        $(document).on('change','.mergeProduct',function(){
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let productId   = $(this).find(":selected").val();
            let price       = $(this).data('price');
            let unitId      = $(this).data('unit_id');
            let unitShow    = $(this).data('unit_id_show');
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
                            console.log(data);
                        }
                    }
                });
            }else{
                notification('error','Product Not Selected')
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
            calculation();
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
            calculation();
        });
        $(document).on('input','.merQty',function(){
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
            calculation();
        });
        $(document).on('input','.rate',function(){
           let useScale = $(this).data('use_scale');
           let milling  = $(this).data('milling');
           if(_(useScale).value == ''){
               $(this).val('');
               notification('error','Please Give Use Scale');
               return;
           }
           _(milling).value = parseFloat($(this).val()) * parseFloat(_(useScale).value);
           calculation();
        });
        $(document).on('input','.merRate',function(){
            let useScale = $(this).data('use_scale');
            let milling  = $(this).data('milling');
            if(_(useScale).value == ''){
                $(this).val('');
                notification('error','Please Give Use Scale');
                return;
            }
            _(milling).value = parseFloat($(this).val()) * parseFloat(_(useScale).value);
            calculation();
        });
        $(document).on('click','.addRaw',function(){
            let html;
            html = `<tr class="text-center">
                       <td>
                       <select class="form-control selectpicker" id="merge_`+ i +`_warehouse_id" name="merge[`+ i +`][warehouse_id]" data-product_id = "merge_`+ i +`_product_id" data-live-search="true">
                       <option value="" selected>{{__('file.Select Please')}}</option>
                       @if (!$warehouses->isEmpty())
                       @foreach ($warehouses as $value)
                       <option value="{{ $value->id }}">{{ $value->name }}</option>
                       @endforeach
                       @endif
                       </select>
                       </td>
                       <td>
                       <select class="form-control selectpicker mergeCategory" id="merge_`+ i +`_category_id" data-warehouse_id="merge_`+ i +`_warehouse_id" data-product_id = "merge_`+ i +`_product_id" data-live-search="true">
                       <option value="" selected>{{__('file.Select Please')}}</option>
                       @if (!$categories->isEmpty())
                       @foreach ($categories as $value)
                       <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                       @endforeach
                       @endif
                       </select>
                       </td>
                       <td>
                       <select class="form-control product selectpicker mergeProduct" id="merge_`+ i +`_product_id" data-warehouse_id="merge_`+ i +`_warehouse_id" data-unit_id_show = "merge_`+ i +`_unit_id_show" data-unit_id = "merge_`+ i +`_unit_id" data-price = "merge_`+ i +`_price" data-available_qty = "merge_`+ i +`_available_qty" name="merge[`+ i +`][product_id]" data-live-search="true"></select>
                       </td>
                       <td>
                       <input type="text" class="form-control bg-primary" id="merge_`+ i +`_unit_id_show" readonly/>
                       <input type="hidden" class="form-control bg-primary" id="merge_`+ i +`_unit_id" readonly/>
                       <input type="hidden" id="merge_`+ i +`_price" name="merge[`+ i +`][price]"/>
                       </td>
                       <td><input type="text" class="form-control bg-primary" id="merge_`+ i +`_available_qty" readonly/></td>
                       <td><input type="text" class="form-control qty" id="merge_`+ i +`_qty" name="merge[`+ i +`][qty]" data-product_id = "merge_`+ i +`_product_id" data-available_qty = "merge_`+ i +`_available_qty" data-unit_id = "merge_`+ i +`_unit_id" data-scale="merge_`+ i +`_scale"/></td>
                       <td><input type="text" class="form-control merScale" id="merge_`+ i +`_scale" name="merge[`+ i +`][scale]" data-product_id = "merge_`+ i +`_product_id" data-available_qty = "merge_`+ i +`_available_qty" data-unit_id = "merge_`+ i +`_unit_id" data-qty="merge_`+ i +`_qty"/></td>
                       <td><input type="text" class="form-control merQty" id="merge_`+ i +`_mer_qty" name="merge[`+ i +`][mer_qty]" data-product_id = "merge_`+ i +`_product_id" data-available_qty = "merge_`+ i +`_available_qty"/></td>
                       <td><input type="text" class="form-control merRate" id="merge_`+ i +`_rate" name="merge[`+ i +`][rate]" data-use_scale="merge_`+ i +`_scale" data-milling="merge_`+ i +`_milling"/></td>
                       <td><input type="text" class="form-control milling bg-primary" id="merge_`+ i +`_milling" name="merge[`+ i +`][milling]" readonly/> </td>
                       <td><button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/><button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button></td>
                   </tr>`;
            $('#millingTableAppend tbody').append(html);
            $('.selectpicker').selectpicker('refresh');
            i++;
        });
        $(document).on('click','.deleteRaw',function(){
            $(this).parent().parent().remove();
            calculation();
        });
        $(document).on('input','.expense_cost',function(){
            calculation();
        })
        function calculation(){
            let productionRawScale  = 0;
            let milling             = 0;
            let expenseCost         = 0;
            let mergeScale          = 0;
            $('.milling').each(function(){
                if($(this).val() == ''){
                    milling += + 0;
                }else{
                    milling += + $(this).val();
                }
            });
            $('.useScale').each(function(){
                if($(this).val() == ''){
                    productionRawScale  += + 0;
                }else{
                    productionRawScale  += + $(this).val();
                }
            });
            $('.expense_cost').each(function(){
                if($(this).val() == ''){
                    expenseCost += + 0;
                }else{
                    expenseCost += + $(this).val();
                }
            });
            $('.merScale').each(function(){
                if($(this).val() == ''){
                    mergeScale += + 0;
                }else{
                    mergeScale += + $(this).val();
                }
            });
            _('total_milling_show').value      = milling;
            _('total_expense_show').value      = expenseCost;
            _('total_merge_scale_show').value  = mergeScale;
            _('total_merge_scale').value       = mergeScale;
            _('total_raw_scale').value         = productionRawScale;
            _('total_milling').value           = milling;
            _('total_expense').value           = expenseCost;
        }
        function show_form(step) {
            $('.step').attr('data-wizard-state','pending');
            $('.step-'+step).attr('data-wizard-state','current');
        }
        function store_data(){
            let form     = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            if(_('total_stock_scale').value == ''){
                notification('error','There Is No Stock In These Production Please Entry Production Stock Entry');
                return;
            }
            let url      = "{{route('tenant.production.complete')}}";
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
                    if (data.status == false) {
                        notification(data.status, data.message);
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') { window.location.replace("{{ route('tenant.production') }}"); }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    </script>
@endpush

