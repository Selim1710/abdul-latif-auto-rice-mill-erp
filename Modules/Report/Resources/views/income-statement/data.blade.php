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
                <div class="card-header flex-wrap py-4">
                    <form method="POST" id="form-filter" action="{{ route('income.statement.report') }}" class="col-md-12 px-0">
                        @csrf
                        <div class="row justify-content-center">

                            <div class="col-md-3" id="">
                                <label class="col-from-label">{{ 'Date' }} <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" value="{{ date("Y-m-d") }}"
                                       class="form-control">
                            </div>
                            <div class="col-md-2">
                                <div style="margin-top:28px;">
                                    <div style="margin-top:28px;">
                                        <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button"
                                                data-toggle="tooltip" data-theme="dark" title="{{__('file.Reset')}}">
                                            <i class="fas fa-undo-alt"></i></button>
                                        <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right btn-search" type="submit"
                                                data-toggle="tooltip" data-theme="dark" title="{{__('file.Search')}}">
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
                            <div class="col-sm-12" id="report">

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
@endpush
