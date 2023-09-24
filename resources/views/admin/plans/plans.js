$(function (params) {
    "use strict";
    let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#planmodal">' + plus_svg_icon + '</a>';
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

$('#upton').on('change', function () {
    "use strict";
    $('#to_coins').attr('disabled', $(this).is(':checked') ? true : false);
});
$('#from_coins').on('input', function () {
    "use strict";
    $('#to_coins').attr('min', $(this).val());
});

$('#plansform').submit(function (event) {
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
                        if($('#' + key).parent().hasClass('input-group')){
                            $('#' + key).parent().parent().append('<small class="err text-danger">' + value + '</small>')
                        }else{
                            $('#' + key).parent().append('<small class="err text-danger">' + value + '</small>')
                        }
                    });
                } else {
                    showtoast('danger', response.message)
                }
                return false;
            } else {
                showtoast('success', response.message)
                $('#planmodal').modal('hide');
                $('#plansform').removeClass('was-validated');
                $('#plansform').find('button[type="reset"]').click();
                $('#table_plans').bootstrapTable('refresh');
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
    $('#plansform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#planmodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#plansform .password').show().find('input[name=password]').attr('disabled', false);;
    $('#plansform input[name=planid]').val('');
});
$('body').on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#planmodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#plansform input[name=from_coins]').val($(this).attr('data-from-coins'));
    $('#plansform input[name=to_coins]').val($(this).attr('data-to-coins'));
    if($(this).attr('data-to-coins') == 0){
        $('#upton').attr('checked', true);
        $('#to_coins').attr('disabled', true);
    }else{
        $('#upton').attr('checked', false);
        $('#to_coins').attr('disabled', false);
    }
    $('#plansform input[name=additional_coins_pr]').val($(this).attr('data-additional-coins-pr'));
    $('#plansform .password').hide().find('input[name=password]').attr('disabled', true);
    $('#plansform input[name=planid]').val($(this).attr('data-pid'));
});
