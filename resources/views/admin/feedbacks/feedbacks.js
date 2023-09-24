var typebtn = 1;
$("body").on("change", 'input[name="filters"]', function () {
    typebtn = $(this).val();
    $("#table_feedbacks").bootstrapTable("refresh");
});
function queryParams(p) {
    "use strict";
    return {
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        limit: p.limit,
        search: p.search,
        type: typebtn,
    };
}
$(document).on("click", " .show-details", function (params) {
    "use strict";
    var fid = $(this).attr("data-fid");
    $.ajax({
        url: FEEDBACK_DETAIL_URL + "/" + fid,
        type: "GET",
        beforeSend: function (response) {
            $("#feedbackmodal").find(".fcontent").html(bs_spinner);
        },
        success: function (response) {
            if (response.status == 0) {
                $("#feedbackmodal").modal("hide").find(".fcontent").html("");
                showtoast("danger", response.message);
                return false;
            } else {
                $("#feedbackmodal").find(".fcontent").html(response.html);
            }
        },
        error: function (xhr, status, error) {
            hidepreloader();
            showtoast("danger", wrong);
            return false;
        },
    });
});
