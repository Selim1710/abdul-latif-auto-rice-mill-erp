<div class="row" style="margin-top: 16px">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                <tr class="text-center">
                    <td><button class="btn btn-primary btn-block">{{__('file.Total Raw Scale')}}</button></td>
                    <td><b>:</b></td>
                    <td><input type="text" class="bg-primary text-white form-control text-center" id="total_raw_scale" name="total_raw_scale" readonly/></td>
                </tr>
                <tr class="text-center">
                    <td><button class="btn btn-primary btn-block">{{__('file.Total Merge Scale')}}</button></td>
                    <td><b>:</b></td>
                    <td><input type="text" class="bg-primary text-white form-control text-center" id="total_merge_scale" name="total_merge_scale" readonly/></td>
                </tr>
                <tr class="text-center">
                    <td><button class="btn btn-primary btn-block">{{__('file.Total Use Product Qty')}}</button></td>
                    <td><b>:</b></td>
                    <td><input type="text" class="bg-primary text-white form-control text-center" id="total_use_product_qty" name="total_use_product_qty" value="{{$productionDelivery[0]->productionDeliveryProductUseQty + $productionProduct[0]->productionProductUseQty}}" readonly/></td>
                </tr>
                <tr class="text-center">
                    <td><button class="btn btn-primary btn-block">{{__('file.Total Milling')}}</button></td>
                    <td><b>:</b></td>
                    <td><input type="text" class="bg-primary text-white form-control text-center" id="total_milling" name="total_milling" readonly/></td>
                </tr>
                <tr class="text-center">
                    <td><button class="btn btn-primary btn-block">{{__('file.Total Expense')}}</button></td>
                    <td><b>:</b></td>
                    <td><input type="text" class="bg-primary text-white form-control text-center" id="total_expense" name="total_expense" readonly/></td>
                </tr>
                <tr class="text-center">
                    <td><button class="btn btn-primary btn-block">{{__('file.Total Delivery Scale')}}</button></td>
                    <td><b>:</b></td>
                    <td><input type="text" class="bg-primary text-white form-control text-center" id="total_delivery_scale" name="total_delivery_scale" value="{{$productionDelivery[0]->scale}}" readonly/></td>
                </tr>
                <tr class="text-center">
                    <td><button class="btn btn-primary btn-block">{{__('file.Total Stock Scale')}}</button></td>
                    <td><b>:</b></td>
                    <td><input type="text" class="bg-primary text-white form-control text-center" id="total_stock_scale" name="total_stock_scale" value="{{$productionProduct[0]->scale}}" readonly/></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3"><button type="button" class="btn btn-secondary btn-block" onclick="show_form(2)"><i class="fas fa-arrow-circle-left"></i>{{__('file.Previous')}}</button></div>
    <div class="col-md-6"></div>
    <div class="col-md-3"><button type="button" class="btn btn-success btn-block next_button" data-wizard-type="action-next" onclick="store_data()"><i class="fas fa-save"></i>{{' '}}{{__('file.Save')}}</button></div>
</div>
