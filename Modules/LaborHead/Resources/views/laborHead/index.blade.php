@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        @if (permission('labor-head-add'))
                            <a href="javascript:void(0);"
                                onclick="showFormModal('{{ __('file.Add New Labor Head') }}','{{ __('file.Save') }}')"
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
                            <x-form.textbox labelName="{{ __('file.Name') }}" name="name" col="col-md-3" />
                            <x-form.textbox labelName="{{ __('file.Mobile') }}" name="mobile" col="col-md-3" />
                            <x-form.selectbox labelName="{{ __('file.Status') }}" name="status" col="col-md-3"
                                class="selectpicker">
                                @foreach (STATUS as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <div class="col-md-3 pt-5 text-left">
                                <div style="margin-top:0px;">
                                    <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right"
                                        type="button" data-toggle="tooltip" data-theme="dark"
                                        title="{{ __('file.Reset') }}"><i class="fas fa-undo-alt"></i></button>
                                    <button id="btn-filter"
                                        class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right" type="button"
                                        data-toggle="tooltip" data-theme="dark" title="{{ __('file.Search') }}"><i
                                            class="fas fa-search"></i></button>
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
                                            <th>{{ __('file.SL') }}</th>
                                            <th>{{ __('file.Warehouse') }}</th>
                                            <th>{{ __('file.Labour Type') }}</th>
                                            <th>{{ __('file.Name') }}</th>
                                            <th>{{ __('file.Mobile') }}</th>
                                            <th>{{ __('file.Previous Balance') }}</th>
                                            <th>{{ __('file.Status') }}</th>
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
    @include('laborhead::laborHead.modal')
@endsection
@push('scripts')
    <script>
        function _(x) {
            return document.getElementById(x);
        }
        let table;
        $(document).ready(function() {
            table = $('#dataTable').DataTable({
                "processing": true, //Feature control the processing indicator
                "serverSide": true, //Feature control DataTable server side processing mode
                "order": [], //Initial no order
                "responsive": true, //Make table responsive in mobile device
                "bInfo": true, //TO show the total number of data
                "bFilter": false, //For datatable default search box show/hide
                "lengthMenu": [
                    [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                    [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
                ],
                "pageLength": 25, //number of data show per page
                "language": {
                    processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable: '<strong class="text-danger">{{ __('file.No Data Found') }}</strong>',
                    infoEmpty: '',
                    zeroRecords: '<strong class="text-danger">{{ __('file.No Data Found') }}</strong>'
                },
                "ajax": {
                    "url": "{{ route('labor.head.datatable.data') }}",
                    "type": "POST",
                    "data": function(data) {
                        data.name = $("#form-filter #name").val();
                        data.mobile = $("#form-filter #mobile").val();
                        data.status = $("#form-filter #status").val();
                        data.created_by = $("#form-filter #created_by").val();
                        data.modified_by = $("#form-filter #modified_by	").val()
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                    "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8],
                    "orderable": false,
                    "className": "text-center"
                }, ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>> <'row'<'col-sm-12'tr>> <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
                "buttons": [{
                        'extend': 'colvis',
                        'className': 'btn btn-secondary btn-sm text-white',
                        'text': '{{ __('file.Column') }}',
                        'columns': ':gt(0)'
                    },
                    {
                        "extend": 'print',
                        'text': '{{ __('file.Print') }}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "orientation": "landscape", //portrait
                        "pageSize": "A4", //A3,A5,A6,legal,letter
                        "exportOptions": {
                            columns: ':visible:not(:eq(6))'
                        },
                        customize: function(win) {
                            $(win.document.body).addClass('bg-white');
                            $(win.document.body).find('table thead').css({
                                'background': '#034d97'
                            });
                            $(win.document.body).find('table tfoot tr').css({
                                'background-color': '#034d97'
                            });
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).find('h1').css('font-size', '15px');
                            $(win.document.body).find('table').css('font-size', 'inherit');
                        },
                    }, {
                        "extend": 'csv',
                        'text': '{{ __('file.CSV') }}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "filename": "{{ strtolower(str_replace(' ', '-', $page_title)) }}-list",
                        "exportOptions": {
                            columns: ':visible:not(:eq(6))'
                        }
                    }, {
                        "extend": 'excel',
                        'text': '{{ __('file.Excel') }}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "filename": "{{ strtolower(str_replace(' ', '-', $page_title)) }}-list",
                        "exportOptions": {
                            columns: ':visible:not(:eq(6))'
                        }
                    }, {
                        "extend": 'pdf',
                        'text': '{{ __('file.PDF') }}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "filename": "{{ strtolower(str_replace(' ', '-', $page_title)) }}-list",
                        "orientation": "landscape", //portrait
                        "pageSize": "A4", //A3,A5,A6,legal,letter
                        "exportOptions": {
                            columns: ':visible:not(:eq(6))'
                        },
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10
                            doc.styles.tableHeader.fontSize = 7;
                            doc.pageMargins = [5, 5, 5, 5];
                        }
                    },
                ],
            });
            $('#btn-filter').click(function() {
                table.ajax.reload();
            });
            $('#btn-reset').click(function() {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            $(document).on('click', '#save-btn', function() {
                let form = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                let url = "{{ route('labor.head.store.or.update') }}";
                let id = _('update_id').value;
                let method;
                if (id) {
                    method = 'update';
                } else {
                    method = 'add';
                }
                store_or_update_data(table, method, url, formData);
            });
            $(document).on('click', '.edit_data', function() {
                // Fill inputs
                $('#update_id').val($(this).data('id'));
                $('#labor_name').val($(this).data('name'));
                $('#labor_mobile').val($(this).data('mobile'));
                $('#labor_previous_balance').val($(this).data('previous_balance'));

                // Select dropdown options
                $('#warehouse_id').val($(this).data('warehouse_id')).trigger('change');
                $('#labour_type_id').val($(this).data('labour_type_id')).trigger('change');

                // Open modal
                $('#store_or_update_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });

                // Update modal title and button text
                $('#store_or_update_modal .modal-title').html(
                    '<i class="fas fa-edit text-white"></i> <span>{{ __('file.Edit') }} ' + $(this)
                    .data('name') + '</span>'
                );
                $('#store_or_update_modal #save-btn').text('{{ __('file.Update') }}');
            });

            $(document).on('click', '.delete_data', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('labor.head.delete') }}";
                delete_data(id, url, table, row, name);
            });
        });
    </script>
@endpush
