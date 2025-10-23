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
                <div class="card-toolbar">
                    @if (permission('product-add'))
                        <a href="{{ route('product.add') }}"  class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i>{{__('file.Add New')}}</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <form method="POST" id="form-filter" class="col-md-12 px-0">
                    <div class="row">
                        <x-form.textbox labelName="{{__('file.Product Name')}}" name="product_name" col="col-md-4" />
                        <x-form.textbox labelName="{{__('file.Product Code')}}" name="product_code" col="col-md-4" />
                        <x-form.selectbox labelName="{{__('file.Category')}}" name="category_id" col="col-md-4" class="selectpicker">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <x-form.selectbox labelName="{{__('file.Status')}}" name="status" col="col-md-4" class="selectpicker">
                            @foreach (STATUS as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <div class="col-md-4">
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
                        <div class="col-sm-12">
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>{{__('file.SL')}}</th>
                                        <th>{{__('file.Image')}}</th>
                                        <th>{{__('file.Product Name')}}</th>
                                        <th>{{__('file.Product Code')}}</th>
                                        <th>{{__('file.Category Name')}}</th>
                                        <th>{{__('file.Unit')}}</th>
                                        <th>{{__('file.Purchase Price')}}</th>
                                        <th>{{__('file.Sale Price')}}</th>
                                        <th>{{__('file.Alert Qty')}}</th>
                                        <th>{{__('file.Opening Stock')}}</th>
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
<script>
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
            "pageLength"   : 25,
            "language"     : {
                processing : `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                emptyTable : '<strong class="text-danger">{{__('file.No Data Found')}}</strong>',
                infoEmpty  : '',
                zeroRecords: '<strong class="text-danger">{{__('file.No Data Found')}}</strong>'
            },
            "ajax"    : {
                "url" : "{{route('product.datatable.data')}}",
                "type": "POST",
                "data": function (data) {
                    data.product_name  = $("#form-filter #product_name").val();
                    data.product_code  = $("#form-filter #product_code").val();
                    data.category_id   = $("#form-filter #category_id").find(":selected").val();
                    data.status        = $("#form-filter #status").val();
                    data._token        = _token;
                }
            },
            "columnDefs"       : [{
                    "targets"  : [0,1,2,3,4,5,6,7,8,9,10,11,12],
                    "orderable": false,
                    "className": "text-center"
                },
            ],
        });
        $('#btn-filter').click(function () {table.ajax.reload();});
        $('#btn-reset').click(function () {
            $('#form-filter')[0].reset();
            $('#form-filter .selectpicker').selectpicker('refresh');
            table.ajax.reload();
        });
        $(document).on('click', '.delete_data', function () {
            let id    = $(this).data('id');
            let name  = $(this).data('name');
            let row   = table.row($(this).parent('tr'));
            let url   = "{{ route('product.delete') }}";
            delete_data(id, url, table, row, name);
        });
    });
    </script>
@endpush
