  @if (!empty($tenant_warehouse_products))
      @foreach ($tenant_warehouse_products as $key => $tenant_warehouse_product)
          @if ($tenant_warehouse_product->qty != 0)
              <tr>
                  <td>
                      <select class="form-control selectpicker text-center labor_warehouse_id"
                          id="production_{{ $key }}_warehouse_id"
                          name="production[{{ $key }}][warehouse_id]" data-live-search = "true">
                          <option value="{{ $tenant_warehouse_product->warehouse_id }}"
                              labour_load_unload_head="{{ $tenant_warehouse_product->warehouse->labour_load_unload_head->rate ?? 0 }}">
                              {{ $tenant_warehouse_product->warehouse->name ?? '' }}
                          </option>
                      </select>
                  </td>

                  {{-- <td>
                  <select class="form-control selectpicker category text-center" id="production_{{ $key }}_category_id"
                      data-warehouse_id="production_{{ $key }}_warehouse_id" data-product_id="production_{{ $key }}_product_id"
                      data-live-search = "true">
                      <option value="">{{ __('Please Select') }}</option>
                      @foreach ($categories as $category)
                          <option value="{{ $category->id }}">
                              {{ $category->category_name }}</option>
                      @endforeach
                  </select>
                  </td> --}}

                  <td>
                      <select class="form-control selectpicker product text-center"
                          id="production_{{ $key }}_product_id"
                          name="production[{{ $key }}][product_id]"
                          data-warehouse_id="production_{{ $key }}_warehouse_id"
                          data-price="production_{{ $key }}_price"
                          data-unit_show="production_{{ $key }}_unit_show"
                          data-unit_id="production_{{ $key }}_unit_id"
                          data-available_qty="production_{{ $key }}_available_qty" data-live-search = "true">
                          <option value="{{ $tenant_warehouse_product->product_id }}">
                              {{ $tenant_warehouse_product->product->product_name ?? '' }}
                          </option>
                      </select>

                      <input type="hidden" id = "production_{{ $key }}_price"
                          name = "production[{{ $key }}][price]" />
                  </td>
                  {{-- batch_no --}}
                  <td>
                      <input class="form-control text-center" id="production_{{ $key }}_batch_no"
                          name="production[{{ $key }}][batch_no]"
                          value="{{ $tenant_warehouse_product->batch_no ?? '' }}" readonly />
                  </td>

                  {{-- unit --}}
                  <td>
                      <input class="form-control bg-primary text-center"
                          value="{{ ($tenant_warehouse_product->product->unit->unit_name ?? '').' ' .($tenant_warehouse_product->product->unit->unit_code ?? '') }}"
                          id="production_{{ $key }}_unit_show" readonly />
                      <input type="hidden" id="production_{{ $key }}_unit_id"
                          value="{{ $tenant_warehouse_product->product->unit->unit_name ?? '' }}" />
                  </td>
                  {{-- available_qty --}}
                  <td>
                      <input class="form-control bg-primary available_qty text-center"
                          id="production_{{ $key }}_available_qty"
                          value="{{ $tenant_warehouse_product->qty ?? '' }}" readonly />
                  </td>
                  {{-- qty --}}
                  <td>
                      <input class="form-control qty text-center" id="production_{{ $key }}_qty"
                          name="production[{{ $key }}][qty]"
                          data-product_id="production_{{ $key }}_product_id"
                          data-unit_id="production_{{ $key }}_unit_id"
                          data-available_qty="production_{{ $key }}_available_qty"
                          data-scale="production_{{ $key }}_scale" />
                  </td>

                  {{-- scale --}}
                  <td>
                      <input class="form-control scale text-center" id="production_{{ $key }}_scale"
                          name="production[{{ $key }}][scale]"
                          data-product_id="production_{{ $key }}_product_id"
                          data-unit_id="production_{{ $key }}_unit_id"
                          data-available_qty="production_{{ $key }}_available_qty"
                          data-qty="production_{{ $key }}_qty" />
                  </td>
                  {{-- pro_qty --}}
                  <td>
                      <input class="form-control proQty text-center" id="production_{{ $key }}_pro_qty"
                          name="production[{{ $key }}][pro_qty]"
                          data-available_qty="production_{{ $key }}_available_qty"
                          data-load_unload_rate="production_{{ $key }}_load_unload_rate"
                          data-load_unload_amount="production_{{ $key }}_load_unload_amount" />
                  </td>
                  <td>
                      <input class="form-control bg-primary load_unload_rate text-center"
                          id="production_{{ $key }}_load_unload_rate"
                          name="production[{{ $key }}][load_unload_rate]"
                          value="{{ $tenant_warehouse_product->warehouse->labour_load_unload_head->rate ?? 0 }}"
                          readonly />
                  </td>
                  <td>
                      <input class="form-control bg-primary loadUnload text-center"
                          id="production_{{ $key }}_load_unload_amount"
                          name="production[{{ $key }}][load_unload_amount]" readonly />
                  </td>
                  <td>
                      {{-- <button type="button" class="btn btn-primary btn-sm addRaw"><i
                          class="fas fa-plus-circle"></i></button><br /> --}}

                      <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i
                              class = "fas fa-minus-circle"></i></button>
                  </td>
              </tr>
          @endif
      @endforeach

      <tr>
          <td colspan="6" class="text-right"> <b>Total : </b></td>

          <td> <input type="number" class="form-control text-center" name="total_scale" id="total_scale"
                  readonly></td>

          <td> <input type="number" name="total_product_qty" class="form-control text-center" id="total_product_qty"
                  readonly></td>
          {{-- <td></td>
          <td> <input type="number" class="form-control text-center" id="total_load_unload"
                  readonly></td> --}}
      </tr>
  @endif
