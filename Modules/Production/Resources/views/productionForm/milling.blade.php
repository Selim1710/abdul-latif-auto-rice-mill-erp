<div class="row">
    <div class="col-md-12 py-5">
        <table class="table table-bordered">
            <thead class="bg-primary">
            <tr class="text-center">
                <th>{{__('file.Product Name')}}</th>
                <th>{{__('file.Unit')}}</th>
                <th>{{__('file.Qty')}}</th>
                <th>{{__('file.Use Qty')}}</th>
                <th>{{__('file.Use Scale')}}</th>
                <th>{{__('file.Use Pro Qty')}}</th>
                <th>{{__('file.Rate')}}</th>
                <th>{{__('file.Milling')}}</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($production->productionRawProductList))
                @foreach($production->productionRawProductList as $key => $value)
                    <tr class="text-center">
                        <td>
                            <input type="hidden" id="raws_{{$key}}_warehouse_id" name="raws[{{$key}}][warehouse_id]" value="{{$value->warehouse_id}}"/>
                            <input type="hidden" id="raws_{{$key}}_product_id" name="raws[{{$key}}][product_id]" value="{{$value->product_id}}"/>
                            <input type="text" class="form-control bg-primary" value="{{$value->product->product_name}}" readonly/>
                        </td>
                        <td>
                            <input class="form-control bg-primary text-center" id="raw_{{$key}}_unit_show" value="{{$value->product->unit->unit_name.'('.$value->product->unit->unit_code.')'}}" readonly/><input type="hidden" id="raw_{{$key}}_unit_id" value="{{$value->product->unit->unit_name}}"/>
                        </td>
                        <td>
                            <input type="text" class="form-control bg-primary" id="raws_{{$key}}_pro_qty" name="raws[{{$key}}][pro_qty]" value="{{$value->pro_qty}}" readonly/>
                            <input type="hidden" id="raws_{{$key}}_scale" name="raws[{{$key}}][scale]" value="{{$value->scale}}"/>
                        </td>
                        <td><input type="text" class="form-control useQty" id="raws_{{$key}}_use_qty" name="raws[{{$key}}][use_qty]" data-price="{{$value->price}}" data-pro_qty="raws_{{$key}}_pro_qty" data-scale="raws_{{$key}}_scale" data-unit_id="raw_{{$key}}_unit_id" data-use_scale="raws_{{$key}}_use_scale"/></td>
                        <td><input type="text" class="form-control useScale" id="raws_{{$key}}_use_scale" name="raws[{{$key}}][use_scale]" data-price="{{$value->price}}" data-pro_qty="raws_{{$key}}_pro_qty" data-scale="raws_{{$key}}_scale" data-unit_id="raw_{{$key}}_unit_id" data-use_qty="raws_{{$key}}_use_qty"/></td>
                        <td><input type="text" class="form-control useProQty" id="raws_{{$key}}_use_pro_qty" name="raws[{{$key}}][use_pro_qty]" data-price="{{$value->price}}" data-pro_qty="raws_{{$key}}_pro_qty"/></td>
                        <td><input type="text" class="form-control rate" id="raws_{{$key}}_rate" name="raws[{{$key}}][rate]" data-use_scale="raws_{{$key}}_use_scale" data-milling="raws_{{$key}}_milling"/></td>
                        <td><input type="text" class="form-control milling bg-primary" id="raws_{{$key}}_milling" name="raws[{{$key}}][milling]" readonly/> </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7"><button type="button" class="btn btn-primary btn-block">{{__('file.Total')}}</button></td>
                <td><input type="text" class="form-control bg-primary" id="total_milling_show"/> </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-9"></div>
    <div class="col-md-3"><button type="button" class="btn btn-primary btn-block next_button" data-wizard-type="action-next" data-type="milling">{{__('file.Next')}}{{' '}}<i class="fas fa-arrow-circle-right"></i></button></div>
</div>
