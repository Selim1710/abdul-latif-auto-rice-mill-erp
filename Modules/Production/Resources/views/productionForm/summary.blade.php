<div class="row" style="margin-top: 16px">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Total Raw Scale') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="total_raw_scale"
                                name="total_raw_scale" readonly /></td>
                    </tr>
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Total Raw Amount') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="total_raw_amount"
                                name="total_raw_amount" readonly /></td>
                    </tr>
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Total Use Product Qty') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="total_use_product_qty"
                                name="total_use_product_qty"
                                value="{{ $productionSale[0]->productionSaleProductUseQty + $productionProduct[0]->productionProductUseQty }}"
                                readonly /></td>
                    </tr>
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Total Use Product Amount') }}</button>
                        </td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center"
                                id="total_use_product_amount" name="total_use_product_amount"
                                value="{{ $productionSale[0]->productionSaleProductUseSubTotal + $productionProduct[0]->productionProductUseSubTotal }}"
                                readonly /></td>
                    </tr>
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Total Milling') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="total_milling"
                                name="total_milling" readonly /></td>
                    </tr>
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Total Expense') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="total_expense"
                                name="total_expense" readonly /></td>
                    </tr>
                    {{-- <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Total Sale Scale') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="total_sale_scale"
                                name="total_sale_scale" value="{{ $productionSale[0]->scale }}" readonly /></td>
                    </tr>
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Total Sale Amount') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="total_sale_amount"
                                name="total_sale_amount" value="{{ $productionSale[0]->subTotal }}" readonly /></td>
                    </tr> --}}

                    {{-- new --}}
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Finish Stock Scale') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="finish_stock_scale"
                                name="finish_stock_scale"
                                value="{{ $productionProduct[0]->scale - $byProduct[0]->scale }}" readonly /></td>
                    </tr>
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Finish Stock Amount') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="finish_stock_amount"
                                name="finish_stock_amount"
                                value="{{ $productionProduct[0]->subTotal - $byProduct[0]->subTotal }}" readonly />
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.By Product Stock Scale') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="by_product_stock_scale"
                                name="by_product_stock_scale"
                                value="{{ $byProduct[0]->scale }}" readonly /></td>
                    </tr>
                    <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.By Product Stock Amount') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="by_product_stock_amount"
                                name="by_product_stock_amount"
                                value="{{ $byProduct[0]->subTotal }}" readonly />
                        </td>
                    </tr>
                    
                    {{-- <tr class="text-center">
                        <td><button type="button"
                                class="btn btn-primary btn-block">{{ __('file.Per Unit Scale Cost') }}</button></td>
                        <td><b>:</b></td>
                        <td><input type="text" class="bg-primary form-control text-center" id="per_unit_scale_cost"
                                name="per_unit_scale_cost" readonly /></td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3"><button type="button" class="btn btn-secondary btn-block" onclick="show_form(2)"><i
                class="fas fa-arrow-circle-left"></i>{{ __('file.Previous') }}</button></div>
    <div class="col-md-6"></div>
    <div class="col-md-3"><button type="button" class="btn btn-success btn-block next_button"
            data-wizard-type="action-next" onclick="store_data()"><i
                class="fas fa-save"></i>{{ ' ' }}{{ __('file.Save') }}</button></div>
</div>
