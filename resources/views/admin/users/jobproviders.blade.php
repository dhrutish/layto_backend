@extends('admin.layout.default')
@section('styles')
@endsection
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">
            <div class="card-body">
                <table class="table-responsive" id="table_jobproviders" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' .date('d-m-y') ?>", "ignoreColumn": ["action","profile"] }' data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                            <th data-field="profile" data-sortable="false" data-visible="true"> {{ trans('labels.profile') }} </th>
                            <th data-field="name" data-sortable="true" data-visible="true"> {{ trans('labels.name') }} </th>
                            <th data-field="email" data-sortable="true" data-visible="true"> {{ trans('labels.email') }} </th>
                            <th data-field="mobile" data-sortable="true" data-visible="true"> {{ trans('labels.mobile') }} </th>
                            <th data-field="login_type" data-sortable="false" data-visible="true"> {{ trans('labels.login_type') }} </th>
                            <th data-field="status" data-sortable="false" data-visible="true"> {{ trans('labels.status') }} </th>
                            <th data-field="action" data-sortable="false" data-visible="true"> {{ trans('labels.action') }} </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('admin.users.resetpassmodal')
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/users/jobproviders.js') }}"></script>
@endsection
