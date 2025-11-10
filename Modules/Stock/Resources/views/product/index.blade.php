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
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row justify-content-center">
                            <x-form.selectbox labelName="{{ __('file.Category') }}" name="category_id" col="col-md-2"
                                class="selectpicker">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <x-form.selectbox labelName="{{ __('file.Product') }}" name="product_id" col="col-md-2"
                                class="selectpicker">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </x-form.selectbox>
                            
                            <x-form.selectbox labelName="{{ __('file.Company') }}" name="warehouse_id" col="col-md-2"
                                class="selectpicker">
                                <option value="0" selected>{{ __('file.All Company') }}</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </x-form.selectbox>

                            <x-form.selectbox labelName="{{ __('file.Party') }}" name="party_id" col="col-md-2"
                                class="selectpicker">
                                <option value="0" selected>{{ __('file.All Party') }}</option>
                                @foreach ($parties as $party)
                                    <option value="{{ $party->id }}">{{ $party->name }}</option>
                                @endforeach
                            </x-form.selectbox>

                            <div class="col-md-0">
                                <div style="margin-top:28px;">
                                    <div style="margin-top:28px;">
                                        <button id="btn-reset"
                                            class="btn btn-danger btn-sm btn-elevate btn-icon mr-2 float-left"
                                            type="button" data-toggle="tooltip" data-theme="dark"
                                            title="{{ __('file.Reset') }}"><i class="fas fa-undo-alt"></i></button>
                                        <button id="btn-filter"
                                            class="btn btn-primary btn-sm btn-elevate btn-icon  float-left" type="button"
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
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>{{ __('file.SL') }}</th>
                                            <th>{{ __('file.Company') }}</th>
                                            <th>{{ __('file.Purchase Invoice') }}</th>
                                            <th>{{ __('file.Party') }}</th>
                                            <th>{{ __('file.Product Name') }}</th>
                                            <th>{{ __('file.Product Code') }}</th>
                                            <th>{{ __('file.Category') }}</th>
                                            <th>{{ __('file.Unit') }}</th>
                                            <th>{{ __('file.Purchase Price') }}</th>
                                            <th>{{ __('file.Sale Price') }}</th>
                                            <th>{{ __('file.Scale') }}</th>
                                            <th>{{ __('file.Qty') }}</th>
                                            <th>{{ __('file.Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-primary">
                                            <th colspan="8"></th>
                                            <th style="text-align: right !important;font-weight:bold;color:white;">
                                                {{ __('file.Total') }}</th>
                                            <th style="text-align: center !important;font-weight:bold;color:white;"></th>
                                            <th style="text-align: center !important;font-weight:bold;color:white;"></th>
                                            <th style="text-align: center !important;font-weight:bold;color:white;"></th>
                                            <th style="text-align: center !important;font-weight:bold;color:white;"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
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
                "pageLength": 25,
                "language": {
                    processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable: '<strong class="text-danger">{{ __('file.No Data Found') }}</strong>',
                    infoEmpty: '',
                    zeroRecords: '<strong class="text-danger">{{ __('file.No Data Found') }}</strong>'
                },
                "ajax": {
                    "url": "{{ route('product.stock.datatable.data') }}",
                    "type": "POST",
                    "data": function(data) {
                        data.category_id = $("#form-filter #category_id option:selected").val();
                        data.product_id = $("#form-filter #product_id option:selected").val();
                        data.warehouse_id = $("#form-filter #warehouse_id option:selected").val();
                        data.party_id = $("#form-filter #party_id option:selected").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10,11],
                    "className": "text-center"
                }],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
                "buttons": [{
                        'extend': 'colvis',
                        'className': 'btn btn-secondary btn-sm text-white',
                        'text': '{{ __('file.Column') }}',
                        'columns': ':gt(0)'
                    }, {
                        "extend": 'print',
                        'text': '{{ __('file.Print') }}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "orientation": "landscape",
                        "pageSize": "A4",
                        "exportOptions": {
                            columns: ':visible:not(:eq(3))',
                            columns: ':visible:not(:eq(4))'
                        },
                        customize: function(win) {
                            $(win.document.body).addClass('bg-white');
                            $(win.document.body).find('table thead').css({
                                'background': '#034d97'
                            });
                            $(win.document.body).find('table thead th').css({
                                'font-size': '15px',
                                'font-weight': 'bold'
                            });
                            $(win.document.body).find('table tfoot tr').css({
                                'background-color': '#034d97',
                                'font-size': '20px',
                                'font-weight': 'bold'
                            });
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).find('h1').css('font-size', '15px');
                            $(win.document.body).find('table').css('font-size', 'inherit');
                        },
                        footer: true
                    },
                    {
                        "extend": 'csv',
                        'text': '{{ __('file.CSV') }}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "filename": 'Customers Ledger From ' + $('#form-filter #start_date').val() +
                            ' To ' + $('#form-filter #end_date').val(),
                        "exportOptions": {
                            columns: function(index, data, node) {
                                return table.column(index).visible();
                            }
                        },
                        footer: true
                    }, {
                        "extend": 'excel',
                        'text': '{{ __('file.Excel') }}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "filename": 'Customers Ledger From ' + $('#form-filter #start_date').val() +
                            ' To ' + $('#form-filter #end_date').val(),
                        "exportOptions": {
                            columns: function(index, data, node) {
                                return table.column(index).visible();
                            }
                        },
                        footer: true
                    }, {
                        "extend": 'pdf',
                        'text': '{{ __('file.PDF') }}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "filename": 'Customers Ledger From ' + $('#form-filter #start_date').val() +
                            ' To ' + $('#form-filter #end_date').val(),
                        "orientation": "portrait",
                        "pageSize": "A4",
                        "exportOptions": {
                            columns: function(index, data, node) {
                                return table.column(index).visible();
                            }
                        },
                        footer: true,
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10
                            doc.styles.tableHeader.fontSize = 7;
                            doc.styles.tableFooter.fontSize = 7;
                            doc.pageMargins = [5, 5, 5, 5];
                        }
                    },
                ],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    for (let index = 8; index <= 12; index++) {
                        total = api
                            .column(index)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        pageTotal = api
                            .column(index, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        $(api.column(index).footer()).html('= ' + number_format(pageTotal));
                    }
                }
            });
            $('#btn-filter').click(function() {
                if ($('#warehouse_id option:selected').val()) {
                    table.ajax.reload();
                } else {
                    notification('error', '{{ __('file.Please select warehouse') }}');
                }
            });
            $('#btn-reset').click(function() {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
        });
    </script>
@endpush
