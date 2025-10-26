<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                        class="ki ki-close text-white"></i></button>
            </div>
            <form id="store_or_update_form" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="update_id" id="update_id" />
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="warehouse_id">{{ __('file.Warehouse') }}</label>
                                <select name="warehouse_id" class="form-control selectpicker" data-live-search="true"
                                    id="warehouse_id">
                                    @if (!empty($warehouses))
                                        <option value="">Select One</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <x-form.textbox labelName="{{ __('file.Name') }}" name="name" required="required"
                            col="col-md-12" placeholder="{{ __('file.Name') }}" />
                        <x-form.textbox labelName="{{ __('file.Rate') }}" name="rate" required="required"
                            col="col-md-12" placeholder="{{ __('file.Rate') }}" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm"
                        data-dismiss="modal">{{ __('file.Close') }}</button>
                    <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
                </div>
            </form>
        </div>
    </div>
</div>
