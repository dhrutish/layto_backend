$(function (params) {
    "use strict";
    let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#citymodal">' + plus_svg_icon + '</a>';
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

$('#cityform').submit(function (event) {
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
                $('#citymodal').modal('hide');
                $('#cityform').removeClass('was-validated').find('button[type="reset"]').click();
                $('#table_cities').bootstrapTable('refresh');
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
    $('#cityform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#citymodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#cityform input[name=cityid]').val('');
});
$('body').on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#citymodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#cityform input[name=title_en]').val($(this).attr('data-title-en'));
    $('#cityform input[name=title_hi]').val($(this).attr('data-title-hi'));
    $('#cityform input[name=title_gj]').val($(this).attr('data-title-gj'));
    $('#cityform input[name=cityid]').val($(this).attr('data-cid'));
    $('#state').val($(this).attr('data-sid'));
});
