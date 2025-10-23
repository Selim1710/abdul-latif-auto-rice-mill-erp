@extends('layouts.app')
@section('title', $page_title)
@push('styles')<link href="{{asset('css/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title"><h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3></div>
                    <div class="card-toolbar">
                        @if (permission('loan-add'))<a href="{{route('loan.add')}}" class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i> Add New</a>@endif
                    </div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="vendor_id">Loan Categories</label>
                                <select class="form-control selectpicker" id="loan_categories_id" name="loan_categories_id" data-live-search="true">
                                    <option value="">Select Loan Categories</option>
                                    @foreach($loanCategories as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="bank_id">Bank</label>
                                <select class="form-control selectpicker" id="bank_id" name="bank_id" data-live-search="true">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $value)
                                        <option value="{{$value->id}}">{{$value->bank_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-form.textbox labelName="Total Amount" name="total_amount" col="col-md-2" placeholder="Enter Total Amount" />
                            <x-form.textbox labelName="Monthly Installment" name="monthly_installment" col="col-md-3" placeholder="Enter Monthly Installment" />
                            <div class="col-md-2">
                                <div style="margin-top:28px;">
                                    <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button" data-toggle="tooltip" data-theme="dark" title="Reset"><i class="fas fa-undo-alt"></i></button>
                                    <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right" type="button" data-toggle="tooltip" data-theme="dark" title="Search"><i class="fas fa-search"></i></button>
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
                                        <th>Sl</th>
                                        <th>Categories</th>
                                        <th>Bank</th>
                                        <th>Branch</th>
                                        <th>Account</th>
                                        <th>Total Amount</th>
                                        <th>Monthly Installment</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Action</th>
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
    @include('loan::loan.modal')
@endsection
@push('scripts')
    <script src="{{asset('js/knockout-3.4.2.js')}}"></script>
    <script src="{{asset('js/daterangepicker.min.js')}}"></script>
    <script>
        function _(x){
            return document.getElementById(x);
        }
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
                "pageLength": 25,
                "language"  : {
                    processing : `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable : '<strong class="text-danger">No Data Found</strong>',
                    infoEmpty  : '',
                    zeroRecords: '<strong class="text-danger">No Data Found</strong>'
                },
                "ajax": {
                    "url" : "{{route('loan.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.vendor_id      = $("#form-filter #vendor_id").val();
                        data.car_seat_id    = $("#form-filter #car_seat_id").val();
                        data.name           = $("#form-filter #name").val();
                        data._token         = _token;
                    }
                },
                "columnDefs"   : [{
                    "targets"  : [0,1,2,3,4,5,6,7,8,9],
                    "orderable": false,
                    "className": "text-center"
                },],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            });
            $('#btn-filter').click(function () { table.ajax.reload(); });
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            $(document).on('click','.change_status',function(){
                $('#approve_status_form #loan_id').val($(this).data('id'));
                $('#approve_status_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#approve_status_modal .modal-title').html('<span>{{__('file.Change Loan Status')}}</span>');
                $('#approve_status_modal #loan-status-btn').text('{{__('file.Change Status')}}');

            });
            $(document).on('click','#loan-status-btn',function(){
                if(_('start_date').value != '' && _('loan_status').value != ''){
                    $.ajax({
                        url: "{{route('loan.change.status')}}",
                        type: "POST",
                        data: {loan_id:_('loan_id').value,start_date:_('start_date').value,loan_status:_('loan_status').value,_token:_token},
                        dataType: "JSON",
                        beforeSend: function(){
                            $('#loan-status-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        complete: function(){
                            $('#loan-status-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        success: function (data) {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                $('#approve_status_modal').modal('hide');
                                table.ajax.reload(null, false);
                            }
                        },
                        error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
                    });
                }else{
                    notification('error','Input Field Empty');
                }
            });
            $(document).on('click', '.delete_data', function () {
                let id    = $(this).data('id');
                let name  = $(this).data('name');
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('loan.delete') }}";
                delete_data(id, url, table, row, name);
            });
        });
    </script>
@endpush
