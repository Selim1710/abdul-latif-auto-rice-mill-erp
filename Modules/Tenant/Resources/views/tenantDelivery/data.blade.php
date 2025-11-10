 @if ($tenant_warehouse_products)
     @foreach ($tenant_warehouse_products as $key => $tenant_warehouse_product)
         @if ($tenant_warehouse_product->qty != 0)
             <tr class="text-center">
                 <td>
                     <select class="form-control selectpicker text-center"
                         id="tenant_delivery_{{ $key }}_warehouse_id"
                         name="tenant_delivery[{{ $key }}][warehouse_id]" data-live-search = "true">
                         <option value="{{ $tenant_warehouse_product->warehouse->id ?? '' }}">
                             {{ $tenant_warehouse_product->warehouse->name ?? '' }}</option>
                     </select>
                 </td>

                 <td>
                     <input class="form-control bg-primary text-center"
                         name="tenant_delivery[{{ $key }}][batch_no]"
                         id="tenant_delivery_{{ $key }}_batch_no"
                         value="{{ $tenant_warehouse_product->batch_no ?? '' }}" readonly />
                 </td>

                 <td>
                     <select class="form-control selectpicker product text-center"
                         id="tenant_delivery_{{ $key }}_product_id"
                         name="tenant_delivery[{{ $key }}][product_id]"
                         data-warehouse_id = "tenant_delivery_{{ $key }}_warehouse_id"
                         data-unit_show="tenant_delivery_{{ $key }}_unit_show"
                         data-unit_id="tenant_delivery_{{ $key }}_unit_id"
                         data-av_qty="tenant_delivery_{{ $key }}_av_qty"
                         data-av_scale="tenant_delivery_{{ $key }}_av_scale" data-live-search = "true">
                         <option value="{{ $tenant_warehouse_product->product_id ?? '' }}">
                             {{ $tenant_warehouse_product->product->product_name ?? '' }}</option>
                     </select>
                 </td>
                 <td>
                     <input class="form-control bg-primary text-center"
                         value="{{ ($tenant_warehouse_product->product->unit->unit_name ?? '') . ' ' . ($tenant_warehouse_product->product->unit->unit_code ?? '') }}"
                         id="tenant_delivery_{{ $key }}_unit_show" readonly />

                     <input type="hidden" id="tenant_delivery_{{ $key }}_unit_id"
                         value="{{ $tenant_warehouse_product->product->unit->unit_name ?? '' }}" />
                 </td>
                 <td><input class="form-control av_qty text-center bg-primary"
                         value="{{ $tenant_warehouse_product->qty ?? '' }}"
                         id="tenant_delivery_{{ $key }}_av_qty" readonly />
                 </td>
                 <td><input class="form-control av_scale text-center bg-primary"
                         value="{{ $tenant_warehouse_product->scale ?? '' }}"
                         id="tenant_delivery_{{ $key }}_av_scale" readonly />
                 </td>
                 <td><input class="form-control qty text-center" id="tenant_delivery_{{ $key }}_qty"
                         name="tenant_delivery[{{ $key }}][qty]"
                         data-product_id="tenant_delivery_{{ $key }}_product_id"
                         data-unit_id="tenant_delivery_{{ $key }}_unit_id"
                         data-av_qty="tenant_delivery_{{ $key }}_av_qty"
                         data-av_scale="tenant_delivery_{{ $key }}_av_scale"
                         data-scale="tenant_delivery_{{ $key }}_scale" /></td>
                 <td><input class="form-control scale text-center" id="tenant_delivery_{{ $key }}_scale"
                         name="tenant_delivery[{{ $key }}][scale]"
                         data-product_id="tenant_delivery_{{ $key }}_product_id"
                         data-unit_id="tenant_delivery_{{ $key }}_unit_id"
                         data-av_qty="tenant_delivery_{{ $key }}_av_qty"
                         data-av_scale="tenant_delivery_{{ $key }}_av_scale"
                         data-qty="tenant_delivery_{{ $key }}_qty" /> </td>
                 <td><input class="form-control delQty" id="tenant_delivery_{{ $key }}_del_qty"
                         name="tenant_delivery[{{ $key }}][del_qty]"
                         data-av_qty="tenant_delivery_{{ $key }}_av_qty" /></td>
                 <td>
                     <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i
                             class = "fas fa-minus-circle"></i></button>
                 </td>
             </tr>
         @endif
     @endforeach
 @endif
