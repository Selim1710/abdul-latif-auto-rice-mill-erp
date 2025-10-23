<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark-primary">
          <h3 class="modal-title text-white" id="model-1"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close text-white"></i></button>
        </div>
        <form id="store_or_update_form" method="post">
          @csrf
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="update_id" id="update_id"/>
                    <x-form.textbox labelName="{{__('file.Unit Name')}}" name="unit_name" required="required" col="col-md-12" placeholder="Enter unit name"/>
                    <x-form.textbox labelName="{{__('file.Unit Code')}}" name="unit_code" required="required" col="col-md-12" placeholder="Enter unit code"/>
                    <x-form.selectbox labelName="{{__('file.Base Unit')}}" name="base_unit" col="col-md-12" class="selectpicker"/>
                    <x-form.textbox labelName="{{__('file.Operator')}}" name="operator" col="col-md-12" placeholder="Enter operator"/>
                    <x-form.textbox labelName="{{__('file.Operation Value')}}" name="operation_value" col="col-md-12" placeholder="Enter operation value"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('file.Close')}}</button>
                <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
            </div>
        </form>
      </div>
    </div>
  </div>
