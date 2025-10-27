  @if (!empty($laborBillRate->labour_bill_rate_details))
      @foreach ($laborBillRate->labour_bill_rate_details as $key => $labor_bill_detail)
          <tr class="text-center">
              <td>
                  <input type="text" class="form-control bg-primary"
                      value="{{ $labor_bill_detail->warehouse->name ?? '' }}" readonly />

                  <input type="hidden" id="bill_{{ $key }}_labor_bill_rate_detail_id"
                      name="bill[{{ $key }}][labor_bill_rate_detail_id]"
                      value="{{ $labor_bill_detail->id ?? '' }}" />
              </td>
              <td>
                  <input type="text" class="form-control rate" id="bill_{{ $key }}_rate"
                      name="bill[{{ $key }}][rate]" data-qty="bill_{{ $key }}_qty"
                      data-amount="bill_{{ $key }}_amount" value="{{ $labor_bill_detail->rate ?? '' }}" />
              </td>
              <td>
                  <input type="text" class="form-control qty" id="bill_{{ $key }}_qty"
                      name="bill[{{ $key }}][qty]" data-rate="bill_{{ $key }}_rate"
                      data-amount="bill_{{ $key }}_amount" />
              </td>
              <td>
                  <input type="text" class="form-control bg-primary amount" id="bill_{{ $key }}_amount"
                      name="bill[{{ $key }}][amount]" readonly />
              </td>
          </tr>
      @endforeach
      {{-- footer --}}
      <tr class="text-center">
          <td colspan="3"><button type="button"
                  class="btn btn-primary btn-block">{{ __('file.Total Amount') }}</button>
          </td>
          <td><input type="text" class="form-control bg-primary" id="total_amount" /></td>
      </tr>

  @endif
