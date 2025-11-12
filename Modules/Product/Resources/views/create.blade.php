@extends('layouts.app')
@section('title', $page_title)
@push('styles')
@endpush
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                <div class="card-toolbar">
                    <a href="{{ route('product') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i>{{__('file.Back')}}</a>
                </div>
            </div>
        </div>
        <div class="card card-custom" style="padding-bottom: 100px !important;">
            <div class="card-body">
                <form id="store_or_update_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <input type="hidden" name="opening_stock" id="opening_stock" value="2"/>
                                <x-form.textbox labelName="{{__('file.Product Name')}}" name="product_name" required="required" col="col-md-6" placeholder="{{__('file.Enter product name')}}"/>
                                <x-form.selectbox labelName="{{__('file.Category')}}" name="category_id" required="required" col="col-md-6" class="selectpicker">
                                    @if (!$categories->isEmpty())
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    @endif
                                </x-form.selectbox>
                                <div class="col-md-6 form-group required">
                                    <label for="code">{{__('file.Product Code')}}</label>
                                    <div class="input-group" id="code_section">
                                        <input type="text" class="form-control" name="product_code" id="code">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-primary" id="generate-code"  data-toggle="tooltip" data-theme="dark" title="{{__('file.Generate Code')}}" style="border-top-right-radius: 0.42rem;border-bottom-right-radius: 0.42rem;border:0;cursor: pointer;"><i class="fas fa-retweet text-white"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="code">{{__('file.Unit')}}</label>
                                    <select name="unit_id" id="unit_id" class="form-control selectpicker" data-live-search="true"  data-live-search-placeholder="Search">
                                        <option value="">{{__('file.Select Please')}}</option>
                                        @if (!$units->isEmpty())
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->unit_name.' ('.$unit->unit_code.')' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <x-form.textbox labelName="{{__('file.Purchase Price')}}" name="purchase_price" required="required" col="col-md-4" placeholder="{{__('file.Purchase Price')}}"/>
                                <x-form.textbox labelName="{{__('file.Sale Price')}}" name="sale_price" required="required" col="col-md-4" placeholder="{{__('file.Sale Price')}}"/>
                                <x-form.textbox labelName="{{__('file.Alert Quantity')}}" name="alert_qty"  col="col-md-4" placeholder="{{__('file.Enter product alert qty')}}"/>
                                <div class="form-group col-md-12 d-none" style="padding-top: 25px;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="opening_stock_check" value="2">
                                        <label class="custom-control-label" for="opening_stock_check">{{__('file.This Product Has Company Stock')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-none" id="openingStockForm">
                                <div class="col-md-4" id="openingStock_0_warehouse_id_show">
                                    <label for="openingStock_0_warehouse_id">{{__('file.Company')}}</label>
                                    <select class="form-control selectpicker" id="openingStock_0_warehouse_id" name="openingStock[0][warehouse_id]" required="required" data-live-search="true">
                                        <option value="">{{__('file.Please Select')}}</option>
                                        @if (!$warehouses->isEmpty())
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3" id="openingStock_0_scale_show">
                                    <label for="openingStock_0_scale">{{__('file.Scale')}}</label>
                                    <input type="text" class="form-control" id="openingStock_0_scale" name="openingStock[0][scale]" required="required" placeholder="0">
                                </div>
                                <div class="col-md-3" id="openingStock_0_qty_show">
                                    <label for="openingStock_0_qty">{{__('file.Quantity')}}</label>
                                    <input type="text" class="form-control" id="openingStock_0_qty" name="openingStock[0][qty]" required="required" placeholder="0">
                                </div>
                                <div class="col-md-2" style="margin-top:27px">
                                    <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button>
                                    <button type = "button" class = "btn btn-danger btn-sm deleteRaw" data-warehouse_id="openingStock_0_warehouse_id_show" data-scale="openingStock_0_scale_show" data-qty="openingStock_0_qty_show"><i class = "fas fa-minus-circle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="form-group col-md-12 mb-0">
                                    <label for="logo" class="form-control-label">{{__('file.Product Image')}}</label>
                                    <div class="col=md-12 px-0  text-center"><div id="image"></div></div>
                                    <input type="hidden" name="old_image" id="old_image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-3"><br/><button type="button" class="btn btn-block btn-danger">{{__('file.Reset')}}</button></div>
                        <div class="col-md-3"><br/><button type="button" class="btn btn-primary btn-block" id="save-btn" onclick="storeData()">{{__('file.Save')}}</button></div>
                        <div class="col-md-3"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('js/spartan-multi-image-picker-min.js')}}"></script>
