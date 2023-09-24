function showtoast(type, message) {
    "use strict";
    removeclasses();
    $('.mytoastr').addClass('border-' + type + ' text-' + type);
    $('.mytoastr button').addClass('text-' + type);
    $('.mytoastr .toast-body').html(message);
    const toast = new bootstrap.Toast($('.mytoastr'));
    toast.show();
}

function removeclasses() {
    "use strict";
    $('.mytoastr').removeClass('border-danger border-success text-success text-danger');
    $('.mytoastr button').removeClass('text-success text-danger');
    $('.mytoastr .toast-body').html('');
}
$('.mytoastr').on('hidden.bs.toast', function () {
    "use strict";
    removeclasses();
});
