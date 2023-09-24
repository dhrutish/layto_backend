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
function queryParamsJobsApplies(p) {
    "use strict";
    return {
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        limit: p.limit,
        search: p.search,
        type: 1
    };
}
function queryParamsReviews(p) {
    "use strict";
    return {
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        limit: p.limit,
        search: p.search,
        type: 2
    };
}
$('#faqform').submit(function (event) {
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
                $('#faqmodal').modal('hide');
                $('#faqform').removeClass('was-validated').find('button[type="reset"]').click();
                $('#table_faqs').bootstrapTable('refresh');
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
    $('#faqform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#faqmodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#faqform .password').show().find('input[name=password]').attr('disabled', false);;
    $('#faqform input[name=faqid]').val('');
});
$('body').on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#faqmodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#faqform input[name=title]').val($(this).attr('data-title'));
    $('#faqform textarea[name=description]').html($(this).attr('data-description'));
    $('#faqform .password').hide().find('input[name=password]').attr('disabled', true);
    $('#faqform input[name=faqid]').val($(this).attr('data-fid'));
});
