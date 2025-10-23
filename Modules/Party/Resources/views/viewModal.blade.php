<div class="modal fade" id="view_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close text-white"></i></button>
            </div>
            <div id="view_form">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">{{__('file.Supplier Name')}}</label>
                            <input type="text" class="form-control bg-secondary" id="name" readonly/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="company_name">{{__('file.Company Name')}}</label>
                            <input type="text" class="form-control bg-secondary" id="company_name" readonly/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="mobile">{{__('file.Mobile')}}</label>
                            <input type="text" class="form-control bg-secondary" id="mobile" readonly/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="previous_balance">{{__('file.Previous Balance')}}</label>
                            <input type="text" class="form-control bg-secondary" id="previous_balance" readonly/>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address">{{__('file.Address')}}</label>
                            <textarea class="form-control bg-secondary" id="address" rows="3" readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('file.Close')}}</button>
            </div>
        </div>
    </div>
</div>
