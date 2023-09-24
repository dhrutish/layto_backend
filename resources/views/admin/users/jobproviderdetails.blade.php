@extends('admin.layout.default')
@section('content')

    <div class="content">

        <div class="mb-9">
            <div class="row align-items-center justify-content-between g-3 mb-4">
                <div class="col-auto">
                    <h3 class="mb-0"> {{ trans('labels.provider_info') }} </h3>
                </div>
                <div class="col-auto">
                    <div class="row g-3">
                        <div class="col-auto">
                            @if ($udata->is_available == 1)
                                <a class="btn btn-outline-danger"onclick="changestatus('{{ $udata->id }}',2,'{{ route('job-proividers.status') }}')"href="javascript:;"><i class="fa-solid fa-ban me-2"></i><span> {{ trans('labels.make_unavailable') }}</span> </a>
                            @else
                                <a class="btn btn-outline-success" onclick="changestatus('{{ $udata->id }}',1,'{{ route('job-proividers.status') }}')" href="javascript:;"> <i class="fa-solid fa-check me-2"></i> <span> {{ trans('labels.make_available') }} </span> </a>
                            @endif
                        </div>
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
                                            <p class="text-800"><i class="far fa-star me-1 text-warning"></i> {{ $udata->provider_feedbacks_avg }}</p>
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
                                        <div class="col-lg-6 mb-3">
                                            <h6 class="fw-bold">{{ trans('labels.addressses') }}</h6>
                                            <p class="fs-1 text-800 mb-0"><i class="far fa-map-marker-alt me-1 text-success"></i> {{count($udata->locations)}} </p>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <h6 class="fw-bold">{{ trans('labels.jobs') }}</h6>
                                            <p class="fs-1 text-800 mb-0"><i class="far fa-briefcase me-1 text-primary"></i> {{$udata->total_jobs}} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5 col-xxl-12 mb-xxl-3">
                            <div class="card h-100">
                                <div class="card-body pb-3">
                                    <div class="accordion" id="accordionExample">
                                        <h4 class="flex-1 mb-2">{{ trans('labels.locations') }}</h4>
                                        @forelse ($udata->locations as $key => $location)
                                            <div class="accordion-item {{ $loop->last == true ? 'border-0' : '' }} ">
                                                <h2 class="accordion-header" id="heading{{$key}}">
                                                    <button class="accordion-button {{$key == 0 ? 'collapsed' : ''}}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}"> {{ $location->title }} </button>
                                                </h2>
                                                <div class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" id="collapse{{$key}}" aria-labelledby="heading{{$key}}" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body pt-0">
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ $location->url }}" class="fw-bold text-primary fs-8"> <i class="fa fa-location-dot"></i> </a>
                                                            <p class="fw-bold"> {{ trans('labels.address') }} : {{ $location->address }}, {{ $location->areas->title_en }}, {{ $location->cities->title_en }}, {{ $location->states->title_en }} - {{ $location->pincode }}</p>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="accordion-body pt-0">
                                                        <p class="fw-bold">Address : {{ $location->address }}</p>
                                                        <div class="d-flex gap-3">
                                                            <p class="fw-bold badge bg-light text-dark p-2">State : <span> {{ $location->states->title_en }}</span> </p>
                                                            <p class="fw-bold badge bg-light text-dark p-2">City : <span> {{ $location->cities->title_en }}</span> </p>
                                                            <p class="fw-bold badge bg-light text-dark p-2">Area : <span> {{ $location->areas->title_en }}</span> </p>
                                                            <p class="fw-bold badge bg-light text-dark p-2">Pincode : <span> {{ $location->pincode }}</span> </p>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center">
                                                <img src="{{ image_path('nodata.png') }}" alt="" height="100">
                                            </div>
                                        @endforelse

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5 col-xxl-12 mb-xxl-3">
                            <div class="card">
                                <div class="card-body pb-3">
                                    <h4 class="flex-1 mb-2"> {{ trans('labels.id_proof') }} {!! !empty($udata->id_proof_details)
                                        ? ' : <small class="">' . $udata->id_proof_details->id_number . '</small>'
                                        : '' !!} </h4>
                                    @if (!empty($udata->id_proof_details))
                                        @if ($udata->id_proof_details->status == 1)
                                            <a class="btn btn-sm btn-outline-success fs--1 me-1" href="javascript:;" onclick="changestatus('{{ $udata->id_proof_details->id }}',2,'{{ URL::to('proofsstatus') }}')"> <i class="fa fa-check"></i> {{ trans('labels.verify') }} </a>
                                            <a class="btn btn-sm btn-outline-danger fs--1" href="javascript:;" onclick="changestatus('{{ $udata->id_proof_details->id }}',3,'{{ URL::to('proofsstatus') }}')"> <i class="fa fa-close"></i> {{ trans('labels.reject') }} </a>
                                        @elseif ($udata->id_proof_details->status == 2)
                                            <span class="text-success"> <i class="fa fa-check"></i> {{ trans('labels.verified') }} </span>
                                        @else
                                            <span class="text-danger"> <i class="fa fa-close"></i> {{ trans('labels.rejected') }} </span>
                                        @endif
                                    @endif
                                    @if (!empty($udata->id_proof_details))
                                        <div class="row g-3 mt-1">
                                            <div class="col-auto">
                                                <a href="{{ $udata->id_proof_details->back_image_url }}" data-gallery="gallery-photos"> <img class="rounded-3" src="{{ $udata->id_proof_details->back_image_url }}" alt="" height="100" /> </a>
                                            </div>
                                            <div class="col-auto">
                                                <a href="{{ $udata->id_proof_details->back_image_url }}" data-gallery="gallery-photos"> <img class="rounded-3" src="{{ $udata->id_proof_details->back_image_url }}" alt="" height="100" /> </a>
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
                        <h3 class="mb-4"> {{ trans('labels.jobs') }} </h3>
                        <div class="border-top border-bottom border-200">
                            <div class="table-responsive">
                                <table class="table-responsive" id="table_jobs" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-export-options='{ "fileName": "<?= 'Jobs-' . $udata->name . '-' . date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParamsJobs">
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                                            <th data-field="provider_info" data-sortable="false" data-visible="false"> {{ trans('labels.provider_info') }} </th>
                                            <th data-field="title" data-sortable="true" data-visible="true"> {{ trans('labels.title') }} </th>
                                            <th data-field="min_salary" data-sortable="true" data-visible="true"> {{ trans('labels.min_salary') }} </th>
                                            <th data-field="max_salary" data-sortable="true" data-visible="true"> {{ trans('labels.max_salary') }} </th>
                                            <th data-field="candidates" data-sortable="true" data-visible="true"> {{ trans('labels.candidates') }} </th>
                                            <th data-field="industry_type" data-sortable="false" data-visible="true"> {{ trans('labels.industry_type') }} </th>
                                            <th data-field="payment_type" data-sortable="true" data-visible="false"> {{ trans('labels.payment_type') }} </th>
                                            <th data-field="education" data-sortable="true" data-visible="false"> {{ trans('labels.education') }} </th>
                                            <th data-field="created_at" data-sortable="true" data-visible="true"> {{ trans('labels.created_at') }} </th>
                                            <th data-field="reposted_on" data-sortable="true" data-visible="false"> {{ trans('labels.reposted_on') }} </th>
                                            <th data-field="status" data-sortable="false" data-visible="true"> {{ trans('labels.status') }} </th>
                                            <th data-field="action" data-sortable="false" data-visible="true"> {{ trans('labels.action') }} </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <h3 class="mb-4"> {{ trans('labels.transactions') }} </h3>
                        <div class="border-200 border-top border-bottom">
                            <div class="table-responsive">
                                <div id="tool1">
                                    <button class="btn btn-sm btn-outline-success add" data-type="add" data-to="{{ $udata->id }}" data-next="{{ URL::to('manage-coins') }}" data-title="Add Coins"> <i class="fa fa-plus"></i> Add Coins </button>
                                    <button class="btn btn-sm btn-outline-danger deduct" data-type="deduct" data-to="{{ $udata->id }}" data-next="{{ URL::to('manage-coins') }}" data-title="Deduct Coins"> <i class="fa fa-minus"></i> Deduct Coins </button>
                                </div>
                                <table class="table-responsive" id="table_transactions" data-url="{{ request()->url() }}" data-toggle="table" data-show-copy-rows="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-show-export="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-pagination-successively-size="5" data-export-types='["xlsx"]' data-toolbar="#tool1" data-export-options='{ "fileName": "<?= 'Transactions-' . $udata->name . '-' .date('d-m-y') ?>", "ignoreColumn": ["action"] }' data-query-params="queryParamsTra">
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-sortable="true" data-visible="true" data-width="80" data-width-unit="px">{{ trans('labels.srno') }}</th>
                                            <th data-field="image" data-sortable="false" data-visible="true"> {{ trans('labels.image') }} </th>
                                            <th data-field="coins" data-sortable="false" data-visible="true"> {{ trans('labels.coins') }} </th>
                                            <th data-field="description" data-sortable="false" data-visible="true"> {{ trans('labels.description') }} </th>
                                            <th data-field="created_at" data-sortable="false" data-visible="true"> {{ trans('labels.created_at') }} </th>
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
