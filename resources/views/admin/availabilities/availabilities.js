$(function (params) {
    "use strict";
    let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#availabilitiesmodal">' + plus_svg_icon + '</a>';
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

$('#availabilitiesform').submit(function (event) {
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
                $('#availabilitiesmodal').modal('hide');
                $('#availabilitiesform').removeClass('was-validated').find('button[type="reset"]').click();
                $('#table_availabilities').bootstrapTable('refresh');
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
    $('#availabilitiesform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#availabilitiesmodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#availabilitiesform input[name=availabilitiesid]').val('');
});
$('body').on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#availabilitiesmodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#availabilitiesform input[name=title_en]').val($(this).attr('data-title-en'));
    $('#availabilitiesform input[name=title_hi]').val($(this).attr('data-title-hi'));
    $('#availabilitiesform input[name=title_gj]').val($(this).attr('data-title-gj'));
    $('#availabilitiesform input[name=availabilitiesid]').val($(this).attr('data-aid'));
});
