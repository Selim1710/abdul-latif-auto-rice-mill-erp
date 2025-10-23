<div class="modal fade" id="approve_status_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close text-white"></i></button>
            </div>
            <form id="approve_status_form" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="purchase_id" id="purchase_id">
                    <div class="row">
                        <div class="form-group col-md-12 required">
                            <label for="">{{__('file.Purchase Status')}}</label>
                            <select class="form-control" name="purchase_status" id="purchase_status" required>
                                <option value="">{{__('file.Please Select')}}</option>
                                @foreach(PURCHASE_STATUS_VALUE as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('file.Close')}}</button>
                    <button type="button" class="btn btn-primary btn-sm" id="status-btn"></button>
                </div>
            </form>
        </div>
    </div>
</div>

