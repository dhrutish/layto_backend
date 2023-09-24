// $(function (params) {
//     "use strict";
//     let html = '<a href="javascript:;" class="btn btn-primary rounded px-3 add--" data-bs-toggle="modal" data-bs-target="#notifyusersmodal">' + plus_svg_icon + '</a>';
//     $('.fixed-table-toolbar .search.btn-group').append(html);
// });
// var typebtn = 1;
// $('body').on('change', 'input[name="filters"]', function () {
//     typebtn = $(this).val();
//     $('#table_notifyusers').bootstrapTable('refresh');
// });
// function queryParams(p) {
    //     "use strict";
//     return {
//         sort: p.sort,
//         order: p.order,
//         offset: p.offset,
//         limit: p.limit,
//         search: p.search,
//         // type: typebtn
//     };
// }
$(document).on('change', 'input[name="user_type"]', function () {
    $(this).parent().parent().parent().find('.err').remove();
    if($(this).val() == 4){
        $('textarea[name="emails"]').show();
    }else{
        $('textarea[name="emails"]').hide();
    }
});
$('#notifyusersform').submit(function (event) {
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
                $('.err').remove();
                showtoast('success', response.message)
                $('#notifyusersform').find('button[type="reset"]').click();
                var editor = CKEDITOR.instances.description;
                editor.setData('');
                editor.focus();
            }
        },
        error: function (xhr, status, error) {
            hidepreloader()
            showtoast('danger', wrong)
            return false;
        }
    });
});
