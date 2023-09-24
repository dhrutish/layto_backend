$(function (params) {
    "use strict";
    let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#notesmodal">' + plus_svg_icon + '</a>';
    $('.fixed-table-toolbar .search.btn-group').append(html);
});

var typebtn = 1;
$('body').on('change', 'input[name="filters"]', function () {
    typebtn = $(this).val();
    $('#table_notes').bootstrapTable('refresh');
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

$('#notesform').submit(function (event) {
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
                        if (document.getElementById(key)) {
                            $('#' + key).parent().append('<small class="err text-danger">' + value + '</small>')
                        } else {
                            showtoast('danger', value)
                        }
                    });
                } else {
                    showtoast('danger', response.message)
                }
                return false;
            } else {
                showtoast('success', response.message)
                $('#notesmodal').modal('hide');
                $('#notesform').removeClass('was-validated').find('button[type="reset"]').click();
                $('#table_notes').bootstrapTable('refresh');
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
    $('#notesform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#notesmodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#notesform input[name=notesid]').val('');
});
$('body').on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#notesmodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#notesform input[name=title_en]').val($(this).attr('data-title-en'));
    $('#notesform input[name=title_hi]').val($(this).attr('data-title-hi'));
    $('#notesform input[name=title_gj]').val($(this).attr('data-title-gj'));
    $('#radio' + $(this).attr('data-type')).attr('checked', true);
    $('#notesform input[name=notesid]').val($(this).attr('data-nid'));
});
