@extends('layouts.app')
@section('title', $page_title)
@push('styles')<link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    @if(permission('purchase-add'))
                        <div class="card-toolbar"><a href="{{ route('purchase.add') }}"  class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i>{{__('file.Add New')}}</a></div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <x-form.textbox labelName="{{__('file.Invoice No')}}." name="invoice_no" col="col-md-3" />
                            <x-form.selectbox labelName="{{__('file.Party')}}" name="party_id" col="col-md-3" class="selectpicker">
                                @if (!$parties->isEmpty())
                                    @foreach ($parties as $value)
                                        <option value="{{ $value->id }}">{{ $value->name.' - '.$value->mobile }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            <div class="form-group col-md-3">
                                <label for="from_date">{{__('file.From Date')}}</label>
                                <input type="text" class="form-control date" name="from_date" id="from_date" readonly />
                            </div>
                            <div class="form-group col-md-3">
                                <label for="to_date">{{__('file.To Date')}}</label>
                                <input type="text" class="form-control date" name="to_date" id="to_date" readonly />
                            </div>
                            <div class="col-md-3">
                                <div style="margin-top:28px;">
                                    <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-left" type="button" data-toggle="tooltip" data-theme="dark" title="{{__('file.Reset')}}"><i class="fas fa-undo-alt"></i></button>
                                    <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-left" style="margin-left : 5px" type="button" data-toggle="tooltip" data-theme="dark" title="{{__('file.Search')}}"><i class="fas fa-search"></i></button>
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
                                            <th>{{__('file.Invoice No')}}</th>
                                            <th>{{__('file.Purchase Date')}}</th>
                                            <th>{{__('file.Party Type')}}</th>
                                            <th>{{__('file.Party Name')}}</th>
                                            <th>{{__('file.Purchase Status')}}</th>
                                            <th>{{__('file.Purchase Qty')}}</th>
                                            <th>{{__('file.Receive Qty')}}</th>
                                            <th>{{__('file.Return Qty')}}</th>
                                            <th>{{__('file.Purchase Amount')}}</th>
                                            <th>{{__('file.Receive Amount')}}</th>
                                            <th>{{__('file.Return Amount')}}</th>
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
    @include('purchase::modal')
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
                    "url" : "{{route('purchase.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.invoice_no           = $("#form-filter #invoice_no").val();
                        data.party_id             = $("#form-filter #party_id option:selected").val();
                        data.from_date            = $("#form-filter #from_date").val();
                        data.to_date              = $("#form-filter #to_date").val();
                        data._token               = _token;
                    }
                },
                "columnDefs"   : [{
                    "targets"  : [0,1,2,3,4,5,6,7,8,9,10,11,12,13],
                    "orderable": false,
                    "className": "text-center"
                },
                ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    for (let index = 6; index <= 11; index++) {
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
            });
            $('#btn-filter').click(function () { table.ajax.reload(); });
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            $(document).on('click', '.delete_data', function () {
                let id    = $(this).data('id');
                let name  = $(this).data('name');
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('purchase.delete') }}";
                delete_data(id, url, table, row, name);
            });
            $(document).on('click','.change_status',function(){
                $('#approve_status_form #purchase_id').val($(this).data('id'));
                $('#approve_status_form #purchase_status').val($(this).data('status'));
                $('#approve_status_form #purchase_status.selectpicker').selectpicker('refresh');
                $('#approve_status_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#approve_status_modal .modal-title').html('<span>{{__('file.Change Sale Status')}}</span>');
                $('#approve_status_modal #status-btn').text('{{__('file.Change Status')}}');
            });
            $(document).on('click','#status-btn',function(){
                let purchaseId     = $('#approve_status_form #purchase_id').val();
                let purchaseStatus =  $('#approve_status_form #purchase_status option:selected').val();
                if(purchaseId && purchaseStatus) {
                    $.ajax({
                        url       : "{{route('purchase.change.status')}}",
                        type      : "POST",
                        data      : {purchase_id:purchaseId,purchase_status:purchaseStatus,_token:_token},
                        dataType  : "JSON",
                        beforeSend: function(){
                            $('#status-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        complete  : function(){
                            $('#status-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        success   : function (data) {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                $('#approve_status_modal').modal('hide');
                                table.ajax.reload(null, false);
                            }
                        },
                        error     : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
                    });
                }
            });
        });
    </script>
@endpush

