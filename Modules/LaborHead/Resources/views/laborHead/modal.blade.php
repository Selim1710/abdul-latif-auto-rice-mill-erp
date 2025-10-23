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
                        <input type="hidden" name="update_id" id="update_id"/>
                        <div class="col-md-12">
                            <label for="name">{{__('file.Name')}}</label>
                            <input type="text" class="form-control" id="labor_name" name="name" />
                        </div>
                        <div class="col-md-12">
                            <br/>
                            <label for="name">{{__('file.Mobile')}}</label>
                            <input type="text" class="form-control" id="labor_mobile" name="mobile" />
                        </div>
                        <div class="col-md-12">
                            <br/>
                            <label for="name">{{__('file.Previous Balance')}}</label>
                            <input type="text" class="form-control" id="labor_previous_balance" name="previous_balance" />
                        </div>
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
