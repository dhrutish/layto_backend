@extends('admin.layout.default')
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-3" id="toolbar">
                    <div class="form-check ps-0">
                        <input class="form-check-input d-none filter-toolbar" name="filters" id="first" type="radio" value="1" checked />
                        <label class="form-check-label cursor-pointer rounded-pill px-3 py-2" for="first"> {{ trans('labels.all') }} </label>
                    </div>
                    <div class="form-check ps-0">
                        <input class="form-check-input d-none filter-toolbar" name="filters" id="2nd" type="radio" value="2" />
                        <label class="form-check-label cursor-pointer rounded-pill px-3 py-2" for="2nd"> {{ trans('labels.new_requested') }}
                            @if (otherAreasCount() > 0)
                                <span class="badge rounded-pill text-bg-secondary cnt">{{ otherAreasCount() }}</span>
                            @endif
                        </label>
                    </div>
                </div>
                <table class="table-responsive" id="table_areas" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-toolbar="#toolbar" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' . date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                            <th data-field="state" data-sortable="false" data-visible="true"> {{ trans('labels.state') }} </th>
                            <th data-field="city" data-sortable="false" data-visible="true"> {{ trans('labels.city') }} </th>
                            <th data-field="title_en" data-sortable="true" data-visible="true"> {{ trans('labels.title_en') }} </th>
                            <th data-field="title_hi" data-sortable="true" data-visible="true"> {{ trans('labels.title_hi') }} </th>
                            <th data-field="title_gj" data-sortable="true" data-visible="true"> {{ trans('labels.title_gj') }} </th>
                            <th data-field="status" data-sortable="false" data-visible="true"> {{ trans('labels.status') }} </th>
                            <th data-field="action" data-sortable="false" data-visible="true"> {{ trans('labels.action') }} </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="areamodal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="areamodalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="areamodalLabel" data-edit-title="{{ trans('labels.edit') }}"
                        data-add-title="{{ trans('labels.create') }}"></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" novalidate="" method="post" data-next="{{ route('areas.store') }}"
                    id="areaform">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="areaid" id="areaid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="state" class="form-label required">{{ trans('labels.state') }}</label>
                                    <select class="form-select" name="state" id="state"
                                        data-next="{{ route('get.cities') }}">
                                        <option value="" selected disabled>{{ trans('labels.select') }}</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->title_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="city" class="form-label required">{{ trans('labels.city') }}</label>
                                    <select class="form-select" name="city" id="city" data-selected="">
                                        <option value="" selected>{{ trans('labels.select') }}</option>
                                    </select>
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
    <div class="modal fade" id="replacemodal" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="replacemodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="replacemodalLabel"> {{ trans('labels.replace_with') }} </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" novalidate="" action="{{ route('areas.type') }}" method="post"
                    id="areastypeform">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="type" id="type" value="2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="area" class="form-label required">{{ trans('labels.areas') }}</label>
                                    <select class="form-select" name="area" id="area">
                                        <option value="" selected disabled>{{ trans('labels.select') }}</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->title_en }}</option>
                                        @endforeach
                                    </select>
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
    <script src="{{ url('resources/views/admin/areas/areas.js') }}"></script>
@endsection
