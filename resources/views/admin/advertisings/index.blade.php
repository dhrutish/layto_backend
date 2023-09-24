@extends('admin.layout.default')
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">
            <div class="card-body">
                <table class="table-responsive" id="table_advertisings" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' . date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                            <th data-field="file" data-sortable="false" data-visible="true"> {{ trans('labels.file') }} </th>
                            <th data-field="url" data-sortable="true" data-visible="true"> {{ trans('labels.redirect_url') }} </th>
                            <th data-field="title" data-sortable="true" data-visible="true"> {{ trans('labels.title') }} </th>
                            <th data-field="expiry_date" data-sortable="true" data-visible="true"> {{ trans('labels.expiry_date') }} </th>
                            <th data-field="status" data-sortable="false" data-visible="true"> {{ trans('labels.status') }} </th>
                            <th data-field="action" data-sortable="false" data-visible="true"> {{ trans('labels.action') }} </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="advertisingsmodal" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="advertisingsmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="advertisingsmodalLabel" data-edit-title="{{ trans('labels.edit') }}" data-add-title="{{ trans('labels.create') }}"></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" novalidate="" method="post" data-next="{{ route('advertisings.store') }}"
                    id="advertisingsform" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="advertisingsid" id="advertisingsid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title" class="form-label required">{{ trans('labels.title') }}</label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="{{ trans('labels.title') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="url" class="form-label required">{{ trans('labels.redirect_url') }}</label>
                                    <input type="text" class="form-control" name="url" id="url" placeholder="{{ trans('labels.redirect_url') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="expiry_date"
                                        class="form-label required">{{ trans('labels.expiry_date') }}</label>
                                    @php
                                        $currentDateTime = date('Y-m-d\TH:i', strtotime('+1 day'));
                                    @endphp
                                    <input class="form-control" id="expiry_date" type="datetime-local" name="expiry_date" placeholder="{{ trans('labels.placeholder_expiry_date') }}" value="{{ $currentDateTime }}" min="{{ $currentDateTime . ' ' . date('H:i') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="afile" class="form-label required">{{ trans('labels.file') }}</label>
                                    <input type="file" class="form-control" name="afile" id="afile">
                                </div>
                                <img src="" alt="" id="imagefile" class="d-none" height="100">
                                <video src="" id="videofile" class="d-none" height="200" autoplay muted></video>
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
    <script src="{{ url('resources/views/admin/advertisings/advertisings.js') }}"></script>
@endsection
