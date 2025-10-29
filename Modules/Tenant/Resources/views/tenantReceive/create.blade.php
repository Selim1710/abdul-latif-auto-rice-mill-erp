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
                    @if (permission('tenant-receive-access'))
                        <div class="card-toolbar"><a href="{{ route('tenant.receive.product') }}"
                                class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i>
                                {{ __('file.Back') }}</a></div>
                    @endif
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form action="" id="tenant_receive_product_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-3 required">
                                    <label for="date">{{ __('file.Date') }}</label>
                                    <input type="date" class="form-control date" id="date" name="date"
                                        value="{{ date('Y-m-d') }}" />
                                </div>

                                <div class="form-group col-md-3 required">
                                    <label for="tenant_id">{{ __('file.Tenant') }}</label>
                                    <select class="form-control selectpicker" id="tenant_id" name="tenant_id"
                                        data-live-search = "true">
                                        <option value="">{{ __('file.Please Select') }}</option>
                                        @foreach ($tenants as $tenant)
                                            <option value="{{ $tenant->id }}">
                                                {{ $tenant->name . '(' . $tenant->mobile . ')' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3 required">
                                    <label for="batch_no">{{ __('file.Batch No') }}</label>
                                    <input type="text" class="form-control" id="batch_no" name="batch_no" readonly />
                                </div>

                                <div class="form-group col-md-3 required">
                                    <label for="memo_no">{{ __('file.Invoice No') }}.</label>
                                    <input type="text" class="form-control bg-primary text-center" id="invoice_no"
                                        name="invoice_no" value="{{ $invoice_no }}" readonly />
                                </div>

                                <div class="col-md-12">
                                    <hr style="border-top: 5px dotted cadetblue;" />
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <table class="table" id="tenantReceiveTable">
                                            <thead class="bg-primary">
                                                <tr class="text-center">
                                                    <th>{{ __('file.Company') }}</th>
                                                    <th>{{ __('file.Category') }}</th>
                                                    <th>{{ __('file.Product') }}</th>
                                                    <th>{{ __('file.Unit') }}</th>
                                                    <th>{{ __('file.Qty') }}</th>
                                                    <th>{{ __('file.Scale') }}</th>
                                                    <th>{{ __('file.Rec Qty') }}</th>
                                                    <th>{{ __('file.Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-center">
                                                    <td>
                                                        <select class="form-control selectpicker text-center"
                                                            id="tenant_receive_0_warehouse_id"
                                                            name="tenant_receive[0][warehouse_id]"
                                                            data-live-search = "true">
                                                            <option value="">{{ __('Please Select') }}</option>
                                                            @foreach ($warehouses as $warehouse)
                                                                <option value="{{ $warehouse->id }}">
                                                                    {{ $warehouse->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control selectpicker category text-center"
                                                            id="tenant_receive_0_category_id"
                                                            data-product_id="tenant_receive_0_product_id"
                                                            data-live-search = "true">
                                                            <option value="">{{ __('Please Select') }}</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}">
                                                                    {{ $category->category_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><select class="form-control selectpicker product text-center"
                                                            id="tenant_receive_0_product_id"
                                                            name="tenant_receive[0][product_id]"
                                                            data-unit_show="tenant_receive_0_unit_show"
                                                            data-unit_id="tenant_receive_0_unit_id"
                                                            data-live-search = "true"></select></td>
                                                    <td><input class="form-control bg-primary text-center"
                                                            id="tenant_receive_0_unit_show" readonly /><input type="hidden"
                                                            id="tenant_receive_0_unit_id" /></td>
                                                    <td><input class="form-control qty text-center"
                                                            id="tenant_receive_0_qty" name="tenant_receive[0][qty]"
                                                            data-product_id="tenant_receive_0_product_id"
                                                            data-unit_id="tenant_receive_0_unit_id"
                                                            data-scale="tenant_receive_0_scale" /></td>
                                                    <td><input class="form-control scale text-center"
                                                            id="tenant_receive_0_scale" name="tenant_receive[0][scale]"
                                                            data-product_id="tenant_receive_0_product_id"
                                                            data-unit_id="tenant_receive_0_unit_id"
                                                            data-qty="tenant_receive_0_qty" /> </td>
                                                    <td><input class="form-control" id="tenant_receive_0_rec_qty"
                                                            name="tenant_receive[0][rec_qty]" /></td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm addRaw"><i
                                                                class="fas fa-plus-circle"></i></button><br />
                                                        <button type = "button" class = "btn btn-danger btn-sm deleteRaw"
                                                            style="margin-top:3px"><i
                                                                class = "fas fa-minus-circle"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="note">{{ __('file.Note') }}</label>
                                    <textarea class="form-control" id="note" name="note"></textarea>
                                </div>
                                <div class="form-group col-md-12 text-center pt-5">
                                    <a class = "btn btn-danger btn-sm mr-3"
                                        href="{{ route('tenant.receive.product.add') }}"><i
                                            class="fas fa-sync-alt"></i>{{ __('file.Reset') }}</a>
                                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn"
                                        onclick="storeData()"><i class="fas fa-save"></i>{{ __('file.Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let i = 1;

        function _(x) {
            return document.getElementById(x);
        }

        $(document).on('change', '#tenant_id', function() {
            let tenant_id = $(this).find(":selected").val();
            if (tenant_id != '') {
                $.ajax({
                    url: "{{ route('tenant-batch-no') }}",
                    data: {
                        tenant_id: tenant_id
                    },
                    method: 'GET',
                    success: function(data) {
                        $('#batch_no').val(data);
                    }
                });
            }
        });

        $(document).on('change', '.category', function() {
            let html;
            let categoryId = $(this).find(":selected").val();
            let productId = $(this).data('product_id');
            $('#' + productId + '').empty();
            $('.selectpicker').selectpicker('refresh');
            if (categoryId != '') {
                $.ajax({
                    url: "{{ route('tenant.receive.product.category') }}",
                    data: {
                        categoryId: categoryId
                    },
                    method: 'GET',
                    success: function(data) {
                        if (data != '') {
                            html = `<option value="">Select Please</option>`;
                            $.each(data, function(key, value) {
                                html += '<option value="' + value.id + '">' + value
                                    .product_name + '</option>';
                            });
                            $('#' + productId + '').append(html);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                });
            }
        });

        $(document).on('change', '.product', function() {
            let productId = $(this).find(":selected").val();
            let unitId = $(this).data('unit_id');
            let unitShow = $(this).data('unit_show');
            if (productId != '') {
                $.ajax({
                    url: "{{ route('tenant.receive.product.details') }}",
                    data: {
                        productId: productId
                    },
                    method: 'GET',
                    success: function(data) {
                        if (data) {
                            $('#' + unitId + '').val(data.unit.unit_name);
                            $('#' + unitShow + '').val(data.unit.unit_name + '(' + data.unit.unit_code +
                                ')');
                        }
                    }
                });
            }
        });
        $(document).on('input', '.qty', function() {
            let productId = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let unitId = $(this).data('unit_id');
            let scale = $(this).data('scale');
            if (productId != '') {
                _(scale).value = $(this).val() * _(unitId).value;
            } else {
                $(this).val('');
                _(scale).value = '';
                notification('error', '{{ __('file.Please Select Product') }}');
            }
        });
        $(document).on('input', '.scale', function() {
            let productId = $('#' + $(this).data('product_id') + '').find(":selected").val();
            let unitId = $(this).data('unit_id');
            let qty = $(this).data('qty');
            if (productId != '') {
                _(qty).value = $(this).val() / _(unitId).value;
            } else {
                $(this).val('');
                _(qty).value = '';
                notification('error', '{{ __('file.Please Select Product') }}');
            }
        });
        $(document).on('click', '.addRaw', function() {
            let html;
            html = `<tr class="text-center">
                      <td>
                      <select class="form-control selectpicker text-center" id="tenant_receive_` + i +
                `_warehouse_id" name="tenant_receive[` + i + `][warehouse_id]" data-live-search = "true">
                      <option value="">{{ __('Please Select') }}</option>
                      @foreach ($warehouses as $warehouse)
                      <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                      @endforeach
                      </select>
                      </td>
                      <td>
                      <select class="form-control selectpicker category text-center" id="tenant_receive_` + i +
                `_category_id" data-product_id="tenant_receive_` + i + `_product_id" data-live-search = "true">
                      <option value="">{{ __('Please Select') }}</option>
                      @foreach ($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                      @endforeach
                      </select>
                      </td>
                      <td><select class="form-control selectpicker product text-center" id="tenant_receive_` + i +
                `_product_id" name="tenant_receive[` + i + `][product_id]" data-unit_show="tenant_receive_` + i +
                `_unit_show" data-unit_id="tenant_receive_` + i + `_unit_id"  data-live-search = "true"></select></td>
                      <td><input class="form-control bg-primary text-center" id="tenant_receive_` + i +
                `_unit_show" readonly/><input type="hidden" id="tenant_receive_` + i + `_unit_id"/></td>
                      <td><input class="form-control qty text-center" id="tenant_receive_` + i +
                `_qty" name="tenant_receive[` + i + `][qty]" data-product_id="tenant_receive_` + i +
                `_product_id" data-unit_id="tenant_receive_` + i + `_unit_id" data-scale="tenant_receive_` + i + `_scale"/></td>
                      <td><input class="form-control scale text-center" id="tenant_receive_` + i +
                `_scale" name="tenant_receive[` + i + `][scale]" data-product_id="tenant_receive_` + i +
                `_product_id" data-unit_id="tenant_receive_` + i + `_unit_id" data-qty="tenant_receive_` + i + `_qty"/> </td>
                      <td><input class="form-control" id="tenant_receive_` + i + `_rec_qty" name="tenant_receive[` +
                i + `][rec_qty]"/></td>
                      <td>
                      <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><br/>
                      <button type = "button" class = "btn btn-danger btn-sm deleteRaw" style="margin-top:3px"><i class = "fas fa-minus-circle"></i></button>
                      </td>
                   </tr>`;
            $('#tenantReceiveTable tbody').append(html);
            $('.selectpicker').selectpicker('refresh');
            i++;
        });
        $(document).on('click', '.deleteRaw', function() {
            $(this).parent().parent().remove();
        });

        function storeData() {
            let form = document.getElementById('tenant_receive_product_form');
            let formData = new FormData(form);
            let url = "{{ route('tenant.receive.product.store') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $('#save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete: function() {
                    $('#save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success: function(data) {
                    $('#tenant_receive_product_form').find('.is-invalid').removeClass('is-invalid');
                    $('#tenant_receive_product_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function(key, value) {
                            var key = key.split('.').join('_');
                            $('#tenant_receive_product_form input#' + key).addClass('is-invalid');
                            $('#tenant_receive_product_form textarea#' + key).addClass('is-invalid');
                            $('#tenant_receive_product_form select#' + key).parent().addClass(
                                'is-invalid');
                            $('#tenant_receive_product_form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            window.location.replace("{{ route('tenant.receive.product') }}");
                        }
                    }
                },
                error: function(xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    </script>
@endpush
