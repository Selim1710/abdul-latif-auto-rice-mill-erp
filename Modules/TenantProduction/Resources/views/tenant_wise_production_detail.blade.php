  @if (!empty($tenant_warehouse_products))
      @foreach ($tenant_warehouse_products as $key => $tenant_warehouse_product)
          <tr>
              <td>
                  <select class="form-control selectpicker text-center" id="production_0_warehouse_id"
                      name="production[0][warehouse_id]" data-live-search = "true">
                      <option value="{{ $tenant_warehouse_product->warehouse_id }}">
                          {{ $tenant_warehouse_product->warehouse->name ?? '' }}
                      </option>
                  </select>
              </td>

              {{-- <td>
                  <select class="form-control selectpicker category text-center" id="production_0_category_id"
                      data-warehouse_id="production_0_warehouse_id" data-product_id="production_0_product_id"
                      data-live-search = "true">
                      <option value="">{{ __('Please Select') }}</option>
                      @foreach ($categories as $category)
                          <option value="{{ $category->id }}">
                              {{ $category->category_name }}</option>
                      @endforeach
                  </select>
              </td> --}}
              <td>
                  <select class="form-control selectpicker product text-center" id="production_0_product_id"
                      name="production[0][product_id]" data-warehouse_id="production_0_warehouse_id"
                      data-price="production_0_price" data-unit_show="production_0_unit_show"
                      data-unit_id="production_0_unit_id" data-available_qty="production_0_available_qty"
                      data-live-search = "true">
                      <option value="{{ $tenant_warehouse_product->product_id }}">
                          {{ $tenant_warehouse_product->product->product_name ?? '' }}
                      </option>
                  </select>

                  <input type="hidden" id = "production_0_price" name = "production[0][price]" />
              </td>
              {{-- batch_no --}}
              <td>
                  <input name="batch_no" class="form-control bg-primary batch_no text-center" id="production_0_batch_no"
                      value="{{ $tenant_warehouse_product->batch_no ?? '' }}" readonly />
              </td>

              {{-- unit --}}
              <td>
                  <input class="form-control bg-primary text-center"
                      value="{{ $tenant_warehouse_product->product->unit->unit_name ?? '' }}"
                      id="production_0_unit_show" readonly />
                  <input type="hidden" id="production_0_unit_id"
                      value="{{ $tenant_warehouse_product->product->unit->id ?? '' }}" />
              </td>
              {{-- available_qty --}}
              <td>
                  <input class="form-control bg-primary available_qty text-center" id="production_0_available_qty"
                      value="{{ $tenant_warehouse_product->qty ?? '' }}" readonly />
              </td>
              {{-- qty --}}
              <td>
                  <input class="form-control qty text-center" id="production_0_qty" name="production[0][qty]"
                      data-product_id="production_0_product_id" data-unit_id="production_0_unit_id"
                      data-available_qty="production_0_available_qty" data-scale="production_0_scale" />
              </td>

              {{-- scale --}}
              <td>
                  <input class="form-control scale text-center" id="production_0_scale" name="production[0][scale]"
                      data-product_id="production_0_product_id" data-unit_id="production_0_unit_id"
                      data-available_qty="production_0_available_qty" data-qty="production_0_qty" />
              </td>
              {{-- pro_qty --}}
              <td>
                  <input class="form-control proQty text-center" id="production_0_pro_qty" name="production[0][pro_qty]"
                      data-available_qty="production_0_available_qty" />
              </td>
              <td>
                  <button type="button" class="btn btn-primary btn-sm addRaw"><i
                          class="fas fa-plus-circle"></i></button><br />
                  <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i
                          class = "fas fa-minus-circle"></i></button>
              </td>
          </tr>
      @endforeach
  @endif
