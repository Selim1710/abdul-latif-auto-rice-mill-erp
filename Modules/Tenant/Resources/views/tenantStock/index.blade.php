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
                        <div class="row">
                            <x-form.selectbox labelName="{{ __('file.Tenant') }}" name="tenant_id" col="col-md-3"
                                class="selectpicker">
                                <option value="0" selected>{{ __('file.Please Select') }}</option>
                                @foreach ($tenants as $tenant)
                                    <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <x-form.selectbox labelName="{{ __('file.Company') }}" name="warehouse_id" col="col-md-3"
                                class="selectpicker">
                                <option value="0" selected>{{ __('file.All Company') }}</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <x-form.selectbox labelName="{{ __('file.Product') }}" name="product_id" col="col-md-3"
                                class="selectpicker">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <x-form.selectbox labelName="{{ __('file.Category') }}" name="category_id" col="col-md-3"
                                class="selectpicker">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <x-form.selectbox labelName="{{ __('Batch') }}" name="batch_number" col="col-md-3"
                                class="selectpicker">
                                @foreach ($batch_numbers as $batch_number)
                                    <option value="{{ $batch_number }}">{{ $batch_number }}</option>
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
                                            <th>{{ __('file.Batch No') }}</th>
                                            <th>{{ __('file.Tenant') }}</th>
                                            <th>{{ __('file.Company') }}</th>
                                            <th>{{ __('file.Product Name') }}</th>
                                            <th>{{ __('file.Product Code') }}</th>
                                            <th>{{ __('file.Category') }}</th>
                                            <th>{{ __('file.Stock Unit') }}</th>
                                            <th>{{ __('file.Production Type') }}</th>
                                            <th>{{ __('file.Qty') }}</th>
                                            <th>{{ __('file.Scale') }}</th>
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
                    "url": "{{ route('tenant.stock.datatable.data') }}",
                    "type": "POST",
                    "data": function(data) {
                        data.tenant_id = $("#form-filter #tenant_id option:selected").val();
                        data.warehouse_id = $("#form-filter #warehouse_id option:selected").val();
                        data.product_id = $("#form-filter #product_id option:selected").val();
                        data.category_id = $("#form-filter #category_id option:selected").val();
                        data.batch_number = $("#form-filter #batch_number option:selected").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    "className": "text-center"
                }],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    for (let index = 9; index <= 10; index++) {
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
