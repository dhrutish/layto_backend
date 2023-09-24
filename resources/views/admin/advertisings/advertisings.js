$(function (params) {
    "use strict";
    let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#advertisingsmodal">' + plus_svg_icon + '</a>';
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
$('#advertisingsform').submit(function (event) {
    "use strict";
    event.preventDefault();
    $('.err').remove();
    var formData = new FormData(this);
    $.ajax({
        url: $(this).attr('data-next'),
        type: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
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
                    showtoast('danger', response.message);
                }
                return false;
            } else {
                showtoast('success', response.message)
                $('#advertisingsmodal').modal('hide');
                $('#advertisingsform').removeClass('was-validated').find('button[type="reset"]').click();
                $('#table_advertisings').bootstrapTable('refresh');
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
    $('#advertisingsform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#advertisingsmodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#advertisingsform input[name=advertisingsid]').val('');
    $('#advertisingsform input[name=title]').val('');
    $('#imagefile, #videofile').attr('src', '');
    $('#imagefile, #videofile').addClass('d-none');
    $('#afile').attr('required', true);
});
$('body').on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#advertisingsmodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#advertisingsform input[name=url]').val($(this).attr('data-url'));
    $('#advertisingsform input[name=expiry_date]').val($(this).attr('data-expiry-date'));
    $('#advertisingsform input[name=advertisingsid]').val($(this).attr('data-aid'));
    $('#advertisingsform input[name=title]').val($(this).attr('data-add-title'));
    if ($(this).attr('data-file-type') == 1) {
        $('#imagefile').attr('src', $(this).attr('data-file-url')).removeClass('d-none');
        $('#videofile').attr('src', '');
        $('#videofile').addClass('d-none');
    } else {
        $('#videofile').attr('src', $(this).attr('data-file-url')).removeClass('d-none');
        $('#imagefile').attr('src', '');
        $('#imagefile').addClass('d-none');
    }
    $('#afile').attr('required', false);
});
