<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
                        <div class="col-md-12">
                            <label for="master_head">{{__('file.Master Head')}}</label>
                            <select class="form-control selectpicker" id="master_head" name="master_head" data-live-search="true">
                                <option value = ''>{{__('file.Please Select')}}</option>
                                @foreach(MASTER_HEAD_VALUE as $key => $value)
                                    <option value = {{$key}}> {{$value}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <br/>
                            <label for="head_id">{{__('file.Head')}}</label>
                            <Select class="form-control selectpicker" id="head_id" name="head_id" data-live-search = "true">
                                <option value="">{{__('file.Please Select')}}</option>
                            </Select>
                        </div>
                        <div class="col-md-12">
                            <br/>
                            <label for="name">{{__('file.Sub Head')}}</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{__('file.Sub Head')}}"/>
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
