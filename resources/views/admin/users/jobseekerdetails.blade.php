@extends('admin.layout.default')
@section('content')

    <div class="content">

        <div class="mb-9">
            <div class="row align-items-center justify-content-between g-3 mb-4">
                <div class="col-auto">
                    <h3 class="mb-0"> {{ trans('labels.seeker_info') }} </h3>
                </div>
                <div class="col-auto">
                    <div class="row g-3">
                        <div class="col-auto">
                            @if ($udata->is_available == 1)
                                <a class="btn btn-outline-danger" onclick="changestatus('{{ $udata->id }}',2,'{{ route('job-proividers.status') }}')" href="javascript:;"> <i class="fa-solid fa-ban me-2"></i><span> {{ trans('labels.make_unavailable') }} </span> </a>
                            @else
                                <a class="btn btn-outline-success" onclick="changestatus('{{ $udata->id }}',1,'{{ route('job-proividers.status') }}')" href="javascript:;"> <i class="fa-solid fa-check me-2"></i> <span> {{ trans('labels.make_available') }} </span> </a>
                            @endif
                        </div>
                        @if ($udata->resume != '')
                            <div class="col-auto">
                                <a href="{{ URL::to('resume-' . $udata->resume->image) }}" target="_blank" class="btn btn-outline-info"> <i class="fas fa-file-alt me-2"></i> Resume </a>
                            </div>
                        @endif
                        <div class="col-auto">
                            <a class="btn btn-outline-secondary reset-password" data-uid="{{ $udata->id }}" data-bs-toggle="modal" data-bs-target="#resetpassmodal" href="javascript:;"> <i class="fas fa-key me-2"></i> {{ trans('labels.reset_password') }} </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-12 col-xxl-4">
                    <div class="row g-3 g-xxl-0">
                        <div class="col-12 col-md-7 col-xxl-12 mb-xxl-3">
                            <div class="card">
                                <div class="card-body d-flex flex-column justify-content-between pb-3">
                                    <div class="row align-items-center g-5 mb-3 text-center text-sm-start">
                                        <div class="col-12 col-sm-auto mb-sm-2">
                                            <div class="avatar avatar-5xl">
                                                <img class="rounded-circle" src="{{ $udata->image_url }}" alt="" />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-auto flex-1">
                                            <h3>{{ $udata->name }}</h3>
                                            <p class="text-800">{{ $udata->email }}</p>
                                            <p class="text-800">{{ $udata->country_code ? '+' . $udata->country_code : '' }} {{$udata->mobile}}</p>
                                            <p class="text-800"> <i class="far fa-star me-1 text-warning"></i> {{ $udata->seeker_feedbacks_avg }} </p>
                                        </div>
                                    </div>
                                    @if (!empty($udata->about))
                                        <p class="text-800 mb-2">“{{ Str::limit($udata->about,400) }}”</p>
                                    @endif
                                    <div class="row flex-between-center border-top border-dashed border-300 pt-4">
                                        <div class="col-lg-6 mb-3">
                                            <h6 class="fw-bold">{{ trans('labels.referral_code') }}</h6>
                                            <p class="fs-1 text-800 mb-0"> <i class="far fa-qrcode me-1 text-info"></i> {{ $udata->referral_code }} </p>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <h6 class="fw-bold">{{ trans('labels.login_type') }}</h6>
                                            <p class="fs-1 text-800 mb-0"> <img src="{{ image_path('login' . $udata->login_type . '.png') }}" alt="login type"> {{ $udata->login_type_text }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5 col-xxl-12 mb-xxl-3">
                            <div class="card">
                                <div class="card-body pb-3">
                                    <h4 class="flex-1 mb-2">Address</h4>
                                    @if (!empty($udata->location))
                                        <div class="d-flex">
                                            <a href="{{ $udata->location->url }}" class="me-2 fw-bold text-primary fs-8"> <i class="fa fa-location-dot"></i> </a>
                                            <p class="fw-bold"> {{ $udata->location->address }}, {{ $udata->location->areas->title_en }}, {{ $udata->location->cities->title_en }}, {{ $udata->location->states->title_en }} - {{ $udata->location->pincode }}</p>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <img src="{{ image_path('nodata.png') }}" alt="" height="100">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5 col-xxl-12 mb-xxl-3">
                            <div class="card h-100">
                                <div class="card-body pb-3">
                                    <h4 class="flex-1 mb-2">ID Proof {!! !empty($udata->id_proof_details) ? ' : <small class="">' . $udata->id_proof_details->id_number . '</small>' : '' !!} </h4>
                                    @if (!empty($udata->id_proof_details))
                                        @if ($udata->id_proof_details->status == 1)
                                            <a class="btn btn-sm btn-outline-success fs--1 me-1" href="javascript:;" onclick="changestatus('{{ $udata->id_proof_details->id }}',2,'{{ URL::to('proofsstatus') }}')"> <i class="fa fa-check"></i> Verify</a>
                                            <a class="btn btn-sm btn-outline-danger fs--1" href="javascript:;" onclick="changestatus('{{ $udata->id_proof_details->id }}',3,'{{ URL::to('proofsstatus') }}')"> <i class="fa fa-close"></i> Reject</a>
                                        @elseif ($udata->id_proof_details->status == 2)
                                            <span class="text-success"> <i class="fa fa-check"></i> Verified</span>
                                        @else
                                            <span class="text-danger"> <i class="fa fa-close"></i> Rejected</span>
                                        @endif
                                    @endif
                                    @if (!empty($udata->id_proof_details))
                                        <div class="row g-3 mt-1">
                                            <div class="col-auto">
                                                <a href="{{ $udata->id_proof_details->back_image_url }}" target="_blank" data-gallery="gallery-photos"> <img class="rounded-3" src="{{ $udata->id_proof_details->back_image_url }}" alt="" height="100" /> </a>
                                            </div>
                                            <div class="col-auto">
                                                <a href="{{ $udata->id_proof_details->back_image_url }}" target="_blank" data-gallery="gallery-photos"> <img class="rounded-3" src="{{ $udata->id_proof_details->back_image_url }}" alt="" height="100" /> </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <img src="{{ image_path('nodata.png') }}" alt="" height="100">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-xxl-8">
                    <div class="mb-6">
                        <h3 class="mb-4"> Jobs Applies</h3>
                        <div class="border-top border-bottom border-200">
                            <div class="table-responsive">
                                <table class="table-responsive" id="table_jobsapplies" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= 'Jobs-' . $udata->name . '-' . date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParamsJobsApplies">
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                                            <th data-field="provider_name" data-sortable="false" data-visible="true"> Provider name </th>
                                            <th data-field="job_name" data-sortable="true" data-visible="true"> Job name </th>
                                            <th data-field="from_amount" data-sortable="true" data-visible="true"> From amount </th>
                                            <th data-field="to_amount" data-sortable="false" data-visible="true"> To amount </th>
                                            <th data-field="description" data-sortable="false" data-visible="true"> Description </th>
                                            <th data-field="created_at" data-sortable="true" data-visible="true"> Created at </th>
                                            <th data-field="status" data-sortable="false" data-visible="true"> Status </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <h3 class="mb-4"> Transaction History </h3>
                        <div class="border-200 border-top border-bottom">
                            <div class="table-responsive">
                                <div id="tool1">
                                    <button class="btn btn-sm btn-outline-success add" data-type="add" data-to="{{ $udata->id }}" data-next="{{ URL::to('manage-coins') }}" data-title="Add Coins"> <i class="fa fa-plus"></i> Add Coins </button>
                                    <button class="btn btn-sm btn-outline-danger deduct" data-type="deduct" data-to="{{ $udata->id }}" data-next="{{ URL::to('manage-coins') }}" data-title="Deduct Coins"> <i class="fa fa-minus"></i> Deduct Coins </button>
                                </div>
                                <table class="table-responsive" id="table_transactions" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-toolbar="#tool1" data-export-options='{ "fileName": "<?= 'Transactions-' . $udata->name . '-' . date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParamsTra">
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                                            <th data-field="image" data-sortable="false" data-visible="true"> {{ trans('labels.image') }} </th>
                                            <th data-field="coins" data-sortable="false" data-visible="true"> {{ trans('labels.coins') }} </th>
                                            <th data-field="description" data-sortable="false" data-visible="true"> {{ trans('labels.description') }} </th>
                                            <th data-field="created_at" data-sortable="true" data-visible="true"> {{ trans('labels.created_at') }} </th>
                                            <th data-field="days_left" data-sortable="false" data-visible="true"> {{ trans('labels.days_left') }} </th>
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
    @include('admin.users.resetpassmodal')
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/users/jobproviders.js') }}"></script>
    <script src="{{ url('resources/views/admin/users/managecoin.js') }}"></script>
@endsection
