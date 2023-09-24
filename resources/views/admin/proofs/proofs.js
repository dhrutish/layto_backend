var typebtn = 1;
$('body').on('change', 'input[name="filters"]', function () {
    typebtn = $(this).val();
    $('#table_proofs').bootstrapTable('refresh');
});

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
