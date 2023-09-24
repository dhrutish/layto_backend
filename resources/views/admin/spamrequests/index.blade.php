@extends('admin.layout.default')
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">
            <div class="card-body">
                <table class="table-responsive" id="table_spam_requests" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' . date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px"> {{ trans('labels.srno') }}</th>
                            <th data-field="job_info" data-sortable="false" data-visible="true"> {{ trans('labels.job_info') }} </th>
                            <th data-field="status" data-sortable="false" data-visible="true"> {{ trans('labels.status') }} </th>
                            <th data-field="action" data-sortable="false" data-visible="true"> {{ trans('labels.action') }} </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="spammodal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="spammodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="spammodalLabel">{{ trans('labels.spam_requests') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="scontent"></div>
                    <h5> Report/Spam requested users </h5>
                    <table class="table-responsive" id="table_spam_requests_users" data-url="" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="false" data-show-export="false" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' . date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParamsUsers">
                        <thead>
                            <tr>
                                <th data-field="id" data-sortable="false" data-visible="true" data-width="80" data-width-unit="px"> {{ trans('labels.srno') }}</th>
                                <th data-field="seeker_info" data-sortable="false" data-visible="true"> {{ trans('labels.seeker_info') }} </th>
                                <th data-field="note" data-sortable="false" data-visible="true"> {{ trans('labels.notes') }} </th>
                                <th data-field="description" data-sortable="false" data-visible="true"> {{ trans('labels.description') }} </th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        const SPAM_DETAIL_URL = {{ Js::from(route('spam-requests.show', [''])) }};
    </script>
    <script src="{{ url('resources/views/admin/spamrequests/spamrequests.js') }}"></script>
@endsection
