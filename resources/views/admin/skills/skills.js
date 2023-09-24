$(function (params) {
    "use strict";
    let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#skillsmodal">' + plus_svg_icon + '</a>';
    $('.fixed-table-toolbar .search.btn-group').append(html);
});
var typebtn = 1;
$('body').on('change', 'input[name="filters"]', function () {
    typebtn = $(this).val();
    $('#table_skills').bootstrapTable('refresh');
});
function queryParams(p) {
    "use strict";
    return {
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        limit: p.limit,
        search: p.search,
        type: typebtn
    };
}

$('#skillsform').submit(function (event) {
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
                $('#skillsmodal').modal('hide');
                $('#skillsform').removeClass('was-validated').find('button[type="reset"]').click();
                $('#table_skills').bootstrapTable('refresh');
            }
        },
        error: function (xhr, status, error) {
            hidepreloader()
            showtoast('danger', wrong)
            return false;
        }
    });
});
$(document).on('click', '.add--', function (params) {
    "use strict";
    $('.err').remove();
    $('#skillsform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#skillsmodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#skillsform input[name=skillsid]').val('');
});
$(document).on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#skillsmodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#skillsform input[name=title_en]').val($(this).attr('data-title-en'));
    $('#skillsform input[name=title_hi]').val($(this).attr('data-title-hi'));
    $('#skillsform input[name=title_gj]').val($(this).attr('data-title-gj'));
    $('#skillsform input[name=skillsid]').val($(this).attr('data-sid'));
    $('#category').val($(this).attr('data-cid'));
});

$(document).on('click', '.replace_with', function(params) {
    "use strict";
    $('.err').remove();
    $('#replacemodal input[name=id]').val($(this).attr('data-sid'));
});
$('#skillstypeform').submit(function(event) {
    "use strict";
    event.preventDefault();
    $('.err').remove();
    var formData = $(this).serialize();
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        beforeSend: function(response) {
            showpreloader()
        },
        success: function(response) {
            hidepreloader()
            if (response.status == 0) {
                if (response.errors && Object.keys(response.errors).length > 0) {
                    $.each(response.errors, function(key, value) {
                        $('#' + key).parent().append('<small class="err text-danger">' +
                            value + '</small>')
                    });
                } else {
                    showtoast('danger', response.message)
                }
                return false;
            } else {
                if (response.cnt > 0) {
                    $('.cnt').html(response.cnt);
                } else {
                    $('.cnt').hide();
                }
                showtoast('success', response.message)
                $('#replacemodal').modal('hide');
                $('#skillstypeform').removeClass('was-validated').find('button[type="reset"]')
                    .click();
                $('#table_skills').bootstrapTable('refresh');
            }
        },
        error: function(xhr, status, error) {
            hidepreloader()
            showtoast('danger', wrong)
            return false;
        }
    });
});
