@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    <div class="card-toolbar">
                        @if (permission('party-add'))
                            <a href="javascript:void(0);" onclick="showFormModal('{{__('file.Add New Party')}}','{{__('file.Save')}}')" class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i> {{__('file.Add New')}}</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="party_id">{{__('file.Party')}}</label>
                                <select class="form-control selectpicker" id="party_id" name="party_id" data-live-search="true">
                                    <option value="">{{__('file.Please Select')}}</option>
                                    @foreach($parties as $party)
                                        <option value="{{$party->id}}">{{$party->company_name.'('.$party->name.')'}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-form.textbox labelName="{{__('file.Mobile')}}" name="mobile" col="col-md-3" />
                            <div class="col-md-3">
                                <div style="margin-top:30px;">
                                    <button style="margin-right : 5px" id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon mr-2 float-left" type="button" data-toggle="tooltip" data-theme="dark" title="{{__('file.Reset')}}"><i class="fas fa-undo-alt"></i></button>
                                    <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon float-left" type="button" data-toggle="tooltip" data-theme="dark" title="{{__('file.Search')}}"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th>{{__('file.SL')}}</th>
                                        <th>{{__('file.Name')}}</th>
                                        <th>{{__('file.Address')}}</th>
                                        <th>{{__('file.Mobile')}}</th>
                                        <th>{{__('file.Previous Balance')}}</th>
                                        <th>{{__('file.Balance Type')}}</th>
                                        <th>{{__('file.Balance')}}</th>
                                        <th>{{__('file.Created By')}}</th>
                                        <th>{{__('file.Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('party::modal')
    @include('party::viewModal')
@endsection
@push('scripts')
    <script>
        function _(x){
            return document.getElementById(x);
        }
        let table;
        $(document).ready(function(){
            table = $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "order"     : [],
                "responsive": true,
                "bInfo"     : true,
                "bFilter"   : false,
                "lengthMenu": [
                    [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                    [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
                ],
                "pageLength": 25,
                "language"  : {
                    processing : `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable : '<strong class="text-danger">{{__('file.No Data Found')}}</strong>',
                    infoEmpty  : '',
                    zeroRecords: '<strong class="text-danger">{{__('file.No Data Found')}}</strong>'
                },
                "ajax"    : {
                    "url" : "{{route('party.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.party_id   = $("#form-filter #party_id option:selected").val();
                        data.mobile     = $("#form-filter #mobile").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                    "targets"  : [0,1,2,3,4,5,6,7,8],
                    "orderable": false,
                    "className": "text-center"
                }]});
            $('#btn-filter').click(function () { table.ajax.reload(); });
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            $(document).on('click', '#save-btn', function () {
                if((_('previous_balance').value != '' && $('#balance_type').find(':selected').val() == '') || (_('previous_balance').value != '' && $('#balance_type').find(':selected').val() == 3) || (_('previous_balance').value == '' && $('#balance_type').find(':selected').val() == 1) || (_('previous_balance').value == '' && $('#balance_type').find(':selected').val() == 2)){
                    notification('error','Previous Balance Empty Or Balance Type Empty Or Balance Type Choose No Balance Please Make It Correct');
                    return;
                }
                let form     = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                let url      = "{{route('party.store.or.update')}}";
                let id       = $('#update_id').val();
                let method;
                if (id) {
                    method = 'update';
                } else {
                    method = 'add';
                }
                store_or_update_data(table, method, url, formData);
            });
            $(document).on('click','.edit_data',function(){
                _('store_or_update_form').reset();
                $('#store_or_update_form #id').val($(this).data('id'));
                $('#store_or_update_form #name').val($(this).data('name'));
                $('#store_or_update_form #company_name').val($(this).data('company_name'));
                $('#store_or_update_form #mobile').val($(this).data('mobile'));
                $('#store_or_update_form #previous_balance').val($(this).data('previous_balance'));
                $('#store_or_update_form #balance_type').val($(this).data('balance_type'));
                $('#store_or_update_form #address').val($(this).data('address'));
                $('#store_or_update_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#store_or_update_modal .modal-title').html('<i class="fas fa-edit text-warning"></i> <span>{{__('file.Edit')}} ' + $(this).data('name') + '</span>');
                $('#store_or_update_modal #save-btn').text('{{__('file.Update')}}');
            });
            $(document).on('click','.view_data',function(){
                $('#view_form #name').val($(this).data('name'));
                $('#view_form #company_name').val($(this).data('company_name'));
                $('#view_form #mobile').val($(this).data('mobile'));
                $('#view_form #previous_balance').val($(this).data('previous_balance'));
                $('#view_form #address').val($(this).data('address'));
                $('#view_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#view_modal .modal-title').html('<i class="fas fa-eye text-white"></i> <span>{{__('file.Details')}} ' + $(this).data('name') + '</span>');
            });
            $(document).on('click', '.delete_data', function () {
                let id    = $(this).data('id');
                let name  = $(this).data('name');
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('party.delete') }}";
                delete_data(id, url, table, row, name);
            });
        });
    </script>
@endpush
