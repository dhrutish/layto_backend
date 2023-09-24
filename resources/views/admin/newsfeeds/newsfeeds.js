$(function (params) {
    "use strict";
    let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#newsfeedsmodal">' + plus_svg_icon + '</a>';
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
$('#newsfeedsform').submit(function (event) {
    "use strict";
    event.preventDefault();
    $('.err').remove();
    var description = CKEDITOR.instances.description.getData();
    var formData = new FormData(this);
    formData.set('description', description);
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
                        $('#' + key).parent().append('<small class="err text-danger">' + value + '</small>')
                    });
                } else {
                    showtoast('danger', response.message)
                }
                return false;
            } else {
                showtoast('success', response.message)
                $('#newsfeedsmodal').modal('hide');
                $('#newsfeedsform').removeClass('was-validated').find('button[type="reset"]').click();
                $('#table_newsfeeds').bootstrapTable('refresh');
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
    $('#newsfeedsform').removeClass('was-validated').find('button[type="reset"]').click();
    $('#newsfeedsmodal').find('.modal-title').html($('.modal-title').attr('data-add-title'));
    $('#newsfeedsform input[name=newsfeedsid]').val('');

    $('#imagefile').attr('src', '');
    $('#imagefile').addClass('d-none');

    var editor = CKEDITOR.instances.description;
    editor.setData('');

    $('#image').attr('required', true);
    $('#industry').val('');
});
$('body').on('click', '.edit-details', function (params) {
    "use strict";
    $('.err').remove();
    $('#newsfeedsmodal').find('.modal-title').html($('.modal-title').attr('data-edit-title'));
    $('#newsfeedsform input[name=title]').val($(this).attr('data-title'));
    $('#newsfeedsform input[name=newsfeedsid]').val($(this).attr('data-nfid'));
    var editor = CKEDITOR.instances.description;
    editor.setData($(this).attr('data-description'));

    $('#imagefile').attr('src', $(this).attr('data-file-url')).removeClass('d-none');
    $('#image').attr('required', false);
    $('#industry').val($(this).attr('data-itid'));
});

function changestatuss(id, status, aurl, ele) {
    "use strict";
    swalWithBootstrapButtons.fire({
        icon: "warning",
        title: are_you_sure,
        showCancelButton: !0,
        allowOutsideClick: !1,
        allowEscapeKey: !1,
        confirmButtonText: yes,
        cancelButtonText: no,
        reverseButtons: !0,
        showLoaderOnConfirm: !0,
        preConfirm: function () {
            return new Promise(function (o, n) {
                $.ajax({
                    type: "post",
                    url: aurl,
                    data: {
                        id: id,
                        status: status
                    },
                    success: function (t) {
                        if (t.status == 1) {
                            if (t.tblnname) {
                                $('#' + t.tblnname).bootstrapTable('refresh');
                                Swal.close();
                            } else {
                                location.reload()
                            }
                        } else {
                            swal_cancelled(t.message);
                            $(ele).prop('checked', $(ele).is(':checked') ? false : true);
                        }
                    },
                    error: function (t) {
                        $(ele).prop('checked', $(ele).is(':checked') ? false : true);
                        return swal_cancelled(wrong), !1
                    }
                })
            })
        }
    }).then(t => {
        t.isConfirmed || (t.dismiss, Swal.DismissReason.cancel)
        if ($(ele).is(':checked')) {
            $(ele).prop('checked', false);
        } else {
            $(ele).prop('checked', true);
        }
    })
}
