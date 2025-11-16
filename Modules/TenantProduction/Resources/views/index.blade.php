@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        @if (permission('tenant-production-add'))
                            <a href="{{ route('tenant.production.add') }}"
                                class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i>
                                {{ __('file.Add New') }}</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <x-form.textbox labelName="{{ __('file.Invoice No') }}." name="invoice_no" col="col-md-4" />
                            <x-form.selectbox labelName="{{ __('file.Mill') }}" name="mill_id" col="col-md-4"
                                class="selectpicker">
                                @if (!$mills->isEmpty())
                                    @foreach ($mills as $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            <div class="form-group col-md-4">
                                <label for="start_date">{{ __('file.Start Date') }}</label>
                                <input type="date" class="form-control date" name="start_date" id="start_date" />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="end_date">{{ __('file.End Date') }}</label>
                                <input type="date" class="form-control date" name="end_date" id="end_date" />
                            </div>
                            <div class="col-md-4">
                                <div style="margin-top:28px;">
                                    <div style="margin-top:28px;">
                                        <button id="btn-reset"
                                            class="btn btn-danger btn-sm btn-elevate btn-icon mr-2 float-left"
                                            type="button" data-toggle="tooltip" data-theme="dark"
                                            title="{{ __('file.Reset') }}"><i class="fas fa-undo-alt"></i></button>
                                        <button id="btn-filter"
                                            class="btn btn-primary btn-sm btn-elevate btn-icon float-left" type="button"
                                            data-toggle="tooltip" data-theme="dark" title="{{ __('file.Search') }}"><i
                                                class="fas fa-search"></i></button>
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
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>{{ __('file.SL') }}</th>
                                                <th>{{ __('file.Invoice No') }}</th>
                                                <th>{{ __('file.Tenant') }}</th>
                                                <th>{{ __('file.Mill') }}</th>
                                                <th>{{ __('file.Date') }}</th>
                                                <th>{{ __('file.Start Date') }}</th>
                                                <th>{{ __('file.End Date') }}</th>
                                                <th>{{ __('file.Total Raw Scale') }}</th>
                                                <th>{{ __('file.Total Milling Cost') }}</th>
                                                <th>{{ __('file.Total Expense Cost') }}</th>
                                                <th>{{ __('file.Production Status') }}</th>
                                                <th>{{ __('file.Created By') }}</th>
                                                <th>{{ __('file.Action') }}</th>
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
    </div>
    @include('production::modal')
@endsection
@push('scripts')
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script>
        let table;
        $(document).ready(function() {
            table = $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "responsive": true,
                "bInfo": true,
                "bFilter": false,
                "lengthMenu": [
                    [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                    [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
                ],
                "pageLength": 25, //number of data show per page
                "language": {
                    processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable: '<strong class="text-danger">No Data Found</strong>',
                    infoEmpty: '',
                    zeroRecords: '<strong class="text-danger">No Data Found</strong>'
                },
                "ajax": {
                    "url": "{{ route('tenant.production.datatable.data') }}",
                    "type": "POST",
                    "data": function(data) {
                        data.invoice_no = $("#form-filter #invoice_no").val();
                        data.mill_id = $("#form-filter #mill_id option:selected").val();
                        data.start_date = $("#form-filter #start_date").val();
                        data.end_date = $("#form-filter #end_date").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                    "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                    "orderable": false,
                    "className": "text-center"
                }, ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            });
            $('#btn-filter').click(function() {
                table.ajax.reload();
            });
            $('#btn-reset').click(function() {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            $(document).on('click', '.change_status', function() {
                $('#production_id').val($(this).data('id'));
                $('#production_status').val($(this).data('status'));
                $('#production_status.selectpicker').selectpicker('refresh');
                $('#approve_status_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#approve_status_modal .modal-title').html(
                '<span>{{ __('file.Change Status') }}</span>');
                $('#approve_status_modal #status-btn').text('{{ __('file.Change') }}');
            });
            $(document).on('click', '#status-btn', function() {
                let productionId = $('#production_id').val();
                let productionStatus = $('#production_status').find(":selected").val();
                if (productionId && productionStatus) {
                    $.ajax({
                        url: "{{ route('tenant.production.change.status') }}",
                        type: "POST",
                        data: {
                            production_id: productionId,
                            production_status: productionStatus,
                            _token: _token
                        },
                        dataType: "JSON",
                        beforeSend: function() {
                            $('#status-btn').addClass(
                                'kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        complete: function() {
                            $('#status-btn').removeClass(
                                'kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        success: function(data) {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                $('#approve_status_modal').modal('hide');
                                table.ajax.reload(null, false);
                            }
                        },
                        error: function(xhr, ajaxOption, thrownError) {
                            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                                .responseText);
                        }
                    });
                }
            });
            $(document).on('click', '.delete_data', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('tenant.production.delete') }}";
                delete_data(id, url, table, row, name);
            });
        });
    </script>
@endpush
