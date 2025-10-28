@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    @if (permission('purchase-access'))
                        <div class="card-toolbar"><a href="{{ route('purchase') }}"
                                class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i>
                                {{ __('file.Back') }}</a></div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form action="" id="purchase_store_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-3 required">
                                    <label for="memo_no">{{ __('file.Invoice No') }}.</label>
                                    <input type="text" class="form-control bg-primary text-center" id="invoice_no"
                                        name="invoice_no" value="{{ $invoice_no }}" readonly />
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="sale_date">{{ __('file.Purchase Date') }}</label>
                                    <input type="date" class="form-control date" id="purchase_date" name="purchase_date"
                                        value="{{ date('Y-m-d') }}" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="purchase_status">{{ __('file.Purchase Status') }}</label>
                                    <select class="form-control selectpicker" id="purchase_status" name="purchase_status"
                                        data-live-search = "true">
                                        <option value="">{{ __('file.Please Select') }}</option>
                                        @foreach (PURCHASE_STATUS_VALUE as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="document">{{ __('file.Attach Document') }}</label>
                                    <input type="file" class="form-control" name="document" id="document">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="party_type">{{ __('file.Party Type') }}</label>
                                    <select class="form-control party_type selectpicker" id="party_type" name="party_type"
                                        onchange="partyType()">
                                        <option value="">{{ __('file.Please Select') }}</option>
                                        @foreach (PARTY_TYPE_VALUE as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6" id="party_id_show">
                                    <label for="party_id">{{ __('file.Party') }}</label>
                                    <select class="form-control party_id selectpicker" id="party_id" name="party_id"
                                        data-live-search = "true" onchange="partyDue()">
                                        <option value="">{{ __('file.Please Select') }}</option>
                                        @foreach ($parties as $party)
                                            <option value="{{ $party->id }}">
                                                {{ $party->name . '(' . $party->address . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6" id="party_name_show">
                                    <label for="party_name">{{ __('file.Party Name') }}</label>
                                    <input type="text" class="form-control" id="party_name" name="party_name" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="payment_status">{{ __('file.Payment Status') }}</label>
                                    <select class="form-control payment_status selectpicker" id="payment_status"
                                        name="payment_status" onchange="paymentStatus()">
                                        <option value="">{{ __('file.Please Select') }}</option>
                                        @foreach (PAYMENT_STATUS as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="payment_method">{{ __('file.Payment Method') }}</label>
                                    <select class="form-control payment_method" id="payment_method" name="payment_method"
                                        onchange="paymentMethod()">
                                        <option value="">{{ __('file.Please Select') }}</option>
                                        @foreach (SALE_PAYMENT_METHOD as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="account_id">{{ __('file.Account') }}</label>
                                    <select class="form-control" id="account_id" name="account_id">
                                        <option value="">{{ __('file.Please Select') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="discount">{{ __('file.Discount') }}</label>
                                    <input type="text" class="form-control text-center" name="discount" id="discount">
                                </div>
                                <div class="form-group col-md-3 required">
                                    <div id="previous_due_status"></div>
                                    <label for="previous_due">{{ __('file.Previous Due') }}</label>
                                    <input type="text" class="form-control bg-primary text-center" name="previous_due"
                                        id="previous_due" readonly>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="net_total">{{ __('file.Net Total') }}</label>
                                    <input type="text" class="form-control bg-primary text-center" name="net_total"
                                        id="net_total" readonly>
                                </div>
                                <div class="form-group col-md-3 required">
                                    <label for="paid_amount">{{ __('file.Paid Amount') }}</label>
                                    <input type="text" class="form-control text-center" name="paid_amount"
                                        id="paid_amount">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="due_amount">{{ __('file.Due Amount') }}</label>
                                    <input type="text" class="form-control bg-primary text-center" name="due_amount"
                                        id="due_amount" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="transport_name">{{ __('file.Transport Name') }}</label>
                                    <input type="text" class="form-control" name="transport_name"
                                        id="transport_name">
                                </div>
                                <div class="col-md-12">
                                    <hr style="border-top: 5px dotted cadetblue;" />
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="purchaseTableAppend">
                                            <table class="table">
                                                <tbody>
                                                    <tr class="text-center">
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Company') }}</button>
                                                        </th>
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Category') }}</button>
                                                        </th>
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Product') }}</button>
                                                        </th>
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Unit') }}</button>
                                                        </th>
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Qty') }}</button>
                                                        </th>
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Scale') }}</button>
                                                        </th>
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Rec Qty') }}</button>
                                                        </th>
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Action') }}</button>
                                                        </th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <td>
                                                            <select
                                                                class="form-control selectpicker text-center labor_warehouse_id"
                                                                id="purchase_0_warehouse_id"
                                                                name="purchase[0][warehouse_id]" index_no="0"
                                                                data-live-search = "true">
                                                                <option value="">{{ __('Please Select') }}</option>
                                                                @foreach ($warehouses as $warehouse)
                                                                    <option value="{{ $warehouse->id }}"
                                                                        labour_load_unload_head="{{ $warehouse->labour_load_unload_head->rate ?? 0 }}">
                                                                        {{ $warehouse->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control selectpicker category text-center"
                                                                id="purchase_0_category_id"
                                                                data-product_id="purchase_0_product_id"
                                                                data-live-search = "true">
                                                                <option value="">{{ __('Please Select') }}</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->category_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><select class="form-control selectpicker product text-center"
                                                                id="purchase_0_product_id" name="purchase[0][product_id]"
                                                                data-unit_show="purchase_0_unit_show"
                                                                data-unit_id="purchase_0_unit_id"
                                                                data-price="purchase_0_price"
                                                                data-live-search = "true"></select></td>
                                                        <td><input class="form-control bg-primary text-center"
                                                                id="purchase_0_unit_show" readonly /><input type="hidden"
                                                                id="purchase_0_unit_id" /></td>
                                                        <td><input class="form-control qty text-center"
                                                                id="purchase_0_qty" name="purchase[0][qty]"
                                                                data-product_id="purchase_0_product_id"
                                                                data-unit_id="purchase_0_unit_id"
                                                                data-scale="purchase_0_scale"
                                                                data-price="purchase_0_price"
                                                                data-sub_total="purchase_0_sub_total" /></td>
                                                        <td><input class="form-control scale text-center"
                                                                id="purchase_0_scale" name="purchase[0][scale]"
                                                                data-product_id="purchase_0_product_id"
                                                                data-unit_id="purchase_0_unit_id"
                                                                data-qty="purchase_0_qty" data-price="purchase_0_price"
                                                                data-sub_total="purchase_0_sub_total" /> </td>
                                                        {{-- receive qty --}}
                                                        <td>
                                                            <input class="form-control recQty" id="purchase_0_rec_qty"
                                                                name="purchase[0][rec_qty]"
                                                                data-load_unload_rate="purchase_0_load_unload_rate"
                                                                data-load_unload_amount="purchase_0_load_unload_amount" />
                                                        </td>

                                                        <th rowspan="3">
                                                            <button type = "button"
                                                                class="btn btn-primary btn-sm addRaw"><i
                                                                    class="fas fa-plus-circle"></i></button><br />
                                                            <button type = "button"
                                                                class = "btn btn-danger btn-sm deleteRaw"
                                                                style="margin-top:3px"><i
                                                                    class = "fas fa-minus-circle"></i></button>
                                                        </th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Price') }}</button>
                                                        </th>
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Sub Total') }}</button>
                                                        </th>
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Load Unload Rate') }}</button>
                                                        </th>
                                                        <th><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Load Unload Amount') }}</button>
                                                        </th>
                                                        <th colspan="3"><button type="button"
                                                                class="btn btn-primary btn-block">{{ __('file.Note') }}</button>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td><input class="form-control price text-center"
                                                                id="purchase_0_price" name="purchase[0][price]"
                                                                data-product_id="purchase_0_product_id"
                                                                data-qty="purchase_0_qty"
                                                                data-sub_total="purchase_0_sub_total" />
                                                        </td>
                                                        <td>
                                                            <input class="form-control bg-primary sub_total text-center"
                                                                id="purchase_0_sub_total" name="purchase[0][sub_total]"
                                                                readonly />
                                                        </td>
                                                        <td>
                                                            <input
                                                                class="form-control bg-primary load_unload_rate text-center"
                                                                id="purchase_0_load_unload_rate"
                                                                name="purchase[0][load_unload_rate]" readonly />
                                                        </td>
                                                        <td>
                                                            <input
                                                                class="form-control bg-primary load_unload_amount text-center"
                                                                id="purchase_0_load_unload_amount"
                                                                name="purchase[0][load_unload_amount]" readonly />
                                                        </td>
                                                        <td colspan="3"><input class="form-control text-center"
                                                                id="purchase_0_note" name="purchase[0][note]" /> </td>
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
                                            <td><button type="button"
                                                    class="btn btn-primary btn-block">{{ __('file.Total Load Unload') }}</button>
                                            </td>
                                            <td><input type="text" class="form-control bg-primary text-center"
                                                    id="total_load_unload" name="total_load_unload" readonly /></td>
                                        </tr>
                                        <tr>
                                            <td><button type="button"
                                                    class="btn btn-primary btn-block">{{ __('file.Total Quantity') }}</button>
                                            </td>
                                            <td><input type="text" class="form-control bg-primary text-center"
                                                    id="total_purchase_qty" name="total_purchase_qty" readonly /></td>
                                        </tr>
                                        <tr>
                                            <td><button type="button"
                                                    class="btn btn-primary btn-block">{{ __('file.Total Price') }}</button>
                                            </td>
                                            <td><input type="text" class="form-control bg-primary text-center"
                                                    id="total_purchase_sub_total" name="total_purchase_sub_total"
                                                    readonly /></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group col-md-12 text-center pt-5">
                                    <a class = "btn btn-danger btn-sm mr-3" href="{{ route('purchase.add') }}"><i
                                            class="fas fa-sync-alt"></i>{{ __('file.Reset') }}</a>
                                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn"
                                        onclick="storeData()"><i class="fas fa-save"></i>{{ __('file.Save') }}</button>
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

        function _(x) {
            return document.getElementById(x);
        }

        function partyType() {
            let partyType = $('#party_type').find(":selected").val();
            if (partyType == 1) {
                _('party_id_show').style.display = 'block';
                _('party_name_show').style.display = 'none';
                $('#party_id').prop('disabled', false);
                $('#party_name').prop('disabled', true);
            } else if (partyType == 2) {
                _('party_id_show').style.display = 'none';
                _('party_name_show').style.display = 'block';
                $('#party_id').prop('disabled', true);
                $('#party_name').prop('disabled', false);
            } else {
                _('party_id_show').style.display = 'none';
                _('party_name_show').style.display = 'none';
                $('#party_id').prop('disabled', true);
                $('#party_name').prop('disabled', true);
            }
        }

        $(document).on('change', '.category', function() {
            let html;
            let categoryId = $(this).find(":selected").val();
            let productId = $(this).data('product_id');
            $('#' + productId + '').empty();
            $('.selectpicker').selectpicker('refresh');
            if (categoryId != '') {
                $.ajax({
                    url: "{{ url('purchase-category-product') }}/" + categoryId,
                    method: 'GET',
                    success: function(data) {
                        if (data != '') {
                            html = `<option value="">Select Please</option>`;
                            $.each(data, function(key, value) {
                                html += '<option value="' + value.id + '">' + value
                                    .product_name + '</option>';
                            });
                            $('#' + productId + '').append(html);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                });
            }
        });

        $(document).on('change', '.product', function() {
            let productId = $(this).find(":selected").val();
            let unitId = $(this).data('unit_id');
            let unitShow = $(this).data('unit_show');
            let price = $(this).data('price');
            if (productId != '') {
                $.ajax({
                    url: "{{ url('purchase-product-details') }}/" + productId,
                    method: 'GET',
                    success: function(data) {
                        if (data) {
                            $('#' + unitId + '').val(data.unit.unit_name);
                            $('#' + unitShow + '').val(data.unit.unit_name + '(' + data.unit.unit_code +
                                ')');
                            $('#' + price + '').val(data.purchase_price);
                        }
                    }
                });
            }
        });

        $(document).on('input', '.qty', function() {
            let productId = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let unitId = $(this).data('unit_id');
            let scale = $(this).data('scale');
            let price = $(this).data('price');
            let subTotal = $(this).data('sub_total');
            if (productId != '') {
                _(scale).value = $(this).val() * _(unitId).value;
                _(subTotal).value = $(this).val() * _(price).value;
            } else {
                $(this).val('');
                _(scale).value = '';
                _(subTotal).value = '';
                notification('error', '{{ __('file.Please Select Product') }}');
            }
            calculation();
        });

        $(document).on('input', '.scale', function() {
            let productId = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let unitId = $(this).data('unit_id');
            let qty = $(this).data('qty');
            let price = $(this).data('price');
            let subTotal = $(this).data('sub_total');
            if (productId != '') {
                _(qty).value = $(this).val() / _(unitId).value;
                _(subTotal).value = $(this).val() / _(unitId).value * _(price).value;
            } else {
                $(this).val('');
                _(qty).value = '';
                _(subTotal).value = '';
                notification('error', '{{ __('file.Please Select Product') }}');
            }
            calculation();
        });

        $(document).on('input', '.price', function() {
            let productId = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let qty = $(this).data('qty');
            let subTotal = $(this).data('sub_total');
            if (productId != '') {
                $('#' + subTotal + '').val($(this).val() * $('#' + qty + '').val());
            } else {
                $(this).val('');
                notification('error', '{{ __('file.Please Select Product') }}');
            }
            calculation();
        });

        $(document).on('input', '.recQty', function() {
            let load_unload_rate = $(this).data('load_unload_rate');
            let load_unload_amount = $(this).data('load_unload_amount');
            let receive_qty = $(this).val();

            _(load_unload_amount).value = _(load_unload_rate).value * receive_qty;

            calculation();
        });

        $(document).on('click', '.addRaw', function() {
            let html;
            html =
                `<table class="table">
                       <tbody>
                           <tr class="text-center" style="border-top: 2px solid cadetblue;">
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Company') }}</button></th>
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Category') }}</button></th>
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Product') }}</button></th>
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Unit') }}</button></th>
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Qty') }}</button></th>
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Scale') }}</button></th>
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Rec Qty') }}</button></th>
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Action') }}</button></th>
                           </tr>
                           <tr class="text-center">
                               <td>
                                 <select class="form-control selectpicker text-center labor_warehouse_id" id="purchase_` +
                i +
                `_warehouse_id" name="purchase[` + i + `][warehouse_id]" index_no="` + i + `"  data-live-search = "true">
                                 <option value="">{{ __('Please Select') }}</option>
                                 @foreach ($warehouses as $warehouse)
                                 <option value="{{ $warehouse->id }}" labour_load_unload_head="{{ $warehouse->labour_load_unload_head->rate ?? 0 }}">{{ $warehouse->name }}</option>
                                 @endforeach
                                 </select>
                               </td>
                               
                               <td>
                                 <select class="form-control selectpicker category text-center" id="purchase_` + i +
                `_category_id" data-product_id="purchase_` + i + `_product_id" data-live-search = "true">
                                 <option value="">{{ __('Please Select') }}</option>
                                 @foreach ($categories as $category)
                                 <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                 @endforeach
                                 </select>
                               </td>
                               <td><select class="form-control selectpicker product text-center" id="purchase_` + i +
                `_product_id" name="purchase[` + i + `][product_id]" data-unit_show="purchase_` + i +
                `_unit_show" data-unit_id="purchase_` + i + `_unit_id" data-price="purchase_` + i + `_price" data-live-search = "true"></select></td>
                               <td><input class="form-control bg-primary text-center" id="purchase_` + i +
                `_unit_show" readonly/><input type="hidden" id="purchase_` + i + `_unit_id"/></td>
                               <td><input class="form-control qty text-center" id="purchase_` + i +
                `_qty" name="purchase[` + i + `][qty]" data-product_id="purchase_` + i +
                `_product_id" data-unit_id="purchase_` + i + `_unit_id" data-scale="purchase_` + i +
                `_scale" data-price="purchase_` + i + `_price" data-sub_total="purchase_` + i + `_sub_total"/></td>
                               <td><input class="form-control scale text-center" id="purchase_` + i +
                `_scale" name="purchase[` + i + `][scale]" data-product_id="purchase_` + i +
                `_product_id" data-unit_id="purchase_` + i + `_unit_id" data-qty="purchase_` + i +
                `_qty" data-price="purchase_` + i + `_price" data-sub_total="purchase_` + i + `_sub_total"/> </td>
                               <td><input class="form-control recQty text-center" id="purchase_` + i +
                `_rec_qty" name="purchase[` + i + `][rec_qty]" data-load_unload_rate="purchase_` + i + `_load_unload_rate"
                                                                data-load_unload_amount="purchase_` + i + `_load_unload_amount" /></td>
                               <th  rowspan="3">
                                 <button type = "button" class="btn btn-primary btn-sm addRaw text-center"><i class="fas fa-plus-circle"></i></button><br/>
                                 <button type = "button" class = "btn btn-danger btn-sm deleteRaw text-center" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button>
                               </th>
                           </tr>
                           <tr class="text-center">
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Price') }}</button></th>
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Sub Total') }}</button></th>
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Load Unload Rate') }}</button></th>
                               <th><button type="button" class="btn btn-primary btn-block">{{ __('file.Load Unload Amount') }}</button></th>
                               <th colspan="4"><button type="button" class="btn btn-primary btn-block">{{ __('file.Note') }}</button></th>
                           </tr>
                           <tr>
                               <td><input class="form-control price text-center" id="purchase_` + i +
                `_price" name="purchase[` + i + `][price]" data-product_id="purchase_` + i +
                `_product_id" data-qty="purchase_` + i + `_qty" data-sub_total="purchase_` + i + `_sub_total"/> </td>
                               
                <td>
                    <input class="form-control bg-primary sub_total text-center" id="purchase_` + i +
                `_sub_total" name="purchase[` + i + `][sub_total]" readonly/>
                 </td>
                               
                <td>
                    <input class="form-control bg-primary load_unload_rate text-center" id="purchase_` + i +
                `_load_unload_rate" name="purchase[` + i + `][load_unload_rate]" readonly/>
                 </td>
                 <td>
                 <input class="form-control bg-primary load_unload_amount text-center" id="purchase_` + i +
                `_load_unload_amount" name="purchase[` + i + `][load_unload_amount]" readonly />
                 </td>     
                <td colspan="4"><input class="form-control text-center" id="purchase_` + i +
                `_note" name="purchase[` + i + `][note]"/> </td>
                           </tr>
                       </tbody>
                   </table>`;
            $('#purchaseTableAppend').append(html);
            $('.selectpicker').selectpicker('refresh');
            calculation();
            i++;
        });

        $(document).on('change', '.labor_warehouse_id', function() {
            let index_no = $(this).attr("index_no");
            if (index_no) {
                console.log("index_no: " + index_no);

                let selectedOption = $(this).find(':selected');
                let rate = selectedOption.attr('labour_load_unload_head') || 0;
                let rateInput = $('#purchase_' + index_no + '_load_unload_rate');

                console.log("rateInput: " + rateInput);

                rateInput.val(rate);
            }
        });

        $(document).on('click', '.deleteRaw', function() {
            $(this).parent().parent().parent().remove();
            calculation();
        });

        function partyDue() {
            let partyId = $('#party_id').find('option:selected').val();
            if (partyId != '') {
                $.ajax({
                    url: "{{ url('party/due') }}/" + partyId,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        if (data > 0) {
                            _('previous_due').value = -Math.abs(data);
                            _('previous_due_status').innerHTML =
                                '<span class = "text-success">{{ __('file.Your Receivable Old Outstanding') }}</span>';
                        } else {
                            _('previous_due').value = Math.abs(data);
                            _('previous_due_status').innerHTML =
                                '<span class = "text-danger">{{ __('file.Your Payable Old Outstanding') }}</span>';
                        }
                        calculation();
                    }
                })
            } else {
                notification('error', 'Party Not Selected');
            }
            calculation();
        }

        function paymentStatus() {
            let paymentStatus = $('#payment_status').find('option:selected').val();
            if (paymentStatus == 1) {
                $('#paid_amount').addClass('bg-primary');
                $('#paid_amount').prop('readonly', true);
                $('#payment_method').removeClass('bg-primary');
                $('#payment_method').prop("disabled", false);
                $('#account_id').removeClass('bg-primary');
                $('#account_id').prop("disabled", false);
                _('paid_amount').value = _('net_total').value;
                calculation();
            } else if (paymentStatus == 3) {
                $('#paid_amount').addClass('bg-primary');
                $('#paid_amount').prop('readonly', true);
                $('#payment_method').addClass('bg-primary');
                $('#payment_method').prop("disabled", true);
                $('#account_id').addClass('bg-primary');
                $('#account_id').prop("disabled", true);
                _('paid_amount').value = '';
                calculation();
            } else {
                $('#paid_amount').removeClass('bg-primary');
                $('#paid_amount').prop('readonly', false);
                $('#payment_method').removeClass('bg-primary');
                $('#payment_method').prop("disabled", false);
                $('#account_id').removeClass('bg-primary');
                $('#account_id').prop("disabled", false);
                _('paid_amount').value = '';
                calculation();
            }
        }

        function paymentMethod() {
            let paymentMethod = $('#payment_method').find('option:selected').val();
            if (paymentMethod != '') {
                $.ajax({
                    url: "{{ url('account-list') }}/" + paymentMethod,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(data) {
                        console.log(data);
                        if (data != '') {
                            html = `<option value="">Select Please</option>`;
                            $.each(data, function(key, value) {
                                html += '<option value="' + value.id + '">' + value.name + '</option>';
                            });
                            $('#account_id').empty();
                            $('#account_id').append(html);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                })
            }
        }

        $(document).on('input', '#discount,#paid_amount', function() {
            calculation();
        })

        function calculation() {
            let qty = 0;
            let subTotal = 0;
            let paymentStatus = $('#payment_status').find('option:selected').val();
            $('.recQty').each(function() {
                if ($(this).val() == '') {
                    qty += +0;
                } else {
                    qty += +$(this).val();
                }
            });
            
            // load unload
            let total_load_unload = 0;
            $('.load_unload_amount').each(function() {
                if ($(this).val() == '') {
                    total_load_unload += +0;
                } else {
                    total_load_unload += +$(this).val();
                }
            });
            _('total_load_unload').value = total_load_unload;

            $('.sub_total').each(function() {
                if ($(this).val() == '') {
                    subTotal += +0;
                } else {
                    subTotal += +$(this).val();
                }
            });
            if (paymentStatus == 1) {
                _('total_purchase_qty').value = qty;
                _('total_purchase_sub_total').value = subTotal - _('discount').value;
                _('net_total').value = +_('previous_due').value + +subTotal - _('discount').value;
                _('paid_amount').value = +_('previous_due').value + +subTotal - _('discount').value;
                _('due_amount').value = +_('previous_due').value + +subTotal - _('discount').value - _('paid_amount').value;
            } else {
                _('total_purchase_qty').value = qty;
                _('total_purchase_sub_total').value = subTotal - _('discount').value;
                _('net_total').value = +_('previous_due').value + +subTotal - _('discount').value;
                _('due_amount').value = +_('previous_due').value + +subTotal - _('discount').value - _('paid_amount').value;
            }
        }

        function storeData() {
            let form = document.getElementById('purchase_store_form');
            let formData = new FormData(form);
            let url = "{{ route('purchase.store') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $('#save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete: function() {
                    $('#save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success: function(data) {
                    $('#purchase_store_form').find('.is-invalid').removeClass('is-invalid');
                    $('#purchase_store_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function(key, value) {
                            var key = key.split('.').join('_');
                            $('#purchase_store_form input#' + key).addClass('is-invalid');
                            $('#purchase_store_form textarea#' + key).addClass('is-invalid');
                            $('#purchase_store_form select#' + key).parent().addClass('is-invalid');
                            $('#purchase_store_form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            window.location.replace("{{ route('purchase') }}");
                        }
                    }
                },
                error: function(xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    </script>
@endpush
