@extends('admin.layout.default')
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">

            {{-- <div class="row">
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Tab 1</a>
                        <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Tab 2</a>
                        <a class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Tab 3</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"> Content for Tab 1 </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab"> Content for Tab 2 </div>
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab"> Content for Tab 3 </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div id="toolbar">
                <button class="btn btn-primary">Button 1</button>
                <button class="btn btn-secondary">Button 2</button>
            </div> --}}

            <div class="card-body">
                <table class="table-responsive" id="table_subadmins"
                    data-url="{{ route('sub.admins.list') }}" data-toggle="table" data-show-copy-rows="true"
                    data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]"
                    data-search="true" data-show-export="true"
                    data-mobile-responsive="true" data-sort-name="id"
                    data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]'
                    data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' . date('d-m-y') ?>", "ignoreColumn": ["action","profile"] }'
                    data-query-params="queryParams">
                    {{-- data-filter-control="true" --}}
                    {{-- data-trim-on-search="false" --}}
                    {{-- data-show-print='true' --}}
                    {{-- data-fixed-columns="false" --}}
                    {{-- --------------------------- --}}
                    {{-- data-toolbar="#toolbar" --}}
                    {{-- data-show-toggle='true' --}}
                    {{-- data-show-refresh="true" --}}
                    {{-- data-click-to-select="true"  --}}
                    {{-- data-show-columns="true" --}}
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                            <th data-field="profile" data-sortable="false" data-visible="true" {{-- data-checkbox="false" data-force-hide="true" --}}> {{ trans('labels.profile') }} </th>
                            <th data-field="name" data-sortable="true" data-visible="true"> {{ trans('labels.name') }} </th>
                            <th data-field="email" data-sortable="true" data-visible="true"> {{ trans('labels.email') }} </th>
                            <th data-field="mobile" data-sortable="true" data-visible="true"> {{ trans('labels.mobile') }} </th>
                            <th data-field="status" data-sortable="true" data-visible="true" {{-- data-filter-control="select" --}}> {{ trans('labels.status') }} </th>
                            <th data-field="action" data-sortable="false" data-visible="true"> {{ trans('labels.action') }} </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="subadminmodal" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="subadminmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subadminmodalLabel"
                        data-edit-title="{{ trans('labels.edit') }}"
                        data-add-title="{{ trans('labels.create') }}"></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" novalidate="" method="post" data-next="{{ route('sub.admins.store') }}"
                    id="subadminform">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="userid" id="userid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="form-label required">{{ trans('labels.name') }}</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="{{ trans('labels.name') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email" class="form-label required">{{ trans('labels.email') }}</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="{{ trans('labels.email') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="mobile" class="form-label required">{{ trans('labels.mobile') }}</label>
                                    <input type="tel" class="form-control" name="mobile" id="mobile" placeholder="{{ trans('labels.mobile') }}">
                                </div>
                            </div>
                            <div class="col-md-12 password">
                                <div class="form-group">
                                    <label for="password" class="form-label required">{{ trans('labels.password') }}</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="{{ trans('labels.password') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" type="reset" data-bs-dismiss="modal">{{ trans('labels.cancel') }}</button>
                        <button class="btn btn-primary" type="submit">{{ trans('labels.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/users/users.js') }}"></script>
@endsection
