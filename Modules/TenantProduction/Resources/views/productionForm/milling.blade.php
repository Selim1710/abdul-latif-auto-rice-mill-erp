<div class="row">
    <div class="col-md-12 py-5">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr class="text-center">
                    <th>{{ __('file.Product Name') }}</th>
                    <th>{{ __('file.Unit') }}</th>
                    <th>{{ __('file.Qty') }}</th>
                    <th>{{ __('file.Use Qty') }}</th>
                    <th>{{ __('file.Use Scale') }}</th>
                    <th>{{ __('file.Use Pro Qty') }}</th>
                    {{-- <th>{{ __('file.Rate') }}</th>
                    <th>{{ __('file.Milling') }}</th> --}}
                </tr>
            </thead>
            <tbody>
                @if (isset($production->rawList))
                    @foreach ($production->rawList as $key => $value)
                        <tr class="text-center">
                            <td>
                                <input type="hidden" id="raws_{{ $key }}_warehouse_id"
                                    name="raws[{{ $key }}][warehouse_id]" value="{{ $value->warehouse_id }}" />
                                <input type="hidden" id="raws_{{ $key }}_product_id"
                                    name="raws[{{ $key }}][product_id]" value="{{ $value->product_id }}" />
                                <input type="text" class="form-control bg-primary"
                                    value="{{ $value->product->product_name }}" readonly />
                            </td>
                            <td>
                                <input class="form-control bg-primary text-center"
                                    id="raw_{{ $key }}_unit_show"
                                    value="{{ $value->product->unit->unit_name . '(' . $value->product->unit->unit_code . ')' }}"
                                    readonly /><input type="hidden" id="raw_{{ $key }}_unit_id"
                                    value="{{ $value->product->unit->unit_name }}" />
                            </td>
                            <td>
                                <input type="text" class="form-control bg-primary"
                                    id="raws_{{ $key }}_pro_qty" name="raws[{{ $key }}][pro_qty]"
                                    value="{{ $value->pro_qty }}" readonly />
                                <input type="hidden" id="raws_{{ $key }}_scale"
                                    name="raws[{{ $key }}][scale]" value="{{ $value->scale }}" />
                            </td>
                            <td><input type="text" class="form-control useQty" id="raws_{{ $key }}_use_qty"
                                    name="raws[{{ $key }}][use_qty]" data-price="{{ $value->price }}"
                                    data-pro_qty="raws_{{ $key }}_pro_qty"
                                    data-scale="raws_{{ $key }}_scale"
                                    data-unit_id="raw_{{ $key }}_unit_id"
                                    data-use_scale="raws_{{ $key }}_use_scale" /></td>
                            <td><input type="text" class="form-control useScale"
                                    id="raws_{{ $key }}_use_scale"
                                    name="raws[{{ $key }}][use_scale]" data-price="{{ $value->price }}"
                                    data-pro_qty="raws_{{ $key }}_pro_qty"
                                    data-scale="raws_{{ $key }}_scale"
                                    data-unit_id="raw_{{ $key }}_unit_id"
                                    data-use_qty="raws_{{ $key }}_use_qty" /></td>
                            <td><input type="text" class="form-control useProQty"
                                    id="raws_{{ $key }}_use_pro_qty"
                                    name="raws[{{ $key }}][use_pro_qty]" data-price="{{ $value->price }}"
                                    data-pro_qty="raws_{{ $key }}_pro_qty" /></td>
                            {{-- <td><input type="text" class="form-control rate" id="raws_{{ $key }}_rate"
                                    name="raws[{{ $key }}][rate]"
                                    data-use_scale="raws_{{ $key }}_use_scale"
                                    data-milling="raws_{{ $key }}_milling" /></td>
                            <td><input type="text" class="form-control milling bg-primary"
                                    id="raws_{{ $key }}_milling" name="raws[{{ $key }}][milling]"
                                    readonly /> </td> --}}
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <table class="table">
            <thead>
                <tr class="text-center">
                    <th class="bg-success">{{ __('file.Merge') }}</th>
                </tr>
            </thead>
        </table>
        <table class="table table-bordered millingTableAppend" id="millingTableAppend">
            <thead class="bg-primary text-center">
                <th>{{ __('file.Company') }}</th>
                <th>{{ __('file.Category') }}</th>
                <th>{{ __('file.Product') }}</th>
                <th>{{ __('file.Unit') }}</th>
                <th>{{ __('file.Available Qty') }}</th>
                <th>{{ __('file.Qty') }}</th>
                <th>{{ __('file.Scale') }}</th>
                <th>{{ __('file.Mer Qty') }}</th>
                {{-- <th>{{ __('file.Rate') }}</th>
                <th>{{ __('file.Milling') }}</th> --}}
                <th>{{ __('file.Action') }}</th>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>
                        <select class="form-control selectpicker" id="merge_0_warehouse_id"
                            name="merge[0][warehouse_id]" data-product_id = "merge_0_product_id"
                            data-live-search="true">
                            <option value="" selected>{{ __('file.Select Please') }}</option>
                            @if (!$warehouses->isEmpty())
                                @foreach ($warehouses as $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td>
                        <select class="form-control selectpicker mergeCategory" id="merge_0_category_id"
                            data-warehouse_id="merge_0_warehouse_id" data-product_id = "merge_0_product_id"
                            data-live-search="true">
                            <option value="" selected>{{ __('file.Select Please') }}</option>
                            @if (!$categories->isEmpty())
                                @foreach ($categories as $value)
                                    <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td>
                        <select class="form-control product selectpicker mergeProduct" id="merge_0_product_id"
                            data-warehouse_id="merge_0_warehouse_id" data-unit_id_show = "merge_0_unit_id_show"
                            data-unit_id = "merge_0_unit_id" data-price = "merge_0_price"
                            data-available_qty = "merge_0_available_qty" name="merge[0][product_id]"
                            data-live-search="true"></select>
                    </td>
                    <td>
                        <input type="text" class="form-control bg-primary" id="merge_0_unit_id_show" readonly />
                        <input type="hidden" class="form-control bg-primary" id="merge_0_unit_id" readonly />
                        <input type="hidden" id="merge_0_price" name="merge[0][price]" />
                    </td>
                    <td><input type="text" class="form-control bg-primary" id="merge_0_available_qty" readonly />
                    </td>
                    <td><input type="text" class="form-control qty" id="merge_0_qty" name="merge[0][qty]"
                            data-product_id = "merge_0_product_id" data-available_qty = "merge_0_available_qty"
                            data-unit_id = "merge_0_unit_id" data-scale="merge_0_scale" /></td>
                    <td><input type="text" class="form-control merScale" id="merge_0_scale"
                            name="merge[0][scale]" data-product_id = "merge_0_product_id"
                            data-available_qty = "merge_0_available_qty" data-unit_id = "merge_0_unit_id"
                            data-qty="merge_0_qty" /></td>
                    <td><input type="text" class="form-control merQty" id="merge_0_mer_qty"
                            name="merge[0][mer_qty]" data-product_id = "merge_0_product_id"
                            data-available_qty = "merge_0_available_qty" /></td>
                    {{-- <td><input type="text" class="form-control merRate" id="merge_0_rate" name="merge[0][rate]"
                            data-use_scale="merge_0_scale" data-milling="merge_0_milling" /></td>
                    <td><input type="text" class="form-control milling bg-primary" id="merge_0_milling"
                            name="merge[0][milling]" readonly /> </td> --}}

                    <td><button type="button" class="btn btn-primary btn-sm addRaw"><i
                                class="fas fa-plus-circle"></i></button><br /><button type = "button"
                            class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i
                                class = "fas fa-minus-circle"></i></button></td>
                </tr>
            </tbody>
        </table>
        <table class="table">
            <tr>
                <td width="50%"></td>
                <td width="25%">
                    <button type="button" class="btn btn-primary btn-block">{{ __('file.Total Milling') }}</button>
                </td>
                <td width="25%">
                    <input type="text" value="69000" class="form-control bg-primary" id="total_milling_show" />
                </td>
            </tr>
            <tr>
                <td width="50%"></td>
                <td width="25%">
                    <button type="button"
                        class="btn btn-primary btn-block">{{ __('file.Total Merge Scale') }}</button>
                </td>
                <td width="25%">
                    <input type="text" class="form-control bg-primary" id="total_merge_scale_show" />
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-9"></div>
    <div class="col-md-3"><button type="button" class="btn btn-primary btn-block next_button"
            data-wizard-type="action-next" data-type="milling">{{ __('file.Next') }}{{ ' ' }}<i
                class="fas fa-arrow-circle-right"></i></button></div>
</div>
