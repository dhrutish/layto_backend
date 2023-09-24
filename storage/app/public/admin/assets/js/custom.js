window.addEventListener("load", function () {
    "use strict";
    $("#preloader").hide();
});
$(function () {
    "use strict";
    $("form, input, textarea").attr("autocomplete", "off");
    $("label.required").each(function () {
        $(this).append(' <span class="text-danger">*</span>');
        $("#" + $(this).attr("for")).attr("required", "required");
    });

    // Bootstrap table
    $(".table-bordered").removeClass("table-bordered");

    // Ajax set headers for all ajax requests
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});

// $(".bootstraptable").attr("data-show-export", true);
// $('.bootstraptable').bootstrapTable({
//     toolbar: ".toolbar",
//     clickToSelect: !1,
//     showRefresh: !1,
//     search: !0,
//     showToggle: !1,
//     showColumns: !1,
//     pagination: !0,
//     searchAlign: "right",
//     pageSize: 10,
//     clickToSelect: !1,
//     pageList: [10, 25, 50, 100],
// });
// $(".fixed-table-toolbar .export.btn-group .dropdown-toggle").addClass("px-3");

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-primary mx-2",
        cancelButton: "btn btn-outline-danger mx-2",
    },
    buttonsStyling: !1,
});

function logout(t) {
    "use strict";
    swalWithBootstrapButtons
        .fire({
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
                    window.location = t;
                });
            },
        })
        .then((t) => {
            t.isConfirmed || (t.dismiss, Swal.DismissReason.cancel);
        });
}

function swal_cancelled(t) {
    "use strict";
    var e = wrong;
    t && (e = "" + t), swalWithBootstrapButtons.fire(oops, e, "error");
}

function showpreloader() {
    "use strict";
    $("#preloader").show();
}

function hidepreloader() {
    "use strict";
    $("#preloader").hide();
}

function deletedata(e) {
    "use strict";
    swalWithBootstrapButtons
        .fire({
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
                        type: "DELETE",
                        url: e,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        success: function (t) {
                            if (t.status == 1) {
                                if (t.tblnname) {
                                    $("#" + t.tblnname).bootstrapTable(
                                        "refresh"
                                    );
                                    Swal.close();
                                    showtoast("success", t.message);
                                } else {
                                    location.reload();
                                }
                            } else {
                                swal_cancelled(t.message);
                            }
                        },
                        error: function (t) {
                            return swal_cancelled(wrong), !1;
                        },
                    });
                });
            },
        })
        .then((t) => {
            t.isConfirmed || (t.dismiss, Swal.DismissReason.cancel);
        });
}

function changestatus(i, s, u) {
    "use strict";
    swalWithBootstrapButtons
        .fire({
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
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        type: "post",
                        url: u,
                        data: {
                            id: i,
                            status: s,
                        },
                        dataType: "json",
                        success: function (t) {
                            if (t.status == 1) {
                                if (t.tblnname) {
                                    if (
                                        document.getElementById("feedbackmodal")
                                    ) {
                                        showtoast("success", t.message);
                                        $("#feedbackmodal").modal("hide");
                                    }
                                    if (document.getElementById("spammodal")) {
                                        showtoast("success", t.message);
                                        $("#spammodal").modal("hide");
                                    }
                                    $("#" + t.tblnname).bootstrapTable(
                                        "refresh"
                                    );
                                    Swal.close();
                                } else {
                                    location.reload();
                                }
                            } else {
                                swal_cancelled(t.message);
                            }
                        },
                        error: function (t) {
                            return swal_cancelled(wrong), !1;
                        },
                    });
                });
            },
        })
        .then((t) => {
            t.isConfirmed || (t.dismiss, Swal.DismissReason.cancel);

            if ($("#open-close-switch").is(":checked")) {
                $("#open-close-switch").prop("checked", false);
            } else {
                $("#open-close-switch").prop("checked", true);
            }
        });
}
// Export data to XLSX format
function exportToXLSX() {
    var tableData = $(".table-responsive").bootstrapTable("getData", {
        useCurrentPage: false,
        includeHiddenRows: true,
    });
    var ws = XLSX.utils.json_to_sheet(tableData);
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Data");
}
$(document).on("click", ".export.btn-group button", function () {
    exportToXLSX();
});
