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
                    <form method="POST" id="form-filter" class="col-md-12">
                        <div class="row justify-content-center">
                            <x-form.selectbox labelName="{{__('file.Product')}}" name="product_id" col="col-md-3" class="selectpicker">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <div class="col-md-2">
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
                                        <th>{{__('file.SL')}}</th>
                                        <th>{{__('file.Product Name')}}</th>
                                        <th>{{__('file.Product Code')}}</th>
                                        <th>{{__('file.Category')}}</th>
                                        <th>{{__('file.Stock Unit')}}</th>
                                        <th>{{__('file.Alert Qty')}}</th>
                                        <th>{{__('file.Stock Qty')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
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
        var table;
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
                "ajax"    : {
                    "url" : "{{route('product.alert.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.product_id    = $("#form-filter #product_id option:selected").val();
                        data._token        = _token;
                    }
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets"  : [0,1,2,3,4,5,6],
                    "className": "text-center"
                }],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            });
            $('#btn-filter').click(function () {
                if($('#product_id option:selected').val()) {
                    table.ajax.reload();
                }else{
                    notification('error','{{__('file.Please select warehouse')}}');
                }
            });
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
        });
    </script>
@endpush

