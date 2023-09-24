@extends('admin.layout.default')
@section('content')
    <div class="content">

        @include('admin.layout.breadcrumb')

        <div class="card">
            <div class="card-body">
                <table class="table-responsive" id="table_plans" data-url="{{ route('plans.list') }}" data-toggle="table" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="asc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' .date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                            <th data-field="from_coins" data-sortable="true" data-visible="true"> {{ trans('labels.from_coins') }} </th>
                            <th data-field="to_coins" data-sortable="true" data-visible="true"> {{ trans('labels.to_coins') }} </th>
                            <th data-field="additional_coins_pr" data-sortable="true" data-visible="true"> {{ trans('labels.additional_coins_pr') }} </th>
                            <th data-field="action" data-sortable="false" data-visible="true"> {{ trans('labels.action') }} </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>

    <div class="modal fade" id="planmodal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="planmodalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="planmodalLabel" data-edit-title="{{ trans('labels.edit') }}"
                        data-add-title="{{ trans('labels.create') }}"></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" novalidate="" method="post" data-next="{{ route('plans.store') }}"
                    id="plansform">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="planid" id="planid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="from_coins"
                                        class="form-label required">{{ trans('labels.from_coins') }}</label>
                                    <input type="number" class="form-control" min="0" name="from_coins"
                                        id="from_coins" placeholder="{{ trans('labels.from_coins') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="to_coins"
                                        class="form-label required">{{ trans('labels.to_coins') }}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" min="0" name="to_coins" id="to_coins"
                                        placeholder="{{ trans('labels.to_coins') }}">
                                        <span class="input-group-text bg-transparent">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="upton" id="upton">
                                                <label class="form-check-label" for="upton"> {{ trans('labels.up_to_n') }} </label>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="additional_coins_pr"
                                        class="form-label">{{ trans('labels.additional_coins_pr') }}</label>
                                    <input type="number" class="form-control" min="0" max="100"
                                        name="additional_coins_pr" id="additional_coins_pr"
                                        placeholder="{{ trans('labels.additional_coins_pr') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" type="reset"
                            data-bs-dismiss="modal">{{ trans('labels.cancel') }}</button>
                        <button class="btn btn-primary" type="submit">{{ trans('labels.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/plans/plans.js') }}"></script>
@endsection
