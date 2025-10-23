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
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        @if (permission('expense-add'))
                            <a href="{{ route('expense.create') }}"  class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i> Add New</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="from_date">{{__('file.From Date')}}</label>
                                <input type="date" class="form-control" name="from_date" id="from_date"/>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="to_date">{{__('file.To Date')}}</label>
                                <input type="date" class="form-control" name="to_date" id="to_date"/>
                            </div>
                            <div class="col-md-3">
                                <div style="margin-top:28px;">
                                    <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon mr-2 float-left" type="button" data-toggle="tooltip" data-theme="dark" title="Reset"><i class="fas fa-undo-alt"></i></button>
                                    <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon float-left" type="button" data-toggle="tooltip" data-theme="dark" title="Search"><i class="fas fa-search"></i></button>
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
                                            <th>{{__('file.SL')}}</th>
                                            <th>{{__('file.Name')}}</th>
                                            <th>{{__('file.Date')}}</th>
                                            <th>{{__('file.Voucher No')}}</th>
                                            <th>{{__('file.Voucher Type')}}</th>
                                            <th>{{__('file.Narration')}}</th>
                                            <th>{{__('file.Debit')}}</th>
                                            <th>{{__('file.Credit')}}</th>
                                            <th>{{__('file.Status')}}</th>
                                            <th>{{__('file.Created By')}}</th>
                                            <th>{{__('file.Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                        <tr class="bg-primary">
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th style="text-align: right !important;font-weight:bold;color:white;">
                                                {{ 'Total' }}</th>
                                            <th style="text-align: center !important;font-weight:bold;color:white;"></th>
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
    </div>
@endsection
@push('scripts')
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        let table;
        $(document).ready(function(){
            $('.date').datetimepicker({format: 'YYYY-MM-DD',ignoreReadonly: true});
            table = $('#dataTable').DataTable({
                "processing": true, //Feature control the processing indicator
                "serverSide": true, //Feature control DataTable server side processing mode
                "order"     : [], //Initial no order
                "responsive": false, //Make table responsive in mobile device
                "bInfo"     : true, //TO show the total number of data
                "bFilter"   : false, //For datatable default search box show/hide
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
                    "url" : "{{route('expense.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.from_date            = $("#form-filter #from_date").val();
                        data.to_date              = $("#form-filter #to_date").val();
                        data._token               = _token;
                    }
                },
                "columnDefs"   : [{
                    "targets"  : [0,1,2,3,4,5,6,7,8,9,10],
                    "orderable": false,
                    "className": "text-center"
                }],
                "dom"    : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    for (let index = 6; index <= 7; index++) {
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
                },
                "buttons": [{
                    'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column','columns': ':gt(0)'
                }, {
                    "extend"       : 'print',
                    'text'         :'Print',
                    'className'    :'btn btn-secondary btn-sm text-white',
                    "title"        : "{{ $page_title }} List",
                    "orientation"  : "landscape", //portrait
                    "pageSize"     : "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns    : ':visible:not(:eq(8))'
                    },
                    customize: function (win) {
                        $(win.document.body).addClass('bg-white');
                        $(win.document.body).find('table thead').css({'background':'#034d97'});
                        $(win.document.body).find('table tfoot tr').css({'background-color':'#034d97'});
                        $(win.document.body).find('h1').css('text-align', 'center');
                        $(win.document.body).find('h1').css('font-size', '15px');
                        $(win.document.body).find('table').css( 'font-size', 'inherit' );
                    },
                }, {
                    "extend"       : 'csv',
                    'text'         :'CSV',
                    'className'    :'btn btn-secondary btn-sm text-white',
                    "title"        : "{{ $page_title }} List",
                    "filename"     : "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    "exportOptions": {
                        columns    : ':visible:not(:eq(8))'
                    }
                }, {
                    "extend"       : 'excel',
                    'text'         :'Excel',
                    'className'    :'btn btn-secondary btn-sm text-white',
                    "title"        : "{{ $page_title }} List",
                    "filename"     : "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    "exportOptions": {
                        columns    : ':visible:not(:eq(8))'
                    }
                }, {
                    "extend"       : 'pdf',
                    'text'         :'PDF',
                    'className'    :'btn btn-secondary btn-sm text-white',
                    "title"        : "{{ $page_title }} List",
                    "filename"     : "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    "orientation"  : "landscape", //portrait
                    "pageSize"     : "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns    : ':visible:not(:eq(8))'
                    },
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10
                        doc.styles.tableHeader.fontSize = 7;
                        doc.pageMargins = [5,5,5,5];
                    }
                },
                ],
            });
            $('#btn-filter').click(function () {table.ajax.reload();});
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter #start_date').val("");
                $('#form-filter #end_date').val("");
                $('#form-filter #voucher_no').val("");
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            $(document).on('click', '.change_status', function () {
                let id     = $(this).data('id');
                let name   = $(this).data('name');
                let status = $(this).data('status');
                let row    = table.row($(this).parent('tr'));
                let url    = "{{ route('expense.change.status') }}";
                change_status(id, url, table, row, name, status);
            });
            $(document).on('click', '.delete_data', function () {
                let id    = $(this).data('id');
                let name  = $(this).data('name');
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('expense.delete') }}";
                delete_data(id, url, table, row, name);
            });
        });
    </script>
@endpush
