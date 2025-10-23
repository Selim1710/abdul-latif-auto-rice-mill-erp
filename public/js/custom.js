$('.selectpicker').selectpicker({
    dropupAuto: false
});//Initialize selectpicker
function number_format(num=0){
    return num.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}
function showFormModal(modal_title, btn_text) {
    $('#store_or_update_form')[0].reset();
    $('#store_or_update_form #update_id').val('');
    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
    $('#store_or_update_form').find('.error').remove();
    $('#store_or_update_form .selectpicker').selectpicker('refresh');
    $('#store_or_update_form table tbody').find("tr:gt(0)").remove();
    $('#store_or_update_modal').modal({
        keyboard: false,
        backdrop: 'static',
    });
    $('#store_or_update_modal .modal-title').html('<i class="fas fa-plus-square text-white"></i> '+modal_title);
    $('#store_or_update_modal #save-btn').text(btn_text);
}
function select_all() {
    if($('#select_all:checked').length == 1){
        $('.select_data').prop('checked',true);
        if($('.select_data:checked').length >= 1) {
            $('.delete_btn').removeClass('d-none');
        }
    }else{
        $('.select_data').prop('checked',false);
        $('.delete_btn').addClass('d-none');
    }
}
function select_single_item(){
    var total = $('.select_data').length; //count total checkbox
    var total_checked =  $('.select_data:checked').length;//count total checked checkbox
    (total == total_checked) ? $('#select_all').prop('checked',true) : $('#select_all').prop('checked',false);
    (total_checked > 0) ?  $('.delete_btn').removeClass('d-none') : $('.delete_btn').addClass('d-none');
}
function notification(status,message) {
    const Toast = Swal.mixin({
        toast            : true,
        position         : 'top-end',
        showConfirmButton: false,
        timer            : 3000,
        timerProgressBar : true,
        onOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    Toast.fire({
        icon : status,
        title: message
    });
}
function store_or_update_data(table, method, url, formData) {
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
                    var key = key.split('.').join('_');
                    $('#store_or_update_form input#' + key).addClass('is-invalid');
                    $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                    $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                    $('#store_or_update_form #' + key).parent().append('<small class="error text-danger">' + value + '</small>');});
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
        error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
    });
}

