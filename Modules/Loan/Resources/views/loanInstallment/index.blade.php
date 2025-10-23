@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link href="{{asset('css/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    <div class="card-toolbar">@if (permission('loan-installment-add'))<a href="{{route('loan.installment.add')}}" class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i> Add New</a>@endif</div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row justify-content-center">
                            {{--                            <div class="col-md-3">--}}
                            {{--                                <label for="supplier_id">{{__('file.Supplier')}}</label>--}}
                            {{--                                <select class="form-control" id="supplier_id" name="supplier_id">--}}
                            {{--                                    <option value="">{{__('file.Select Please')}}</option>--}}
                            {{--                                    @if (!$suppliers->isEmpty())--}}
                            {{--                                        @foreach ($suppliers as $supplier)--}}
                            {{--                                            <option value="{{ $supplier->id }}" data-coaid="{{ $supplier->coa->id }}" data-name="{{ $supplier->name }}">{{ $supplier->name.'('.$supplier->company_name.')' }}</option>--}}
                            {{--                                        @endforeach--}}
                            {{--                                    @endif--}}
                            {{--                                </select>--}}
                            {{--                            </div>--}}
{{--                            <div class="form-group col-md-3">--}}
{{--                                <label for="name">{{__('file.Choose Your Date')}}</label>--}}
{{--                                <div class="input-group">--}}
{{--                                    <input type="text" class="form-control daterangepicker-filed">--}}
{{--                                    <input type="hidden" id="from_date" name="from_date" >--}}
{{--                                    <input type="hidden" id="to_date" name="to_date" >--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-md-1">
                                <div style="margin-top:28px;">
                                    <div style="margin-top:28px;">
                                        <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button" data-toggle="tooltip" data-theme="dark" title="{{__('file.Reset')}}"><i class="fas fa-undo-alt"></i></button>
                                        <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right" type="button" data-toggle="tooltip" data-theme="dark" title="{{__('file.Search')}}"><i class="fas fa-search"></i></button>
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
                                        <th>{{__('file.Date')}}</th>
                                        <th>{{__('file.Narration')}}</th>
                                        <th>{{__('file.Voucher No')}}</th>
                                        <th>{{__('file.Debit')}}</th>
                                        <th>{{__('file.Credit')}}</th>
                                        <th>{{__('file.Balance')}}</th>
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
    <script src="{{asset('js/knockout-3.4.2.js')}}"></script>
    <script src="{{asset('js/daterangepicker.min.js')}}"></script>
    <script>
        function _(x){
            return document.getElementById(x);
        }
        let table;
        $('.daterangepicker-filed').daterangepicker({
            callback: function(startDate, endDate, period){
                var start_date = startDate.format('YYYY-MM-DD');
                var end_date   = endDate.format('YYYY-MM-DD');
                var title      = start_date + ' To ' + end_date;
                $(this).val(title);
                $('input[name="from_date"]').val(start_date);
                $('input[name="to_date"]').val(end_date);
            }
        });
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
                "ajax": {
                    "url" : "{{route('loan.installment.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.supplier_id = $("#form-filter #supplier_id option:selected").val();
                        data._token      = _token;
                    }
                },
                "columnDefs": [{
                    "targets"  : [0,1,2,3,4,5],
                    "orderable": false,
                    "className": "text-center"
                }],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            });
            $('#btn-filter').click(function () {table.ajax.reload();});
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter #from_date').val("");
                $('#form-filter #to_date').val("");
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
        });
    </script>
@endpush

