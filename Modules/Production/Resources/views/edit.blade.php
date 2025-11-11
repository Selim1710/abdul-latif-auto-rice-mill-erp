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
                    @if (permission('production-access'))
                        <div class="card-toolbar"><a href="{{ route('production') }}"
                                class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i>
                                {{ __('file.Back') }}</a></div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form action="" id="production_store_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" id="update_id" name="update_id" value="{{ $edit->id }}" />
                                <div class="form-group col-md-4 required">
                                    <label for="invoice_no">{{ __('file.Invoice No') }}</label>
                                    <input type="text" class="form-control bg-primary text-center" id="invoice_no"
                                        name="invoice_no" value="{{ $edit->invoice_no }}" readonly />
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="batch_no">{{ __('file.Batch No') }}</label>
                                    <input type="text" class="form-control" id="batch_no" name="batch_no"
                                        value="{{ $edit->batch_no }}" readonly />
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="production_type">{{ __('file.Production Type') }}</label>
                                    <input type="text" class="form-control" id="production_type" name="production_type"
                                        value="{{ $edit->production_type }}" />
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="mill_id">{{ __('file.Mill') }}</label>
                                    <select class="form-control selectpicker" id="mill_id" name="mill_id"
                                        data-live-search="true">
                                        <option value="">{{ __('file.Please Select') }}</option>
                                        @foreach ($mills as $mill)
                                            <option value="{{ $mill->id }}"
                                                @if ($edit->mill_id == $mill->id) selected="selected" @endif>
                                                {{ $mill->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="date">{{ __('file.Date') }}</label>
                                    <input type="date" class="form-control date" id="date" name="date"
                                        value="{{ $edit->date }}" />
                                </div>
                                <div class="col-md-12">
                                    <hr style="border-top: 5px dotted cadetblue;" />
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <table class="table" id="productionTable">
                                            @if (isset($edit->productionRawProductList))
                                                @foreach ($edit->productionRawProductList as $key => $item)
                                                    <tbody>
                                                        @if ($key == 1)
                                                            <tr>
                                                                <th colspan="7">
                                                                    <hr class="bg-danger" />
                                                                </th>
                                                            </tr>
                                                        @endif
                                                        <tr class="bg-primary text-center">
                                                            <th>{{ __('file.Company') }}</th>
                                                            <th>{{ __('file.Party') }}</th>
                                                            <th>{{ __('file.Product') }}</th>
                                                            <th colspan="3">{{ __('file.Purchase Invoice') }}</th>
                                                            <th>{{ __('file.Unit') }}</th>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <select
                                                                    class="form-control selectpicker text-center labor_warehouse_id"
                                                                    id="production_{{ $key }}_warehouse_id"
                                                                    name="production[{{ $key }}][warehouse_id]"
                                                                    index_no="{{ $key }}"
                                                                    data-live-search = "true">
                                                                    <option value="">{{ __('Please Select') }}
                                                                    </option>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        <option value="{{ $warehouse->id }}"
                                                                            @if (!empty($item->warehouse_id) && $item->warehouse_id == $warehouse->id) Selected @endif
                                                                            labour_load_unload_head="{{ $warehouse->labour_load_unload_head->rate ?? 0 }}"
                                                                            labour_cutting_head="{{ $warehouse->labour_cutting_head->rate ?? 0 }}">
                                                                            {{ $warehouse->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-control selectpicker party text-center"
                                                                    id="production_{{ $key }}_party_id"
                                                                    name="production[{{ $key }}][party_id]"
                                                                    data-warehouse_id="production_{{ $key }}_warehouse_id"
                                                                    data-product_id="production_{{ $key }}_product_id"
                                                                    data-live-search = "true">
                                                                    <option value="">{{ __('Please Select') }}
                                                                    </option>
                                                                    @foreach ($parties as $party)
                                                                        <option value="{{ $party->id }}"
                                                                            @if (!empty($item->party_id) && $item->party_id == $party->id) Selected @endif>
                                                                            {{ $party->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select
                                                                    class="form-control selectpicker product text-center"
                                                                    id="production_{{ $key }}_product_id"
                                                                    name="production[{{ $key }}][product_id]"
                                                                    data-party_id="production_{{ $key }}_party_id"
                                                                    data-warehouse_id="production_{{ $key }}_warehouse_id"
                                                                    data-purchase_id="production_{{ $key }}_purchase_id"
                                                                    data-price="production_{{ $key }}_price"
                                                                    data-unit_show="production_{{ $key }}_unit_show"
                                                                    data-unit_id="production_{{ $key }}_unit_id"
                                                                    data-available_qty="production_{{ $key }}_available_qty"
                                                                    data-live-search = "true">
                                                                    <option value = "{{ $item->product_id }}">
                                                                        {{ $item->product->product_name }}</option>
                                                                </select>
                                                                <input type="hidden"
                                                                    id = "production_{{ $key }}_price"
                                                                    name = "production[{{ $key }}][price]" />
                                                            </td>

                                                            {{-- purchase invoice --}}
                                                            <td colspan="3">
                                                                <select
                                                                    class="form-control selectpicker purchase_id text-center"
                                                                    onchange="purchase_warehouse_stock(this)"
                                                                    id="production_{{ $key }}_purchase_id"
                                                                    name="production[{{ $key }}][purchase_id]"
                                                                    data-party_id="production_{{ $key }}_party_id"
                                                                    data-warehouse_id="production_{{ $key }}_warehouse_id"
                                                                    data-product_id="production_{{ $key }}_product_id"
                                                                    data-available_qty="production_{{ $key }}_available_qty"
                                                                    data-live-search = "true">
                                                                    <option value = "{{ $item->purchase_id ?? '' }}">
                                                                        {{ $item->purchase->invoice_no ?? '' }}</option>
                                                                </select>
                                                            </td>

                                                            <td><input class="form-control bg-primary text-center"
                                                                    id="production_{{ $key }}_unit_show"
                                                                    value="{{ $item->product->unit->unit_name . '(' . $item->product->unit->unit_code . ')' }}"
                                                                    readonly /><input type="hidden"
                                                                    id="production_{{ $key }}_unit_id"
                                                                    value="{{ $item->product->unit->unit_name }}" /></td>

                                                        </tr>
                                                        <tr class="bg-primary text-center">
                                                            <th>{{ __('file.Available Qty') }}</th>
                                                            <th>{{ __('file.Qty') }}</th>
                                                            <th>{{ __('file.Scale') }}</th>
                                                            <th>{{ __('file.Pro Qty') }}</th>
                                                            <th>{{ __('file.Load Unload Rate') }}</th>
                                                            <th>{{ __('file.Load Unload Amount') }}</th>
                                                            <th>{{ __('file.Action') }}</th>
                                                        </tr>
                                                        <tr>
                                                            <td><input
                                                                    class="form-control bg-primary available_qty text-center"
                                                                    id="production_{{ $key }}_available_qty"
                                                                    value="{{ $item->availableQty($item->party_id, $item->warehouse_id, $item->purchase_id, $item->product_id)->qty ?? 0 }}"
                                                                    readonly /></td>

                                                            <td><input class="form-control qty text-center"
                                                                    id="production_{{ $key }}_qty"
                                                                    name="production[{{ $key }}][qty]"
                                                                    value="{{ $item->qty }}"
                                                                    data-product_id="production_{{ $key }}_product_id"
                                                                    data-unit_id="production_{{ $key }}_unit_id"
                                                                    data-available_qty="production_{{ $key }}_available_qty"
                                                                    data-scale="production_{{ $key }}_scale" />
                                                            </td>
                                                            <td><input class="form-control scale text-center"
                                                                    id="production_{{ $key }}_scale"
                                                                    name="production[{{ $key }}][scale]"
                                                                    value="{{ $item->scale }}"
                                                                    data-product_id="production_{{ $key }}_product_id"
                                                                    data-unit_id="production_{{ $key }}_unit_id"
                                                                    data-available_qty="production_{{ $key }}_available_qty"
                                                                    data-qty="production_{{ $key }}_qty" /> </td>
                                                            <td><input class="form-control proQty text-center"
                                                                    id="production_{{ $key }}_pro_qty"
                                                                    name="production[{{ $key }}][pro_qty]"
                                                                    value="{{ $item->pro_qty }}"
                                                                    data-available_qty="production_{{ $key }}_available_qty"
                                                                    data-load_unload_rate="production_{{ $key }}_load_unload_rate"
                                                                    data-load_unload_amount="production_{{ $key }}_load_unload_amount" />
                                                            </td>

                                                            <td>
                                                                <input
                                                                    class="form-control bg-primary load_unload_rate text-center"
                                                                    id="production_{{ $key }}_load_unload_rate"
                                                                    name="production[{{ $key }}][load_unload_rate]"
                                                                    value="{{ $item->load_unload_rate }}"
                                                                    readonly />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    class="form-control bg-primary load_unload_amount text-center"
                                                                    id="production_{{ $key }}_load_unload_amount"
                                                                    name="production[{{ $key }}][load_unload_amount]"
                                                                    value="{{ $item->load_unload_amount }}"
                                                                    readonly />
                                                            </td>

                                                            <td class="text-center">
                                                                <div class="btn-group">
                                                                    <button type="button"
                                                                        class="btn btn-primary addRaw"><i
                                                                            class="fas fa-plus-circle"></i></button><br />
                                                                    <button type = "button"
                                                                        class="btn btn-danger deleteRaw"><i
                                                                            class = "fas fa-minus-circle"></i></button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                @endforeach
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 text-center pt-5">
                                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn"
                                        onclick="updateData()"><i
                                            class="fas fa-save"></i>{{ __('file.Update') }}</button>
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
        let i = {{ count($edit->productionRawProductList) }} + 1;

        function _(x) {
            return document.getElementById(x);
        }

        $(document).on('change', '.party', function() {
            let html;
            let warehouseId = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let partyId = $(this).find(":selected").val();
            let productId = $(this).data('product_id');
            $('#' + productId + '').empty();
            $('.selectpicker').selectpicker('refresh');
            if (warehouseId != '' && partyId != '') {
                $.ajax({
                    url: "{{ route('party.product') }}",
                    data: {
                        warehouseId: warehouseId,
                        partyId: partyId,
                    },
                    method: 'GET',
                    success: function(data) {
                        if (data != '') {
                            html = `<option value="">Select Please</option>`;
                            $.each(data, function(key, value) {
                                html += '<option value="' + value.product.id + '">' + value
                                    .product.product_name + '</option>';
                            });
                            $('#' + productId + '').html(html);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                });
            } else {
                notification('error', 'Warehouse Or Party Not Selected');
            }
        });

        $(document).on('change', '.product', function() {
            let party_id = $('#' + $(this).data('party_id') + '').find(":selected").val();
            let warehouse_id = $('#' + $(this).data('warehouse_id') + '').find(":selected").val();
            let product_id = $(this).find(":selected").val();
            let purchase_id = $(this).data('purchase_id');

            let unitId = $(this).data('unit_id');
            let unitShow = $(this).data('unit_show');

            if (product_id != '') {
                $.ajax({
                    url: "{{ route('party.wise.product.purchase.invoice') }}",
                    data: {
                        party_id: party_id,
                        warehouse_id: warehouse_id,
                        product_id: product_id,
                    },
                    method: 'GET',
                    success: function(data) {
                        let unit_id = 0;
                        let unit_show = 0;
                        if (data != '') {
                            html = `<option value="">Select Please</option>`;
                            $.each(data, function(key, value) {
                                html += '<option value="' + value.purchase.id + '">' + value
                                    .purchase.invoice_no + '</option>';

                                // unit
                                unit_id = value.product.unit.unit_name;
                                unit_show = value.product.unit.unit_name + value.product.unit
                                    .unit_code;

                            });
                            $('#' + purchase_id + '').html(html);

                            $('#' + unitId + '').val(unit_id);
                            $('#' + unitShow + '').val(unit_show);


                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                });
            } else {
                notification('error', 'Product Not Selected')
            }
        });

        function purchase_warehouse_stock(el) {
            let purchase_id = $(el).find(":selected").val();
            let party_id = $('#' + $(el).data('party_id') + '').find(":selected").val();
            let warehouse_id = $('#' + $(el).data('warehouse_id') + '').find(":selected").val();
            let product_id = $('#' + $(el).data('product_id') + '').find(":selected").val();

            // console.log('purchase_id : ' + purchase_id);

            let availableQty = $(el).data('available_qty');
            if (purchase_id != '') {
                $.ajax({
                    url: "{{ route('available-product') }}",
                    data: {
                        purchase_id: purchase_id,
                        party_id: party_id,
                        warehouse_id: warehouse_id,
                        product_id: product_id,
                    },
                    method: 'GET',
                    success: function(data) {
                        console.log('data : ' + data);
                        if (data) {
                            $('#' + availableQty + '').val(data.qty);
                        }
                    }
                });
            } else {
                notification('error', 'Product Not Selected')
            }
        }


        $(document).on('input', '.qty', function() {
            let productId = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let availableQty = $(this).data('available_qty');
            let unitId = $(this).data('unit_id');
            let scale = $(this).data('scale');
            if (productId == '') {
                $(this).val('');
                notification('error', 'Price Or Product Not Selected');
                return;
            }
            if (parseFloat($(this).val()) > parseFloat(_(availableQty).value)) {
                $(this).val('');
                _(scale).value = '';
                notification('error', 'Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
            _(scale).value = $(this).val() * _(unitId).value;
        });
        $(document).on('input', '.scale', function() {
            let productId = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let availableQty = $(this).data('available_qty');
            let unitId = $(this).data('unit_id');
            let qty = $(this).data('qty');
            if (productId == '') {
                $(this).val('');
                _(qty).value = '';
                notification('error', 'Price Or Product Not Selected');
                return;
            }
            _(qty).value = $(this).val() / _(unitId).value;
            if (parseFloat(_(qty).value) > parseFloat(_(availableQty).value)) {
                $(this).val('');
                _(qty).value = '';
                notification('error', 'Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }
        });
        $(document).on('input', '.proQty', function() {
            let availableQty = $(this).data('available_qty');
            if (parseFloat($(this).val()) > parseFloat(_(availableQty).value)) {
                $(this).val('');
                notification('error', 'Quantity Can\'t Be Greater Then Stock Quantity');
                return;
            }

            let receive_qty = $(this).val();

            let load_unload_rate = $(this).data('load_unload_rate');
            let load_unload_amount = $(this).data('load_unload_amount');

            _(load_unload_amount).value = _(load_unload_rate).value * receive_qty;

            // let cutting_rate = $(this).data('cutting_rate');
            // let cutting_amount = $(this).data('cutting_amount');
            // _(cutting_amount).value = _(cutting_rate).value * receive_qty;

        });

        $(document).on('change', '.labor_warehouse_id', function() {
            let index_no = $(this).attr("index_no");
            if (index_no) {
                // console.log("index_no: " + index_no);

                let selectedOption = $(this).find(':selected');
                let load_unload_rate = selectedOption.attr('labour_load_unload_head') || 0;
                $('#production_' + index_no + '_load_unload_rate').val(load_unload_rate);

                let cutting_rate = selectedOption.attr('labour_cutting_head') || 0;
                $('#production_' + index_no + '_cutting_rate').val(cutting_rate);
            }
        });


        $(document).on('click', '.addRaw', function() {
            let html;
            html = `
            
            <tbody>
                <tr><th colspan="7"><hr class="bg-danger" /></th></tr>
                <tr class="bg-primary text-center">
                    <th>{{ __('file.Company') }}</th>
                    <th>{{ __('file.Party') }}</th>
                    <th>{{ __('file.Product') }}</th>
                    <th colspan="3">{{ __('file.Purchase Invoice') }}</th>
                    <th>{{ __('file.Unit') }}</th>
                </tr>
                <tr>
                    <td>
                        <select
                            class="form-control selectpicker text-center labor_warehouse_id"
                            id="production_` + i + `_warehouse_id"
                            name="production[` + i + `][warehouse_id]" index_no="` + i + `"
                            data-live-search = "true">
                            <option value="">{{ __('Please Select') }}</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}"
                                    labour_load_unload_head="{{ $warehouse->labour_load_unload_head->rate ?? 0 }}"
                                    labour_cutting_head="{{ $warehouse->labour_cutting_head->rate ?? 0 }}">
                                    {{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control selectpicker party text-center"
                            id="production_` + i + `_party_id"
                            name="production[` + i + `][party_id]"
                            data-warehouse_id="production_` + i + `_warehouse_id"
                            data-product_id="production_` + i + `_product_id"
                            data-live-search = "true">
                            <option value="">{{ __('Please Select') }}</option>
                            @foreach ($parties as $party)
                                <option value="{{ $party->id }}">
                                    {{ $party->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control selectpicker product text-center"
                            id="production_` + i + `_product_id" name="production[` + i + `][product_id]"
                            data-party_id="production_` + i + `_party_id"
                            data-warehouse_id="production_` + i + `_warehouse_id"
                            data-purchase_id="production_` + i + `_purchase_id"
                            data-price="production_` + i + `_price"
                            data-unit_show="production_` + i + `_unit_show"
                            data-unit_id="production_` + i + `_unit_id"
                            data-available_qty="production_` + i + `_available_qty"
                            data-live-search = "true">
                        </select>
                        <input type="hidden" id = "production_` + i + `_price"
                            name = "production[` + i + `][price]" />
                    </td>

                    {{-- purchase invoice --}}
                    <td colspan="3">
                        <select class="form-control selectpicker purchase_id text-center"
                            onchange="purchase_warehouse_stock(this)"
                            id="production_` + i + `_purchase_id"
                            name="production[` + i + `][purchase_id]"
                            data-party_id="production_` + i + `_party_id"
                            data-warehouse_id="production_` + i + `_warehouse_id"
                            data-product_id="production_` + i + `_product_id"
                            data-available_qty="production_` + i + `_available_qty"
                            data-live-search = "true">
                        </select>
                    </td>


                    <td><input class="form-control bg-primary text-center"
                            id="production_` + i + `_unit_show" readonly /><input type="hidden"
                            id="production_` + i + `_unit_id" /></td>

                </tr>
                <tr class="bg-primary text-center">
                    <th>{{ __('file.Available Qty') }}</th>
                    <th>{{ __('file.Qty') }}</th>
                    <th>{{ __('file.Scale') }}</th>
                    <th>{{ __('file.Pro Qty') }}</th>
                    <th>{{ __('file.Load Unload Rate') }}</th>
                    <th>{{ __('file.Load Unload Amount') }}</th>
                    <th>{{ __('file.Action') }}</th>
                </tr>
                <tr>
                    <td><input class="form-control bg-primary available_qty text-center"
                            id="production_` + i + `_available_qty" readonly /></td>

                    <td><input class="form-control qty text-center" id="production_` + i + `_qty"
                            name="production[` + i + `][qty]"
                            data-product_id="production_` + i + `_product_id"
                            data-unit_id="production_` + i + `_unit_id"
                            data-available_qty="production_` + i + `_available_qty"
                            data-scale="production_` + i + `_scale" /></td>
                    <td><input class="form-control scale text-center"
                            id="production_` + i + `_scale" name="production[` + i + `][scale]"
                            data-product_id="production_` + i + `_product_id"
                            data-unit_id="production_` + i + `_unit_id"
                            data-available_qty="production_` + i + `_available_qty"
                            data-qty="production_` + i + `_qty" /> </td>
                    <td><input class="form-control proQty text-center"
                            id="production_` + i + `_pro_qty" name="production[` + i + `][pro_qty]"
                            data-available_qty="production_` + i + `_available_qty"
                            data-load_unload_rate="production_` + i + `_load_unload_rate"
                            data-load_unload_amount="production_` + i + `_load_unload_amount" />
                    </td>

                    <td>
                        <input class="form-control bg-primary load_unload_rate text-center"
                            id="production_` + i + `_load_unload_rate"
                            name="production[` + i + `][load_unload_rate]" readonly />
                    </td>
                    <td>
                        <input
                            class="form-control bg-primary load_unload_amount text-center"
                            id="production_` + i + `_load_unload_amount"
                            name="production[` + i + `][load_unload_amount]" readonly />
                    </td>

                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary addRaw"><i
                                    class="fas fa-plus-circle"></i></button><br />
                            <button type = "button" class="btn btn-danger deleteRaw"><i
                                    class = "fas fa-minus-circle"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
            `;
            $('#productionTable').append(html);
            $('.selectpicker').selectpicker('refresh');
            i++;
        });

        $(document).on('click', '.deleteRaw', function() {
            $(this).parent().parent().parent().parent().remove();
        });

        function updateData() {
            let form = document.getElementById('production_store_form');
            let formData = new FormData(form);
            let url = "{{ route('production.update') }}";
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
                    $('#production_store_form').find('.is-invalid').removeClass('is-invalid');
                    $('#production_store_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function(key, value) {
                            var key = key.split('.').join('_');
                            $('#production_store_form input#' + key).addClass('is-invalid');
                            $('#production_store_form textarea#' + key).addClass('is-invalid');
                            $('#production_store_form select#' + key).parent().addClass('is-invalid');
                            $('#production_store_form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            window.location.replace("{{ route('production') }}");
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
