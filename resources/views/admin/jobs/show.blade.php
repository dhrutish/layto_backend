{{--
    seeker_feedbacks_avg
    login_type_text
--}}
@extends('admin.layout.default')
@section('content')
    <div class="content">
        <div class="mb-9">
            <div class="row align-items-center justify-content-between g-3 mb-4">
                <div class="col-auto">
                    <h3 class="mb-0"> Job details </h3>
                </div>
                <div class="col-auto">
                    <div class="row g-3">
                        <div class="col-auto">
                            @if ($jobdata->status == 1)
                                <a class="btn btn-outline-danger" onclick="changestatus('{{ $jobdata->id }}',2,'{{ route('job-proividers.status') }}')" href="javascript:;"> <i class="fa-solid fa-ban me-2"></i><span> {{ trans('labels.make_unavailable') }} </span> </a>
                            @elseif ($jobdata->status == 5)
                            <a class="btn btn-outline-success" onclick="changestatus('{{ $jobdata->id }}',1,'{{ route('job-proividers.status') }}')" href="javascript:;"> <i class="fa-solid fa-check me-2"></i> <span> {{ trans('labels.approve_female_security') }} </span> </a>
                            @elseif ($jobdata->status == 2)
                                <span class="text-danger"> <i class="fa fa-ban"></i> {{trans('labels.closed')}} </span>
                            @elseif ($jobdata->status == 3)
                                <span class="text-danger"> <i class="fa-regular fa-triangle-exclamation"></i> {{trans('labels.job_spamed')}} </span>
                            @elseif ($jobdata->status == 4)
                                <span class="text-info"> <i class="fa fa-close"></i> {{trans('labels.auto_closed')}} </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-12 col-xxl-4">
                    <div class="row g-3 g-xxl-0 h-100">
                        <div class="col-12 col-md-5 col-xxl-12 mb-xxl-3">
                            <div class="card h-100">
                                <div class="card-body pb-3">
                                    <p class="flex-1 mb-2"> {{ trans('labels.job_title') }} : <span class="h4"> {{ $jobdata->title }} </span> </p>
                                    <p class="text-muted mb-3"> {{ $jobdata->description }} </p>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.industry_type') }} </p>
                                            <b class=""> {{ $jobdata->industry_types->title_en }} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.category') }} </p>
                                            <b class=""> {{ $jobdata->category->category->title_en }} </b>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <p class="flex-1"> {{ trans('labels.skills') }} </p>
                                            <b class="">
                                                {{ implode(', ',array_map(function ($item) {return $item['skill']['title_en'];}, $jobdata->skills->toArray())) }}
                                            </b>
                                        </div>


                                        <div class="col-lg-12 mb-3">
                                            <p class="flex-1"> {{ trans('labels.location_title') }} </p>
                                            <b class=""> {{ $jobdata->location_info->title }} </b>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <p class="flex-1"> {{ trans('labels.address') }} </p>
                                            <b class=""> {{ $jobdata->location_info->address }} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.state') }} </p>
                                            <b class=""> {{ $jobdata->location_info->state_id }} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.city') }} </p>
                                            <b class=""> {{ $jobdata->location_info->city_id }} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.area') }} </p>
                                            <b class=""> {{ $jobdata->location_info->area_id }} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.pincode') }} </p>
                                            <b class=""> {{ $jobdata->location_info->pincode }} </b>
                                        </div>


                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.job_type') }} </p>
                                            <b class=""> {{ $jobdata->availability->title_en }} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.payment_type') }} </p>
                                            <b class=""> {{ $jobdata->payment_types->title_en }} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.from_amount') }} </p>
                                            <b class=""> {{currency_formated($jobdata->min_salary)}} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.to_amount') }} </p>
                                            <b class=""> {{currency_formated($jobdata->max_salary)}} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.gender') }} </p>
                                            <b class=""> {{$jobdata->gender_text}} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.minimum_education') }} </p>
                                            <b class=""> {{$jobdata->education->title_en}} </b>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.experience_level') }} </p>
                                            <b class=""> {{$jobdata->experience_type_text}} </b>
                                        </div>
                                        @if ($jobdata->experience_type == 3)
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.experience_years') }} </p>
                                            <b class=""> {{$jobdata->exp_years}} </b>
                                        </div>
                                        @endif
                                        <div class="col-lg-6 mb-3">
                                            <p class="flex-1"> {{ trans('labels.required_candidates') }} </p>
                                            <b class=""> {{$jobdata->candidates}} </b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-7 col-xxl-12 mb-xxl-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-between pb-3">
                                    <div class="row align-items-center g-5 mb-3 text-center text-sm-start">
                                        <div class="col-12 col-sm-auto mb-sm-2">
                                            <div class="avatar avatar-5xl">
                                                <img class="rounded-circle" src="{{ $jobdata->user->image_url }}"
                                                    alt="">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-auto flex-1">
                                            <h3>{{ $jobdata->user->name }}</h3>
                                            <p class="text-800">{{ $jobdata->user->email }}</p>
                                            <p class="text-800"> {{ $jobdata->user->mobile }}</p>
                                            <p class="text-800"> <i class="far fa-star me-1 text-warning"></i>
                                                {{ $jobdata->user->provider_feedbacks_avg }} </p>
                                        </div>
                                    </div>
                                    <div class="row flex-between-center border-top border-dashed border-300 pt-4">
                                        <div class="col-lg-6 mb-3">
                                            <h6 class="fw-bold">Referral Code</h6>
                                            <p class="fs-1 text-800 mb-0"> <i class="far fa-qrcode me-1 text-info"></i>
                                                {{ $jobdata->user->referral_code }} </p>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <h6 class="fw-bold">Login type</h6>
                                            <p class="fs-1 text-800 mb-0"> <img
                                                    src="{{ image_path('login' . $jobdata->user->login_type . '.png') }}"
                                                    alt="login type"> {{ $jobdata->user->login_type_text }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xxl-8">
                    <div class="mb-6">
                        <h3 class="mb-4"> {{ trans('labels.job_applies') }} </h3>
                        <div class="border-top border-bottom border-200">
                            <div class="table-responsive">
                                <table class="table-responsive" id="table_jobsapplies" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' . date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParamsJobsApplies">
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                                            <th data-field="seeker_info" data-sortable="false" data-visible="true"> {{ trans('labels.seeker_info') }} </th>
                                            <th data-field="from_amount" data-sortable="true" data-visible="true"> {{ trans('labels.from_amount') }} </th>
                                            <th data-field="to_amount" data-sortable="true" data-visible="true"> {{ trans('labels.to_amount') }} </th>
                                            <th data-field="description" data-sortable="true" data-visible="true"> {{ trans('labels.description') }} </th>
                                            <th data-field="status" data-sortable="false" data-visible="true"> {{ trans('labels.status') }} </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <h3 class="mb-4"> {{ trans('labels.reviews') }} </h3>
                        <div class="border-200 border-top border-bottom">
                            <div class="table-responsive">
                                <table class="table-responsive" id="table_reviews" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= basename(parse_url(request()->url(), PHP_URL_PATH)) . '-' . date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParamsReviews">
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                                            <th data-field="seeker_info" data-sortable="false" data-visible="true"> {{ trans('labels.seeker_info') }} </th>
                                            <th data-field="rating" data-sortable="true" data-visible="true"> {{ trans('labels.rating') }} </th>
                                            <th data-field="comment" data-sortable="true" data-visible="true"> {{ trans('labels.comment') }} </th>
                                            <th data-field="created_at" data-sortable="true" data-visible="true"> {{ trans('labels.created_at') }} </th>
                                            <th data-field="status" data-sortable="false" data-visible="false"> {{ trans('labels.status') }} </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/jobs/jobs.js') }}"></script>
@endsection
