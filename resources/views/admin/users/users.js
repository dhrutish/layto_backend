$(function (params) {
    "use strict";
    let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#subadminmodal">' + plus_svg_icon + '</a>';
    $('.fixed-table-toolbar .search.btn-group').append(html);
    // let html = '<a href="' + window.location.href.replace(window.location.search, '') + '/add" class="btn btn-primary rounded px-3">' + plus_svg_icon + '</a>';
});
// var filterbtn = 'All';
// $('.filter-btn').on('click', function() {
//     filterbtn = $(this).val();
//     $('#table_list').bootstrapTable('refresh');
// });
function queryParams(p) {
    "use strict";
    return {
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        limit: p.limit,
        search: p.search,
        // filter: p.filter,
        // status: filterbtn
    };
}

$('#subadminform').submit(function (event) {
    "use strict";
    event.preventDefault();
    $('.err').remove();
    var ajxurl = $(this).attr('data-next');
    var formData = $(this).serialize();
    $.ajax({
        url: ajxurl,
        type: 'POST',
        data: formData,
        beforeSend: function (response) {
            showpreloader()
        },
        success: function (response) {
            hidepreloader()
            if (response.status == 0) {
                if (response.errors && Object.keys(response.errors).length > 0) {
                    $.each(response.errors, function (key, value) {
                        $('#' + key).parent().append('<small class="err text-danger">' + value + '</small>')
                    });
                } else {
                    showtoast('danger', response.message)
                }
                return false;
            } else {
                showtoast('success', response.message)
                $('#subadminmodal').modal('hide');
                $('#subadminform').removeClass('was-validated').find('button[type="reset"]').click();
                $('#table_subadmins').bootstrapTable('refresh');
            }
        },
        error: function (xhr, status, error) {
            hidepreloader()
            showtoast('danger', wrong)
            return false;
        }
    });
});
$('body').on('click', '.add--', function (params) {
    "use strict";
    $('.err').remove();
    $('#subadminform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#subadminmodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#subadminform .password').show().find('input[name=password]').attr('disabled', false);;
    $('#subadminform input[name=userid]').val('');
});
$('body').on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#subadminmodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#subadminform input[name=name]').val($(this).attr('data-name'));
    $('#subadminform input[name=email]').val($(this).attr('data-email'));
    $('#subadminform input[name=mobile]').val($(this).attr('data-mobile'));
    $('#subadminform .password').hide().find('input[name=password]').attr('disabled', true);
    $('#subadminform input[name=userid]').val($(this).attr('data-uid'));
});
