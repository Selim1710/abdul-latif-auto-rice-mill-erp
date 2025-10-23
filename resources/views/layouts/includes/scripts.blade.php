<script src="{{asset('js/config.js')}}" type="text/javascript"></script>
<script src="{{asset('css/global/plugins.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('js/datatables.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('js/scripts.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('js/custom.js')}}" type="text/javascript"></script>
<script>
    var _token = "{{ csrf_token() }}";
    $(document).ready(function(){
        <?php if (session('status')) { ?>
        notification("{{session('status')}}","{{session('message')}}");
        <?php } ?>
        <?php if (session('success')) { ?>
        notification("success","{{session('success')}}");
        <?php } ?>
        <?php if (session('error')) { ?>
        notification("error","{{session('message')}}");
        <?php } ?>
    });
</script>
<script type="text/javascript">
    $(document).on('input','input',function(e){
        e.preventDefault();
        let input = $(this).val();
        $(this).val(' ');
        let number = convertInEnglish(input);
        $(this).val(number);
    });
    const banglaNumbers = {'০': 0, '১': 1, '২': 2, '৩': 3, '৪': 4, '৫': 5, '৬': 6, '৭': 7, '৮': 8, '৯': 9};
    function convertInEnglish(number){
        let output = [];
        for (let i = 0; i < number.length; ++i) {
            if (banglaNumbers.hasOwnProperty(number[i])) {
                output.push(banglaNumbers[number[i]]);
            } else {
                output.push(number[i]);
            }
        }
        return  output.join('');
    }
    function delete_data(id, url, table, row, name='') {
        Swal.fire({
            title              : '{{__('file.Are you sure to delete')}}' + ' ' + name + ' ' + '{{__('file.data?')}}',
            text               : '{{__('file.You won\'t be able to revert this!')}}',
            icon               : 'warning',
            showCancelButton   : true,
            confirmButtonColor : '#3085d6',
            cancelButtonColor  : '#d33',
            cancelButtonText   : '{{__('file.Cancel')}}',
            confirmButtonText  : '{{__('file.Yes, delete it!')}}'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url        : url,
                    type       : "POST",
                    data       : { id : id, _token : _token},
                    dataType   : "JSON",
                }).done(function (response) {
                    if (response.status == "success") {
                        Swal.fire("Deleted", response.message, "success").then(function () {
                            table.row(row).remove().draw(false);
                        });
                    }
                    if (response.status == "error") {
                        Swal.fire('Oops...', response.message, "error");
                    }
                }).fail(function () {
                    Swal.fire('Oops...', '{{__('file.Something went wrong with ajax!')}}', "error");
                });
            }
        });
    }
    function bulk_delete(ids,url,table,rows){
        Swal.fire({
            title               : '{{__('file.Are you sure to delete all checked data?')}}',
            text                : '{{__('file.You won\'t be able to revert this!')}}',
            icon                : 'warning',
            showCancelButton    : true,
            confirmButtonColor  : '#3085d6',
            cancelButtonColor   : '#d33',
            cancelButtonText    : '{{__('file.Cancel')}}',
            confirmButtonText   : '{{__('file.Yes, delete all!')}}'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url         : url,
                    type        : "POST",
                    data        : {ids : ids, _token : _token
                    },
                    dataType    : "JSON",
                }).done(function (response) {
                    if(response.status == "success") {
                        Swal.fire("Deleted", response.message, "success").then(function () {
                            table.rows(rows).remove().draw(false);
                            $('#select_all').prop('checked',false);
                            $('.delete_btn').addClass('d-none');
                        });
                    }
                    if (response.status == "error") {
                        Swal.fire('Oops...', response.message, "error");
                    }
                }).fail(function () {
                    Swal.fire('Oops...', '{{__('file.Something went wrong with ajax!')}}', "error");
                });
            }
        });
    }
    function change_status(id, url, table, row, name, status) {
        Swal.fire({
            title               : '{{__('file.Are you sure to change')}} ' + name + ' {{__('file.status?')}}',
            icon                : 'warning',
            showCancelButton    : true,
            confirmButtonColor  : '#3085d6',
            cancelButtonColor   : '#d33',
            cancelButtonText    :'{{__('file.Cancel')}}',
            confirmButtonText   : '{{__('file.Yes,change it!')}}'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url         : url,
                    type        : "POST",
                    data        : { id : id,status : status, _token : _token},
                    dataType    : "JSON",
                }).done(function (response) {
                    if (response.status == "success") {
                        Swal.fire("Changed", response.message, "success").then(function () {
                            table.ajax.reload(null, false);
                        });
                    }
                    if (response.status == "error") {
                        Swal.fire('Oops...', response.message, "error");
                    }
                }).fail(function () {
                    Swal.fire('Oops...', "{{__('file.Something went wrong with ajax!')}}", "error");
                });
            }
        });
    }
</script>
@stack('scripts')
