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
                    @if (permission('user-add'))
                    <a href="javascript:void(0);" onclick="showUserFormModal('{{__('file.Add New User')}}','{{__('file.Save')}}')" class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i> {{__('file.Add New')}}</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <form method="POST" id="form-filter" class="col-md-12 px-0">
                    <div class="row">
                        <x-form.textbox labelName="{{__('file.Name')}}" name="name" col="col-md-3" placeholder="{{__('file.Name')}}" />
                        <x-form.textbox labelName="{{__('file.Username')}}" name="username" col="col-md-3" placeholder="{{__('file.Username')}}" />
                        <x-form.textbox labelName="{{__('file.Phone No')}}." name="phone" col="col-md-3" placeholder="{{__('file.Phone No')}}" />
                        <x-form.textbox labelName="{{__('file.Email')}}" name="email" col="col-md-3" placeholder="{{__('file.Email')}}" />
                        <x-form.selectbox labelName="{{__('file.Role')}}" name="role_id" col="col-md-3" class="selectpicker">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <x-form.selectbox labelName="{{__('file.Status')}}" name="status" col="col-md-3" class="selectpicker">
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </x-form.selectbox>
                        <div class="col-md-6">
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
                                        <th>{{__('file.Avatar')}}</th>
                                        <th>{{__('file.Name')}}</th>
                                        <th>{{__('file.Username')}}</th>
                                        <th>{{__('file.Role')}}</th>
                                        <th>{{__('file.Phone No')}}.</th>
                                        <th>{{__('file.Email')}}</th>
                                        <th>{{__('file.Gender')}}</th>
                                        <th>{{__('file.Status')}}</th>
                                        <th>{{__('file.Created By')}}</th>
                                        <th>{{__('file.Modified By')}}</th>
                                        <th>{{__('file.Created At')}}</th>
                                        <th>{{__('file.Updated At')}}</th>
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
@include('user.modal')
@include('user.view')
@endsection
@push('scripts')
<script>
let table;
$(document).ready(function(){
    table = $('#dataTable').DataTable({
        "processing": true, //Feature control the processing indicator
        "serverSide": true, //Feature control DataTable server side processing mode
        "order"     : [], //Initial no order
        "responsive": true, //Make table responsive in mobile device
        "bInfo"     : true, //TO show the total number of data
        "bFilter"   : false, //For datatable default search box show/hide
        "lengthMenu": [
            [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
            [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
        ],
        "pageLength": 25, //number of data show per page
        "language"  : {
            processing : `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable : '<strong class="text-danger">{{__('file.No Data Found')}}</strong>',
            infoEmpty  : '',
            zeroRecords: '<strong class="text-danger">{{__('file.No Data Found')}}</strong>'
        },
        "ajax": {
            "url" : "{{route('user.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.name     = $("#form-filter #name").val();
                data.username = $("#form-filter #username").val();
                data.phone    = $("#form-filter #phone").val();
                data.email    = $("#form-filter #email").val();
                data.role_id  = $("#form-filter #role_id").val();
                data.status   = $("#form-filter #status").val();
                data._token   = _token;
            }
        },
        "columnDefs": [{
            "targets": [0,1,2,3,4,5,6,7,8,9,10,11,12,13],
            "orderable": false,
            "className": "text-center"
            }],
        "dom"    : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
        "buttons": [
            @if (permission('user-report')){
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'{{__('file.Column')}}','columns': ':gt(0)'
            }, {
                    "extend"       : 'print',
                    'text'         :'{{__('file.Print')}}',
                    'className'    :'btn btn-secondary btn-sm text-white',
                    "title"        : "{{ $page_title }} List",
                    "orientation"  : "landscape", //portrait
                    "pageSize"     : "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns    : ':visible:not(:eq(13))'
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
                    'text'         :'{{__('file.CSV')}}',
                    'className'    :'btn btn-secondary btn-sm text-white',
                    "title"        : "{{ $page_title }} List",
                    "filename"     : "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    "exportOptions": {
                        columns    : ':visible:not(:eq(13))'
                    }
                }, {
                    "extend"       : 'excel',
                    'text'         :'{{__('file.Excel')}}',
                    'className'    :'btn btn-secondary btn-sm text-white',
                    "title"        : "{{ $page_title }} List",
                    "filename"     : "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    "exportOptions": {
                        columns    : ':visible:not(:eq(13))'
                    }
                }, {
                    "extend"       : 'pdf',
                    'text'         :'{{__('file.PDF')}}',
                    'className'    :'btn btn-secondary btn-sm text-white',
                    "title"        : "{{ $page_title }} List",
                    "filename"     : "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    "orientation"  : "landscape", //portrait
                    "pageSize"     : "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns    : ':visible:not(:eq(13))'
                    },
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10
                        doc.styles.tableHeader.fontSize = 7;
                        doc.pageMargins = [5,5,5,5];
                    }
                },
            @endif
        ],
    });
    $('#btn-filter').click(function () {
        table.ajax.reload();
    });
    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('#form-filter .selectpicker').selectpicker('refresh');
        table.ajax.reload();
    });
    $(document).on('click', '#save-btn', function () {
        let form     = document.getElementById('store_or_update_form');
        let formData = new FormData(form);
        let url      = "{{route('user.store.or.update')}}";
        let id       = $('#update_id').val();
        let method;
        if (id) {
            method = 'update';
        } else {
            method = 'add';
        }
        $.ajax({
            url        : url,
            type       : "POST",
            data       : formData,
            dataType   : "JSON",
            contentType: false,
            processData: false,
            cache      : false,
            beforeSend : function(){
                $('#save-btn').addClass('spinner spinner-white spinner-right');
            },
            complete   : function(){
                $('#save-btn').removeClass('spinner spinner-white spinner-right');
            },
            success    : function (data) {
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();
                if (data.status == false) {
                    $.each(data.errors, function (key, value) {
                        $('#store_or_update_form input#' + key).addClass('is-invalid');
                        $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                        $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                        if(key == 'password' || key == 'password_confirmation'){
                            $('#store_or_update_form #' + key).parents('.form-group').append('<small class="error text-danger">' + value + '</small>');
                        }else{
                            $('#store_or_update_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');
                        }
                    });
                } else {
                    notification(data.status, data.message);
                    if (data.status == 'success') {
                        if (method == 'update') {
                            table.ajax.reload(null, false);
                        } else {
                            table.ajax.reload();
                        }
                        $('#store_or_update_modal').modal('hide');
                    }
                }
            },
            error     : function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
        });
    });
    $(document).on('click', '.edit_data', function () {
        let id = $(this).data('id');
        $('#store_or_update_form')[0].reset();
        $('#store_or_update_form .select').val('');
        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
        $('#store_or_update_form').find('.error').remove();
        if (id) {
            $.ajax({
                url     : "{{route('user.edit')}}",
                type    : "POST",
                data    : { id: id,_token: _token},
                dataType: "JSON",
                success : function (data) {
                    if(data.status == 'error'){
                        notification(data.status,data.message)
                    }else{
                        $('#store_or_update_form #update_id').val(data.id);
                        $('#store_or_update_form #name').val(data.name);
                        $('#store_or_update_form #username').val(data.username);
                        $('#store_or_update_form #phone').val(data.phone);
                        $('#store_or_update_form #email').val(data.email);
                        $('#store_or_update_form #gender').val(data.gender);
                        $('#store_or_update_form #role_id').val(data.role_id);
                        $('#store_or_update_form .selectpicker').selectpicker('refresh');
                        $('#password, #password_confirmation').parents('.form-group').removeClass('required');
                        $('#store_or_update_modal').modal({
                            keyboard: false,
                            backdrop: 'static',
                        });
                        $('#store_or_update_modal .modal-title').html('<i class="fas fa-edit text-white"></i> <span>{{__('file.Edit')}} ' + data.name + '</span>');
                        $('#store_or_update_modal #save-btn').text('{{__('file.Update')}}');
                    }
                },
                error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    });
    $(document).on('click', '.view_data', function () {
        let id = $(this).data('id');
        if (id) {
            $.ajax({
                url    : "{{route('user.view')}}",
                type   : "POST",
                data   : { id: id,_token: _token},
                success: function (data) {
                    $('#view_modal #view-data').html('');
                    $('#view_modal #view-data').html(data);
                    $('#view_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                },
                error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
            });
        }
    });
    $(document).on('click', '.delete_data', function () {
        let id    = $(this).data('id');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('user.delete') }}";
        delete_data(id, url, table, row, name);
    });
    $(document).on('click', '.change_status', function () {
        let id     = $(this).data('id');
        let name   = $(this).data('name');
        let status = $(this).data('status');
        let row    = table.row($(this).parent('tr'));
        let url    = "{{ route('user.change.status') }}";
        change_status(id, url, table, row, name, status);
    });
    $(".toggle-password").click(function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
});
function showUserFormModal(modal_title, btn_text) {
    $('#store_or_update_form')[0].reset();
    $('#store_or_update_form #update_id').val('');
    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
    $('#store_or_update_form').find('.error').remove();
    $('#password, #password_confirmation').parents('.form-group').addClass('required');
    $('#store_or_update_form .selectpicker').selectpicker('refresh');
    $('#store_or_update_modal').modal({
        keyboard: false,
        backdrop: 'static',
    });
    $('#store_or_update_modal .modal-title').html('<i class="fas fa-plus-square text-white"></i> '+modal_title);
    $('#store_or_update_modal #save-btn').text(btn_text);
}
 const randomFunc = {
    upper  : getRandomUpperCase,
    lower  : getRandomLowerCase,
    number : getRandomNumber,
    symbol : getRandomSymbol
};
function getRandomUpperCase(){
    return String.fromCharCode(Math.floor(Math.random()*26)+65);
}
function getRandomLowerCase(){
   return String.fromCharCode(Math.floor(Math.random()*26)+97);
}
function getRandomNumber(){
   return String.fromCharCode(Math.floor(Math.random()*10)+48);
}
function getRandomSymbol(){
    var symbol = "!@#$%^&*=~?";
    return symbol[Math.floor(Math.random()*symbol.length)];
}
document.getElementById("generate_password").addEventListener('click', () =>{
    const length    = 8;
    const hasUpper  = true;
    const hasLower  = true;
    const hasNumber = true;
    const hasSymbol = true;
    let   password  = generatePassword(hasUpper, hasLower, hasNumber, hasSymbol, length);
    document.getElementById("password").value = password;
    document.getElementById("password_confirmation").value = password;
});
function generatePassword(upper, lower, number, symbol, length){
    let generatedPassword = "";
    const typesCount = upper + lower + number + symbol;
    const typesArr = [{upper}, {lower}, {number}, {symbol}].filter(item => Object.values(item)[0]);
    if(typesCount === 0) {
        return '';
    }
    for(let i=0; i<length; i+=typesCount) {
        typesArr.forEach(type => {
            const funcName = Object.keys(type)[0];
            generatedPassword += randomFunc[funcName]();
        });
    }
    const finalPassword = generatedPassword.slice(0, length);
    return finalPassword;
}
</script>
@endpush
