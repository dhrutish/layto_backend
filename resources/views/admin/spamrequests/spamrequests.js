
var fid = '';
function queryParamsUsers(c) {
    "use strict";
    return {
        sort: c.sort,
        order: c.order,
        offset: c.offset,
        limit: c.limit,
        search: c.search,
        fid: fid
    };
}

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

$(document).on('click', ' .show-details', function(params) {
    "use strict";
    fid = $(this).attr('data-rid');
    var aurl = SPAM_DETAIL_URL + '/' + fid;
    var $table = $('#table_spam_requests_users');
    $.ajax({
        url: aurl,
        type: 'GET',
        beforeSend: function(response) {
            $('#spammodal').find('.scontent').html(bs_spinner);
            $table.bootstrapTable('destroy');
        },
        success: function(response) {
            if (response.status == 0) {
                $('#spammodal').modal('hide').find('.scontent').html('');
                showtoast('danger', response.message)
                return false;
            } else {
                $('#spammodal').find('.scontent').html(response.html);
                $table.bootstrapTable({
                    data: response.tabledata
                });
            }
        },
        error: function(xhr, status, error) {
            hidepreloader()
            showtoast('danger', wrong)
            $('#spammodal').modal('hide').find('.scontent').html('');
            return false;
        }
    });
});
