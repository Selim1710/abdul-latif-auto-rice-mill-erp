<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">

      <!-- Modal Content -->
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-dark-primary">
          <h3 class="modal-title text-white" id="model-1"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close text-white"></i>
          </button>
        </div>
        <!-- /modal header -->
        <form id="store_or_update_form" method="post">
          @csrf
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="update_id" id="update_id"/>
                    <x-form.textbox labelName="{{__('file.Asset Tag')}}" name="tag" required="required" col="col-md-4"/>
                    <x-form.textbox labelName="{{__('file.Asset Name')}}" name="name" required="required" col="col-md-4"/>
                    <x-form.selectbox labelName="{{__('file.Asset Type')}}" name="asset_type_id" required="required"  col="col-md-4" class="selectpicker">
                      @if (!$types->isEmpty())
                          @foreach ($types as $key => $value)
                              <option value="{{ $value->id }}">{{ $value->name }}</option>
                          @endforeach
                      @endif
                    </x-form.selectbox>
                    <x-form.textbox labelName="{{__('file.Cost')}}" name="cost" required="required" col="col-md-4"/>
                    <x-form.textbox labelName="{{__('file.Purchase Date')}}" name="purchase_date" class="date" col="col-md-4"/>
                    <x-form.textbox labelName="{{__('file.Warranty')}}({{__('file.Months')}})" name="warranty" col="col-md-4"/>
                    <x-form.textbox labelName="{{__('file.Asset User')}}" name="user" col="col-md-4"/>
                    <x-form.textbox labelName="{{__('file.Location')}}" name="location" col="col-md-4"/>
                    <x-form.selectbox labelName="{{__('file.Asset Status')}}" name="asset_status" required="required" col="col-md-4" class="selectpicker">
                      @foreach (ASSET_STATUS as $key => $value)
                          <option value="{{ $key }}">{{ $value }}</option>
                      @endforeach
                    </x-form.selectbox>
                    <x-form.textarea labelName="{{__('file.Description')}}" name="description" col="col-md-12"/>
                    <div class="form-group col-md-4">
                      <label for="logo" class="form-control-label">{{__('file.Photo')}}</label>
                      <div class="col=md-12 px-0  text-center">
                          <div id="photo">

                          </div>
                      </div>
                      <input type="hidden" name="old_photo" id="old_photo">
                    </div>
                  </div>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('file.Close')}}</button>
            <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
            </div>
            <!-- /modal footer -->
        </form>
      </div>
      <!-- /modal content -->

    </div>
  </div>
