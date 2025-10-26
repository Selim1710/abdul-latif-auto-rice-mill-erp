@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    <div class="card-toolbar">
                        @if (permission('labor-bill-rate-add'))
                            <a href="javascript:void(0);" onclick="showFormModal('{{__('file.Add New')}}','{{__('file.Save')}}')" class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i> {{__('file.Add New')}}</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <x-form.textbox labelName="{{__('file.Name')}}" name="name" col="col-md-4" />
                            <div class="col-md-4">
                                <div style="margin-top:28px;">
                                    <div style="margin-top:28px;">
                                        <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon mr-2 float-left" type="button" data-toggle="tooltip" data-theme="dark" title="{{__('file.Reset')}}"><i class="fas fa-undo-alt"></i></button>
                                        <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon float-left" type="button" data-toggle="tooltip" data-theme="dark" title="{{__('file.Search')}}"><i class="fas fa-search"></i></button>
                                    </div>
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
                                        <th>{{__('file.Warehouse')}}</th>
                                        <th>{{__('file.Name')}}</th>
                                        <th>{{__('file.Rate')}}</th>
                                        <th>{{__('file.Status')}}</th>
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
    @include('laborhead::laborBillRate.modal')
@endsection
@push('scripts')
    <script>
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
                "pageLength": 25, //number of data show per page
                "language"  : {
                    processing : `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable : '<strong class="text-danger">No Data Found</strong>',
                    infoEmpty  : '',
                    zeroRecords: '<strong class="text-danger">No Data Found</strong>'
                },
                "ajax": {
                    "url": "{{route('labor.bill.rate.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.name   = $("#form-filter #name").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                    "targets": [0,1,2,3,4,5],
                    "orderable": false,
                    "className": "text-center"
                },
                ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            });
            $('#btn-filter').click(function () {table.ajax.reload();});
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            $(document).on('click', '#save-btn', function () {
                let form     = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                let url      = "{{route('labor.bill.rate.store.or.update')}}";
                let id       = $('#update_id').val();
                let method;
                if (id) {
                    method = 'update';
                } else {
                    method = 'add';
                }
                store_or_update_data(table, method, url, formData);
            });
            $(document).on('click', '.edit_data', function () {
                $('#store_or_update_form #update_id').val($(this).data('id'));
                $('#store_or_update_form #warehouse_id').val($(this).data('warehouse_id')).change();
                $('#store_or_update_form #name').val($(this).data('name'));
                $('#store_or_update_form #rate').val($(this).data('rate'));
                $('#store_or_update_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#store_or_update_modal .modal-title').html('<i class="fas fa-edit text-white"></i> <span>{{__('file.Edit')}}' + ' Data' + '</span>');
                $('#store_or_update_modal #save-btn').text('{{__('file.Update')}}');
            });
            $(document).on('click', '.delete_data', function () {
                let id    = $(this).data('id');
                let name  = $(this).data('name');
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('labor.bill.rate.delete') }}";
                delete_data(id, url, table, row, name);
            });
        });
    </script>
@endpush
