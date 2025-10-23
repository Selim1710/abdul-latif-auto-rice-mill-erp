<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close text-white"></i></button>
            </div>
            <form id="store_or_update_form" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="update_id" id="update_id"/>
                        <input type="hidden" name="old_name" id="old_name"/>
                        <input type="hidden" name="old_previous_balance" id="old_previous_balance"/>
                        <x-form.textbox labelName="{{__('file.Tenant Name')}}" name="name" required="required" col="col-md-6" placeholder="{{__('file.Tenant Name')}}"/>
                        <x-form.textbox labelName="{{__('file.Mobile')}}" name="mobile" required="required" col="col-md-6" placeholder="{{__('file.Mobile')}}"/>
                        <x-form.textbox labelName="{{__('file.Address')}}" name="address" required="required" col="col-md-6" placeholder="{{__('file.Address')}}"/>
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
