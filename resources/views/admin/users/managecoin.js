$(function (params) {

    $('body').on('click', '.add,.deduct', function (params) {
        "use strict";
        var ajurl = $(this).attr('data-next');
        var id = $(this).attr('data-to');
        var type = $(this).attr('data-type');
        swalWithBootstrapButtons.fire({
            icon: "warning",
            // title: are_you_sure,
            // input: 'number',
            title: $(this).attr('data-title'),
            // inputAttributes: {
            //     required: 'true',
            //     min: '1',
            //     step: '1',
            //     placeholder: 'Coins',
            // },
            html: `<div class="form-group"><input class="form-control" id="numberInput" type="number" placeholder="Enter Coins" required></div><div class="form-group"><textarea class="form-control" id="textInput" placeholder="Description" required></textarea></div>`,
            focusConfirm: false,
            showCancelButton: !0,
            allowOutsideClick: !1,
            allowEscapeKey: !1,
            confirmButtonText: yes,
            cancelButtonText: no,
            reverseButtons: !0,
            showLoaderOnConfirm: !0,
            didOpen: function () {
                $('.swal2-icon').hide();
            },
            preConfirm: function (value) {
                return new Promise(function (o, n) {
                    const coins = document.getElementById('numberInput').value;
                    const description = document.getElementById('textInput').value;
                    if (!coins) {
                        Swal.showValidationMessage('Please enter Number of Coins.');
                        Swal.disableLoading();
                        return false;
                    } else if (!description) {
                        Swal.showValidationMessage('Please enter Description.');
                        Swal.disableLoading();
                        return false;
                    } else {
                        $.ajax({
                            type: "POST",
                            url: ajurl,
                            data: {
                                id: id,
                                type: type,
                                coins: coins,
                                description: description,
                            },
                            success: function (t) {
                                if (t.status == 1) {
                                    if (t.tblname) {
                                        $('#' + t.tblname).bootstrapTable('refresh');
                                        Swal.close();
                                        showtoast('success', t.message);
                                    } else {
                                        location.reload()
                                    }
                                } else {
                                    swal_cancelled(t.message);
                                }
                            },
                            error: function (t) {
                                return swal_cancelled(wrong), !1
                            }
                        })
                    }
                })
            }
        }).then(t => {
            t.isConfirmed || (t.dismiss, Swal.DismissReason.cancel)
        })
    });
})
