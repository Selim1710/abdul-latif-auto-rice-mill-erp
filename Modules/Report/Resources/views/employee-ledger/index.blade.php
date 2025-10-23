@extends('layouts.app')
@section('title', $page_title)
@section('content')

    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <!--begin::Notice-->
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                </div>
            </div>
            <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        @csrf
                        <div class="row">
                            <div class="col-md-3" id="">
                                <label class="col-from-label">{{ 'From Date' }} <span class="text-danger">*</span></label>
                                <input type="date" name="from_date" id="from_date" value="{{ date("Y-m-d") }}" class="form-control">
                            </div>
                            <div class="col-md-3" id="">
                                <label class="col-from-label">{{ 'To Date' }} <span class="text-danger">*</span></label>
                                <input type="date" name="to_date" id="to_date" value="{{ date("Y-m-d") }}" class="form-control">
                            </div>
                            <div class="col-md-3" id="">
                                <label class="col-from-label">{{ 'Employee' }} <span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" name="employee_id" id="employee_id" data-live-search="true" required>
                                    @foreach ($employees as $value)
                                        <option value="{{ $value->coa->id }}">{{ $value->name }}-{{ $value->employee_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div style="margin-top:28px;">
                                    <div style="margin-top:28px;">
                                        <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-left mr-1" type="button" data-toggle="tooltip" data-theme="dark"
                                            title="{{ __('file.Reset') }}">
                                            <i class="fas fa-undo-alt"></i></button>
                                        <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-left btn-search" type="button" data-toggle="tooltip" data-theme="dark"
                                            title="{{ __('file.Search') }}">
                                            <i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <form id="salary_store_or_update">
                                    @csrf
                                    <div id="report" style="width: 100%;margin:0;padding:0;">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--end: Datatable-->
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
@push('scripts')
    <script>

        $(document).on('click', '.btn-search', function() {
            report();
        });

        function report() {
            let employee_id = Number($('#employee_id').val());
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();
            if (employee_id > 0) {
                $.ajax({
                    url: "{{ route('employee.ledger.data') }}",
                    type: "POST",
                    data: {
                        employee_id: employee_id,
                        from_date: from_date,
                        to_date: to_date,
                        _token: _token
                    },
                    success: function(data) {
                        $('#report').empty();
                        $('#report').append(data);
                        $('#dataTable .selectpicker').selectpicker('refresh');
                    },
                    error: function(xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            } else {
                $('#report').empty();
                notification('error', 'Please select Employee');
            }
        }
    </script>
@endpush
