@extends('admin.layout.default')
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">
            <div class="card-body">

                <table class="table-responsive" id="table_transactions" data-url="{{ request()->url() }}" data-toggle="table"
                    data-show-copy-rows="true" data-side-pagination="server" data-pagination="true"
                    data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true"
                    data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                    data-pagination-successively-size="5" data-export-types='["xlsx"]' data-toolbar="#tool"
                    data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' .
                    date('d-m-y') ?>", "ignoreColumn": ["action"] }'
                    data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                            <th data-field="image" data-sortable="false" data-visible="true"> {{ trans('labels.image') }} </th>
                            <th data-field="final_coins" data-sortable="true" data-visible="true"> {{ trans('labels.coins') }} </th>
                            <th data-field="amount" data-sortable="true" data-visible="true"> {{ trans('labels.amount') }} </th>
                            <th data-field="transaction_id" data-sortable="true" data-visible="true"> {{ trans('labels.transaction_id') }} </th>
                            <th data-field="description" data-sortable="false" data-visible="true"> {{ trans('labels.description') }} </th>
                            <th data-field="created_at" data-sortable="true" data-visible="true"> {{ trans('labels.created_at') }} </th>
                            <th data-field="days_left" data-sortable="false" data-visible="true"> {{ trans('labels.days_left') }} </th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/transactions/transactions.js') }}"></script>
@endsection
