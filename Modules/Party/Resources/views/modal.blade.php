<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark-primary">
          <h3 class="modal-title text-white" id="model-1"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close text-white"></i></button>
        </div>
        <form id="store_or_update_form" method="post">
          @csrf
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="id" id="id"/>
                    <x-form.textbox labelName="{{__('file.Party Name')}}" name="name" required="required" col="col-md-4" placeholder="{{__('file.Enter party name')}}"/>
                    <x-form.textbox labelName="{{__('file.Company Name')}}" name="company_name" col="col-md-4" placeholder="{{__('file.Enter company name')}}"/>
                    <x-form.textbox labelName="{{__('file.Mobile')}}" name="mobile" required="required" col="col-md-4" placeholder="{{__('file.Enter mobile number')}}"/>
                    <x-form.textbox labelName="{{__('file.Previous Balance')}}" name="previous_balance" col="col-md-6" class="text-right" placeholder="{{__('file.Previous Balance')}}"/>
                    <div class="col-md-6">
                        <label for="balance_type">{{__('file.Balance Type')}}</label>
                        <select class="form-control" id="balance_type" name="balance_type">
                            <option value="">{{__('file.Please Select')}}</option>
                            @foreach(BALANCE_TYPE_VALUE as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form.textarea labelName="{{__('file.Address')}}" name="address" col="col-md-12" placeholder="{{__('file.Address')}}"/>
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
