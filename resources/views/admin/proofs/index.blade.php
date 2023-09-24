@extends('admin.layout.default')
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-3" id="toolbar">
                    <div class="form-check ps-0">
                        <input class="form-check-input d-none filter-toolbar" name="filters" id="first" type="radio" value="1" checked />
                        <label class="form-check-label cursor-pointer rounded-pill px-3 py-2" for="first"> {{ trans('labels.job_providers') }} </label>
                    </div>
                    <div class="form-check ps-0">
                        <input class="form-check-input d-none filter-toolbar" name="filters" id="2nd" type="radio" value="2" />
                        <label class="form-check-label cursor-pointer rounded-pill px-3 py-2" for="2nd"> {{ trans('labels.job_seekers') }} </label>
                    </div>
                </div>
                <table class="table-responsive" id="table_proofs" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' . date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-toolbar="#toolbar" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                            <th data-field="id_number" data-sortable="true" data-visible="true"> {{ trans('labels.id_number') }} </th>
                            <th data-field="front_image" data-sortable="false" data-visible="true"> {{ trans('labels.front_image') }} </th>
                            <th data-field="back_image" data-sortable="false" data-visible="true"> {{ trans('labels.back_image') }} </th>
                            <th data-field="status" data-sortable="true" data-visible="true"> {{ trans('labels.status') }} </th>
                            <th data-field="action" data-sortable="false" data-visible="true"> {{ trans('labels.action') }} </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/proofs/proofs.js') }}"></script>
@endsection
