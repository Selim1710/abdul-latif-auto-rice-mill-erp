@extends('layouts.app')
@section('title', $page_title)
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                <div class="card-toolbar"><a href="{{ route('product') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i>{{__('file.Back')}}</a></div>
            </div>
        </div>
        <div class="card card-custom" style="padding-bottom: 100px !important;">
            <div class="card-body">
                <div class="row">
                    @if (!empty($product->image))
                        <div class="col-md-12 text-center">
                            <img src="{{ asset('storage/'.PRODUCT_IMAGE_PATH.$product->image) }}" alt="{{ $product->name }}" style="width: 100%;">
                        </div>
                    @endif
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr class="text-center">
                                <td width="40%"><b>{{__('file.Product Name')}}</b></td>
                                <td width="20%"><b>:</b></td>
                                <td width="40%">{{ $product->product_name }}</td>
                            </tr>
                            <tr class="text-center">
                                <td width="40%"><b>{{__('file.Product Code')}}</b></td>
                                <td width="20%"><b>:</b></td>
                                <td width="40%">{{ $product->product_code }}</td>
                            </tr>
                            <tr class="text-center">
                                <td width="40%"><b>{{__('file.Category')}}</b></td>
                                <td class="20%"><b>:</b></td>
                                <td class="40%">{{ $product->category->category_name }}</td>
                            </tr>
                            <tr class="text-center">
                                <td class="40%"><b>{{__('file.Stock Unit')}}</b></td>
                                <td class="20%"><b>:</b></td>
                                <td class="40%">{{ $product->unit->unit_name }}</td>
                            </tr>
                            <tr class="text-center">
                                <td class="40%"><b>{{__('file.Unit')}}</b></td>
                                <td class="20%"><b>:</b></td>
                                <td class="40%">{{ $product->unit->unit_name.'('.$product->unit->unit_code.')' }}</td>
                            </tr>
                            <tr class="text-center">
                                <td class="40%"><b>{{__('file.Purchase Price')}}</b></td>
                                <td class="20%"><b>:</b></td>
                                <td class="40%">{{ $product->purchase_price }}</td>
                            </tr>
                            <tr class="text-center">
                                <td class="40%"><b>{{__('file.Sale Price')}}</b></td>
                                <td class="20%"><b>:</b></td>
                                <td class="40%">{{ $product->sale_price }}</td>
                            </tr>
                            <tr class="text-center">
                                <td width="40%"><b>{{__('file.Alert Quantity')}}</b></td>
                                <td width="20%"><b>:</b></td>
                                <td width="40%">{{ $product->alert_qty }}</td>
                            </tr>
                        </table>
                        @if($product->openingStockList->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td>{{__('file.Company')}}</td>
                                <td>{{__('file.Product')}}</td>
                                <td>{{__('file.Scale')}}</td>
                                <td>{{__('file.Qty')}}</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product->openingStockList as $value)
                            <tr>
                                <td>{{$value->warehouse->name}}</td>
                                <td>{{$value->product->product_name}}</td>
                                <td>{{$value->product->scale}}</td>
                                <td>{{$value->product->qty}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
