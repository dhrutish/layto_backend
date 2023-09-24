$(function (params) {
    "use strict";
    let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#categoriesmodal">' + plus_svg_icon + '</a>';
    $('.fixed-table-toolbar .search.btn-group').append(html);
});
function queryParams(p) {
    "use strict";
    return {
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        limit: p.limit,
        search: p.search,
    };
}

$('#categoriesform').submit(function (event) {
    "use strict";
    event.preventDefault();
    $('.err').remove();
    var formData = $(this).serialize();
    $.ajax({
        url: $(this).attr('data-next'),
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
                $('#categoriesmodal').modal('hide');
                $('#categoriesform').removeClass('was-validated').find('button[type="reset"]').click();
                $('#table_categories').bootstrapTable('refresh');
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
    $('#categoriesform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#categoriesmodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#categoriesform input[name=categoriesid]').val('');
    $('#industry').val('');
});
$('body').on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#categoriesmodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#categoriesform input[name=title_en]').val($(this).attr('data-title-en'));
    $('#categoriesform input[name=title_hi]').val($(this).attr('data-title-hi'));
    $('#categoriesform input[name=title_gj]').val($(this).attr('data-title-gj'));
    $('#categoriesform input[name=categoriesid]').val($(this).attr('data-cid'));
    $('#industry').val($(this).attr('data-itid'));
});
