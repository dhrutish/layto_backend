$(function (params) {
    "use strict";
    let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#areamodal">' + plus_svg_icon + '</a>';
    $('.fixed-table-toolbar .search.btn-group').append(html);
});
var typebtn = 1;
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
function getcities(s) {
    "use strict";
    $.ajax({
        url: $('#state').attr('data-next'),
        type: 'POST',
        data: {
            state: s
        },
        beforeSend: function (response) {
            $('#city option:not(:first)').remove();
            $('#city option:first').html(bs_spinner);
        },
        success: function (response) {
            $('#city').show();
            $('#city').parent().find('.spp').remove();
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
                $('#city option:first').html('Select');
                $.each(response.cities, (arrayIndex, elementValue) => {
                    $('#city').append(`<option value="${elementValue.id}" ${$.trim($('#city').attr('data-selected')) == elementValue.id ? 'selected' : ''}>${elementValue.title_en}</option>`);
                });
            }
        },
        error: function (xhr, status, error) {
            $('#city option:first').html('Select');
            showtoast('danger', wrong)
            return false;
        }
    });
}
$('#areaform').submit(function (event) {
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
                $('#areamodal').modal('hide');
                $('#areaform').removeClass('was-validated').find('button[type="reset"]').click();
                $('#table_areas').bootstrapTable('refresh');
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
    $('#areaform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#areamodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#areaform input[name=areaid]').val('');
});
$('body').on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#areamodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#areaform input[name=title_en]').val($(this).attr('data-title-en'));
    $('#areaform input[name=title_hi]').val($(this).attr('data-title-hi'));
    $('#areaform input[name=title_gj]').val($(this).attr('data-title-gj'));
    $('#areaform input[name=areaid]').val($(this).attr('data-aid'));
    $('#state').val($(this).attr('data-sid'));
    $('#city').attr('data-selected', $(this).attr('data-cid'));
    getcities($(this).attr('data-sid'));
});
$('#areamodal').on('hidden.bs.modal', function (e) {
    "use strict";
    $('#city option:not(:first)').remove();
});
$('#state').on('change', function (params) {
    "use strict";
    getcities($(this).val());
})
$(document).on('change', 'input[name="filters"]', function() {
    typebtn = $(this).val();
    $('#table_areas').bootstrapTable('refresh');
});
$(document).on('click', '.replace_with', function(params) {
    "use strict";
    $('.err').remove();
    $('#replacemodal input[name=id]').val($(this).attr('data-aid'));
});
$('#areastypeform').submit(function(event) {
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
                $('#areastypeform').removeClass('was-validated').find('button[type="reset"]')
                    .click();
                $('#table_areas').bootstrapTable('refresh');
            }
        },
        error: function(xhr, status, error) {
            hidepreloader()
            showtoast('danger', wrong)
            return false;
        }
    });
});
