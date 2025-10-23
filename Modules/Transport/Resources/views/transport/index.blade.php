@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    <div class="card-toolbar">
                        @if (permission('transport-add'))
                            <a href="{{ route('transport.add') }}" class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i> {{__('file.Add New')}}</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <x-form.textbox labelName="{{__('file.Voucher No')}}." name="voucher_no" col="col-md-3" />
                            <x-form.selectbox labelName="{{__('file.Truck No')}}" name="truck_id" col="col-md-3" required="required" class="selectpicker">
                                @if (!$trucks->isEmpty())
                                    @foreach ($trucks as $value)
                                        <option value="{{ $value->id }}">{{ $value->truck_no }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            <div class="col-md-3">
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
                            <div class="col-sm-12 table-responsive">
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th>{{__('file.SL')}}</th>
                                        <th>{{__('file.Date')}}</th>
                                        <th>{{__('file.Invoice No')}}.</th>
                                        <th>{{__('file.Party Type')}}</th>
                                        <th>{{__('file.Party Name')}}</th>
                                        <th>{{__('file.Truck No')}}</th>
                                        <th>{{__('file.Driver Name')}}</th>
                                        <th>{{__('file.Rent')}}</th>
                                        <th>{{__('file.Amount')}}</th>
                                        <th>{{__('file.Total Expense')}}</th>
                                        <th>{{__('file.Income')}}</th>
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
@endsection
@push('scripts')
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        var table;
        $(document).ready(function () {
            $('.date').datetimepicker({
                format: 'YYYY-MM-DD',
                ignoreReadonly: true
            });
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
                "language": {
                    processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable: '<strong class="text-danger">{{__('file.No Data Found')}}</strong>',
                    infoEmpty: '',
                    zeroRecords: '<strong class="text-danger">{{__('file.No Data Found')}}</strong>'
                },
                "ajax": {
                    "url" : "{{route('transport.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.voucher_no = $("#form-filter #voucher_no").val();
                        data.truck_id   = $("#form-filter #truck_id option:selected").val();
                        data._token     = _token;
                    }
                },
                "columnDefs"   : [{
                    "targets"  : [0,1,2,3,4,5,6,7,8,9,10,11,12,13],
                    "orderable": false,
                    "className": "text-center"
                }],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            });
            $('#btn-filter').click(function () {
                table.ajax.reload();
            });
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            $(document).on('click', '.delete_data', function () {
                let id   = $(this).data('id');
                let name = $(this).data('name');
                let row  = table.row($(this).parent('tr'));
                let url  = "{{ route('transport.delete') }}";
                delete_data(id, url, table, row, name);
            });
            $(document).on('click', '.change_status', function () {
                let id     = $(this).data('id');
                let name   = $(this).data('name');
                let status = $(this).data('status');
                let row    = table.row($(this).parent('tr'));
                let url    = "{{ route('transport.change.status') }}";
                change_status(id, url, table, row, name, status);
            });
        });
    </script>
@endpush
