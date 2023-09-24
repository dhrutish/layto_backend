@extends('admin.layout.default')
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-3" id="toolbar">
                    <div class="form-check ps-0">
                        <input class="form-check-input d-none filter-toolbar" name="filters" id="first" type="radio"
                            value="1" checked />
                        <label class="form-check-label cursor-pointer rounded-pill px-3 py-2" for="first">
                            {{ trans('labels.plan_notes') }} </label>
                    </div>
                    <div class="form-check ps-0">
                        <input class="form-check-input d-none filter-toolbar" name="filters" id="2nd" type="radio"
                            value="2" />
                        <label class="form-check-label cursor-pointer rounded-pill px-3 py-2" for="2nd">
                            {{ trans('labels.spam_notes') }} </label>
                    </div>
                </div>
                <table class="table-responsive" id="table_notes" data-url="{{ request()->url() }}" data-toggle="table"
                    data-show-copy-rows="true" data-side-pagination="server" data-pagination="true"
                    data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true"
                    data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                    data-pagination-successively-size="5" data-export-types='["xlsx"]'
                    data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' .
                    date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-toolbar="#toolbar"
                    data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                            <th data-field="title_en" data-sortable="true" data-visible="true"> {{ trans('labels.title_en') }} </th>
                            <th data-field="title_hi" data-sortable="true" data-visible="true"> {{ trans('labels.title_hi') }} </th>
                            <th data-field="title_gj" data-sortable="true" data-visible="true"> {{ trans('labels.title_gj') }} </th>
                            <th data-field="action" data-sortable="false" data-visible="true"> {{ trans('labels.action') }}
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="notesmodal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="notesmodalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notesmodalLabel" data-edit-title="{{ trans('labels.edit') }}"
                        data-add-title="{{ trans('labels.create') }}"></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" novalidate="" method="post" data-next="{{ route('notes.store') }}"
                    id="notesform">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="notesid" id="notesid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check ps-0">
                                    <input class="form-check-input d-none filter-toolbar" name="type" id="radio1"
                                        type="radio" value="1" checked />
                                    <label class="form-check-label w-100 text-center cursor-pointer rounded-pill px-3 py-2"
                                        for="radio1"> {{ trans('labels.plan_notes') }} </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check ps-0">
                                    <input class="form-check-input d-none filter-toolbar" name="type" id="radio2"
                                        type="radio" value="2" />
                                    <label class="form-check-label w-100 text-center cursor-pointer rounded-pill px-3 py-2"
                                        for="radio2"> {{ trans('labels.spam_notes') }} </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title_en"
                                        class="form-label required">{{ trans('labels.title_en') }}</label>
                                    <input type="text" class="form-control" name="title_en" id="title_en"
                                        placeholder="{{ trans('labels.title_en') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title_hi"
                                        class="form-label required">{{ trans('labels.title_hi') }}</label>
                                    <input type="text" class="form-control" name="title_hi" id="title_hi"
                                        placeholder="{{ trans('labels.title_hi') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title_gj"
                                        class="form-label required">{{ trans('labels.title_gj') }}</label>
                                    <input type="text" class="form-control" name="title_gj" id="title_gj"
                                        placeholder="{{ trans('labels.title_gj') }}">
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
    <script src="{{ url('resources/views/admin/notes/notes.js') }}"></script>
@endsection
