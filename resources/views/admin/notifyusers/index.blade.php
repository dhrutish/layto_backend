@extends('admin.layout.default')
@section('styles')
    <style>
        .label-info {
            background-color: #5bc0de;
            padding: 0 10px;
            margin: 5px;
        }
        .bootstrap-tagsinput input{
            width: 100% !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
@endsection
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate="" method="post" data-next="{{ route('notify-users.store') }}"
                    id="notifyusersform">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="title" class="form-label">{{ trans('labels.title') }}</label>
                                        <input class="form-control" name="title" id="title"
                                            placeholder="{{ trans('labels.title') }}">
                                    </div>
                                </div>
                                <div class="col-12 my-2">
                                    <label for="" class="form-label">{{ trans('labels.notification_type') }}</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check ps-0">
                                            <input class="form-check-input d-none filter-toolbar" data-placeholder="data-email" name="noti_type" id="radio1" type="radio" value="1" checked />
                                            <label class="form-check-label w-100 text-center cursor-pointer rounded-pill px-3 py-2" for="radio1"> {{ trans('labels.email_message') }} </label>
                                        </div>
                                        <div class="form-check ps-0">
                                            <input class="form-check-input d-none filter-toolbar" data-placeholder="" name="noti_type" id="radio2" type="radio" value="2" />
                                            <label class="form-check-label w-100 text-center cursor-pointer rounded-pill px-3 py-2" for="radio2"> {{ trans('labels.firebase_notification') }} </label>
                                        </div>
                                        <div class="form-check ps-0">
                                            <input class="form-check-input d-none filter-toolbar" data-placeholder="data-mobile" name="noti_type" id="radio3" type="radio" value="3" />
                                            <label class="form-check-label w-100 text-center cursor-pointer rounded-pill px-3 py-2" for="radio3"> {{ trans('labels.twilio_message') }} </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 my-2">
                                    <div class="form-group">
                                        <label for=""
                                            class="form-label">{{ trans('labels.select_user_type') }}</label>
                                        <div class="d-flex">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="user_type1" type="radio" name="user_type" value="1" checked />
                                                <label class="form-check-label" for="user_type1"> {{ trans('labels.job_providers') }} </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="user_type2" type="radio" name="user_type" value="2" />
                                                <label class="form-check-label" for="user_type2"> {{ trans('labels.job_seekers') }} </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="user_type3" type="radio" name="user_type" value="3" />
                                                <label class="form-check-label" for="user_type3"> {{ trans('labels.both') }} </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="user_type4" type="radio" name="user_type" value="4" />
                                                <label class="form-check-label" for="user_type4"> {{ trans('labels.other') }} </label>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control hidden" name="comma_values" id="comma_values" data-email="{{ trans('labels.email') }}" data-mobile="{{ trans('labels.mobile') }}" placeholder="" data-role="tagsinput" name="test_tags">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="description"
                                    class="form-label required">{{ trans('labels.description') }}</label>
                                <textarea class="form-control" name="description" id="description" placeholder="{{ trans('labels.description') }}"
                                    rows="10"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            {!! form_action_buttons(route('dashboard')) !!}
                        </div>
                        <input type="reset" class="d-none">
                    </div>
                </form>

                {{-- <div class="d-flex gap-3" id="toolbar">
                    <div class="form-check ps-0">
                        <input class="form-check-input d-none filter-toolbar" name="filters" id="first" type="radio" value="1" checked />
                        <label class="form-check-label cursor-pointer rounded-pill px-3 py-2" for="first"> {{ trans('labels.plan_notifyusers') }} </label>
                    </div>
                    <div class="form-check ps-0">
                        <input class="form-check-input d-none filter-toolbar" name="filters" id="2nd" type="radio" value="2" />
                        <label class="form-check-label cursor-pointer rounded-pill px-3 py-2" for="2nd"> {{ trans('labels.spam_notifyusers') }} </label>
                    </div>
                </div>
                <table class="table-responsive" id="table_notifyusers" data-url="{{ request()->url() }}" data-toggle="table"
                    data-show-copy-rows="true" data-side-pagination="server" data-pagination="true"
                    data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true"
                    data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                    data-pagination-successively-size="5" data-export-types='["xlsx"]'
                    data-export-options='{ "fileName": "{{basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' . date('d-m-y')}}", "ignoreColumn": ["action"] }' data-toolbar="#toolbar"
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
                </table> --}}
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/notifyusers/notifyusers.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.12.1/ckeditor.js"></script>
    <script type="text/javascript">
        $(function(params) {
            CKEDITOR.replace('description', {
                height: '200',
            });
            setTimeout(() => $('#cke_46').remove(), 400);
        })
        $(function(params) {
            "use strict";
            $('.bootstrap-tagsinput').addClass('d-none w-100');
        });
        $(document).on('change', 'input[name="noti_type"]', function() {
            $('.bootstrap-tagsinput .label-info').remove();
            if($('input[name="user_type"]:checked').val() == 4){
                $('.bootstrap-tagsinput input').attr('placeholder',$(this).val() == 1 ? 'Email address' : 'Mobile numbers' );
            }
        });
        $(document).on('change', 'input[name="user_type"]', function() {
            $(this).parent().parent().parent().find('.err').remove();
            if ($(this).val() == 4) {
                $('.bootstrap-tagsinput').removeClass('d-none');
                $('.bootstrap-tagsinput input').attr('placeholder',$('input[name="noti_type"]:checked').val() == 1 ? 'Email address' : 'Mobile numbers' );
            } else {
                $('.bootstrap-tagsinput').addClass('d-none');
            }
        });
    </script>
@endsection