<script>
$(document).ready(function () {
    let i = 1;
    $("#image").spartanMultiImagePicker({
        fieldName       : 'image',
        maxCount        : 1,
        rowHeight       : '200px',
        groupClassName  : 'col-md-12 col-sm-12 col-xs-12',
        maxFileSize     : '',
        dropFileLabel   : "Drop Here",
        allowedExt      : 'png|jpg|jpeg',
        onExtensionErr  : function(index, file){ Swal.fire({icon: 'error',title: 'Oops...',text: '{{__('file.Only png,jpg,jpeg file format allowed!')}}'}); },
    });
    $("input[name='image']").prop('required',true);
    $('.remove-files').on('click', function(){  $(this).parents(".col-md-12").remove(); });
    $(document).on('click','#generate-code',function(){
        $.ajax({
            url       : "{{ route('product.generate.code') }}",
            type      : "GET",
            dataType  : "JSON",
            beforeSend: function(){
                $('#generate-code').addClass('spinner spinner-white spinner-right');
            },
            complete  : function(){
                $('#generate-code').removeClass('spinner spinner-white spinner-right');
            },
            success   : function (data) {
                data ? $('#store_or_update_form #code').val(data) : $('#store_or_update_form #code').val('');
            },
            error     : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
        });
    });
    $(document).on('change','#opening_stock_check', function(){
        if($(this).is(':checked')) {
            $('#opening_stock').val(1);
            $('#openingStockForm').removeClass('d-none');
            $('.selectpicker').selectpicker('refresh');
        }else{
            $('#opening_stock').val(2);
            $('#openingStockForm').addClass('d-none');
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $(document).on('click','.addRaw',function(){
        let html;
        html = `<div class="col-md-4" id="openingStock_`+ i +`_warehouse_id_show">
                    <label for="openingStock_0_warehouse_id">{{__('file.Company')}}</label>
                    <select class="form-control selectpicker" id="openingStock_`+ i +`_warehouse_id" name="openingStock[`+ i +`][warehouse_id]" required="required" data-live-search="true">
                        <option value="">{{__('file.Please Select')}}</option>
                        @if (!$warehouses->isEmpty())
                        @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                        @endif
                    </select>
               </div>
               <div class="col-md-3" id="openingStock_`+ i +`_scale_show">
                    <label for="openingStock_0_scale">{{__('file.Scale')}}</label>
                    <input type="text" class="form-control" id="openingStock_`+ i +`_scale" name="openingStock[`+ i +`][scale]" required="required" placeholder="0">
               </div>
               <div class="col-md-3" id="openingStock_`+ i +`_qty_show">
                    <label for="openingStock_`+ i +`_qty">{{__('file.Quantity')}}</label>
                    <input type="text" class="form-control" id="openingStock_`+ i +`_qty" name="openingStock[`+ i +`][qty]" required="required" placeholder="0">
               </div>
               <div class="col-md-2" style="margin-top:27px">
                    <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button>
                    <button type = "button" class = "btn btn-danger btn-sm deleteRaw" data-warehouse_id="openingStock_`+ i +`_warehouse_id_show" data-scale="openingStock_`+ i +`_scale_show" data-qty="openingStock_`+ i +`_qty_show"><i class = "fas fa-minus-circle"></i></button>
               </div>`;
        $('#openingStockForm').append(html);
        $('.selectpicker').selectpicker('refresh');
        i++;
    });
    $(document).on('click','.deleteRaw',function(){
        $('#' + $(this).data('warehouse_id') + '').remove();
        $('#' + $(this).data('scale') + '').remove();
        $('#' + $(this).data('qty') + '').remove();
        $(this).parent().remove();
    });
});
function storeData(){
    let form     = document.getElementById('store_or_update_form');
    let formData = new FormData(form);
    let url      = "{{route('product.store.or.update')}}";
    $.ajax({
        url        : url,
        type       : "POST",
        data       : formData,
        dataType   : "JSON",
        contentType: false,
        processData: false,
        cache      : false,
        beforeSend : function(){
            $('#save-btn').addClass('spinner spinner-white spinner-right');
        },
        complete   : function(){
            $('#save-btn').removeClass('spinner spinner-white spinner-right');
        },
        success    : function (data) {
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            if (data.status == false) {
                $.each(data.errors, function (key, value){
                    var key = key.split('.').join('_');
                    $('#store_or_update_form input#' + key).addClass('is-invalid');
                    $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                    $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                    if(key == 'code'){
                        $('#store_or_update_form #' + key).parents('.form-group').append('<small class="error text-danger">' + value + '</small>');
                    }else{
                        $('#store_or_update_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                    }
                });
            } else {
                notification(data.status, data.message);
                if (data.status == 'success') {  window.location.replace("{{ route('product') }}"); }
            }
        },
        error: function (xhr, ajaxOption, thrownError) {console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);}
    });
}
</script>
@endpush
