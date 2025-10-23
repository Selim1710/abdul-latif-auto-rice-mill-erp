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
                    <div class="card-toolbar"><a href="{{ route('stock.transfer.add') }}"  class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i>{{__('file.Add New')}}</a></div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">

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
                                            <th>{{__('file.SL')}}</th>
                                            <th>{{__('file.Invoice No')}}</th>
                                            <th>{{__('file.Transfer Date')}}</th>
                                            <th>{{__('file.Transfer Company')}}</th>
                                            <th>{{__('file.Receive Company')}}</th>
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
    </div>
@endsection
@push('scripts')
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        let table;
        $(document).ready(function(){
            $('.date').datetimepicker({format: 'YYYY-MM-DD',ignoreReadonly: true});
            table = $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "order"     : [],
                "responsive": false,
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
                "ajax": {
                    "url": "{{route('stock.transfer.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        // data.invoice_no           = $("#form-filter #invoice_no").val();
                        // data.from_date            = $("#form-filter #from_date").val();
                        // data.to_date              = $("#form-filter #to_date").val();
                        // data.customer_id          = $("#form-filter #customer_id option:selected").val();
                        // data.sale_status          = $("#form-filter #sale_status option:selected").val();
                        data._token               = _token;
                    }
                },
                "columnDefs": [{
                    "targets"  : [0,1,2,3,4,5,6,7],
                    "orderable": false,
                    "className": "text-center"
                },
                ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            });
            $('#btn-filter').click(function () { table.ajax.reload(); });
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            $(document).on('click', '.change_status', function () {
                let id     = $(this).data('id');
                let name   = $(this).data('name');
                let status = $(this).data('status');
                let row    = table.row($(this).parent('tr'));
                let url    = "{{ route('stock.transfer.change.status') }}";
                change_status(id, url, table, row, name, status);
            });
            $(document).on('click', '.delete_data', function () {
                let id    = $(this).data('id');
                let name  = $(this).data('name');
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('stock.transfer.delete') }}";
                delete_data(id, url, table, row, name);
            });
        });
    </script>
@endpush
