@extends('layouts.app')
@section('title','Dashboard')
@push('styles')
<style>
    .today-btn{
        border-radius: 5px 0 0 5px !important;
    }
    .week-btn,.month-btn{
        border-radius: 0 !important;
    }
    .year-btn{
        border-radius: 0 5px 5px 0 !important;
    }
    .icon{
        width: 40px;
        height: 40px;
    }
</style>
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/customer.png')}}" alt="customer"></div>
                        <br/>
                        <h5><b>{{ $party }}</b></h5>
                        <h5><b>{{__('file.Party')}}</b></h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/supplier.png')}}" alt="supplier"></div>
                        <br/>
                        <h5><b>{{ $tenant }}</b></h5>
                        <h5><b>{{__('file.Tenant')}}</b></h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/labor.png')}}" alt="labor"></div>
                        <br/>
                        <h5><b>{{ $labor }}</b></h5>
                        <h5><b>{{__('file.Labor')}}</b></h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/bank.png')}}" alt="bank"></div>
                        <h5>{{ 0 }}</h5>
                        <h5>{{__('file.Bank')}}</h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/mobile_bank.png')}}" alt="mobile bank"></div>
                        <h5>{{ 0 }}</h5>
                        <h5>{{__('file.Mobile Bank')}}</h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/cash.png')}}" alt="cash"></div>
                        <h5>{{ 0 }}</h5>
                        <h5>{{__('file.Cash')}}</h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/categories.png')}}" alt="category"></div>
                        <br/>
                        <h5><b>{{ $category }}</b></h5>
                        <h5><b>{{__('file.Category')}}</b></h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/product.png')}}" alt="product"></div>
                        <br/>
                        <h5><b>{{ $product }}</b></h5>
                        <h5><b>{{__('file.Product')}}</b></h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/purchase.png')}}" alt="purchase"></div>
                        <br/>
                        <h5><b>{{ number_format($purchases,2) }}</b></h5>
                        <h5><b>{{__('file.Purchase')}}</b></h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/sale.png')}}" alt="sale"></div>
                        <br/>
                        <h5><b>{{ number_format($sales,2) }}</b></h5>
                        <h5><b>{{__('file.Sale')}}</b></h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5 text-primary bold">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/income.png')}}" alt="income"></div>
                        <h5>{{ 0 }}</h5>
                        <h5>{{__('file.Income')}}</h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/expense.png')}}" alt="expense"></div>
                        <h5>{{ 0 }}</h5>
                        <h5>{{__('file.Expense')}}</h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/mill.png')}}" alt="mill"></div>
                        <br/>
                        <h5><b>{{ $mill }}</b></h5>
                        <h5><b>{{__('file.Mill')}}</b></h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/transport.png')}}" alt="transport"></div>
                        <br/>
                        <h5><b>{{ $truck }}</b></h5>
                        <h5><b>{{__('file.Transport')}}</b></h5>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="bg-white text-center py-3 text-primary bold">
                        <div><img src="{{asset('icon/user.png')}}" alt="user"></div>
                        <br/>
                        <h5><b>{{ $user }}</b></h5>
                        <h5><b>{{__('file.User')}}</b></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
{{--$(document).ready(function(){--}}
{{--    $('.data-btn').on('click',function(){--}}
{{--        $('.data-btn').removeClass('active');--}}
{{--        $(this).addClass('active');--}}
{{--        var start_date = $(this).data('start_date');--}}
{{--        var end_date = $(this).data('end_date');--}}
{{--        $.get("{{ url('dashboard-data') }}/"+start_date+'/'+end_date, function(data){--}}
{{--            $('#sale').text((data.sale).toFixed(2)+'Tk');--}}
{{--            $('#purchase').text((data.purchase).toFixed(2)+'Tk');--}}
{{--            $('#machine_purchase').text((data.machine_purchase).toFixed(2)+'Tk');--}}
{{--            $('#expense').text((data.expense).toFixed(2)+'Tk');--}}
{{--            $('#maintenance_service').text((data.maintenance_service).toFixed(2)+'Tk');--}}
{{--            $('#transport_service').text((data.transport_service).toFixed(2)+'Tk');--}}
{{--        });--}}
{{--    });--}}
{{--});--}}
</script>
@endpush
