@extends('admin.layout.default')
@section('content')
    @php
        $arr = [7 => 'Last 7 days', 3 => 'Last 3 Months', 6 => 'Last 6 Months', 1 => 'This Year'];
    @endphp
    <div class="content">
        <div class="row gy-3 mb-4 justify-content-between">
            <div class="col-12 col-xxl-6 mb-3 mb-sm-0">
                <h3 class="mb-4">Dashboard</h3>
                <div class="row g-0">
                    <div class="col-6 col-xl-3">
                        <a href="{{ route('job-providers.index') }}"
                            class="d-flex flex-column flex-center align-items-sm-start flex-md-row justify-content-md-between flex-xxl-column p-3 ps-sm-3 ps-md-4 p-md-3 h-100 border-1 border-bottom border-end">
                            <div class="d-flex align-items-center mb-1">
                                <span class="fa-solid fa-square fs--3 me-2 text-primary"
                                    data-fa-transform="up-2"></span><span class="mb-0 fs--1 text-900">
                                    {{ trans('labels.job_providers') }} </span>
                            </div>
                            <h3 class="fw-semi-bold ms-xl-3 ms-xxl-0 pe-md-2 pe-xxl-0 mb-0 mb-sm-3"> {{ $total_providers }}
                            </h3>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a href="{{ route('job-seekers.index') }}"
                            class="d-flex flex-column flex-center align-items-sm-start flex-md-row justify-content-md-between flex-xxl-column p-3 ps-sm-3 ps-md-4 p-md-3 h-100 border-1 border-bottom border-end-md-0 border-end-xl">
                            <div class="d-flex align-items-center mb-1">
                                <span class="fa-solid fa-square fs--3 me-2 text-success"
                                    data-fa-transform="up-2"></span><span class="mb-0 fs--1 text-900">
                                    {{ trans('labels.job_seekers') }} </span>
                            </div>
                            <h3 class="fw-semi-bold ms-xl-3 ms-xxl-0 pe-md-2 pe-xxl-0 mb-0 mb-sm-3"> {{ $total_seekers }}
                            </h3>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a href="{{ route('spam-requests.index') }}"
                            class="d-flex flex-column flex-center align-items-sm-start flex-md-row justify-content-md-between flex-xxl-column p-3 ps-sm-3 ps-md-4 p-md-3 h-100 border-1 border-bottom border-end ">
                            <div class="d-flex align-items-center mb-1">
                                <span class="fa-solid fa-square fs--3 me-2 text-danger"
                                    data-fa-transform="up-2"></span><span
                                    class="mb-0 fs--1 text-900">{{ trans('labels.spam_requests') }}</span>
                            </div>
                            <h3 class="fw-semi-bold ms-xl-3 ms-xxl-0 pe-md-2 pe-xxl-0 mb-0 mb-sm-3">
                                {{ $total_spam_requests }} </h3>
                        </a>
                    </div>

                    <div class="col-6 col-xl-3">
                        <a href="{{ route('feedbacks.index') }}"
                            class="d-flex flex-column flex-center align-items-sm-start flex-md-row justify-content-md-between flex-xxl-column p-3 ps-sm-3 ps-md-4 p-md-3 h-100 border-1 border-bottom border-end border-end-md border-end-xl-0">
                            <div class="d-flex align-items-center mb-1">
                                <span class="fa-solid fa-square fs--3 me-2 text-info" data-fa-transform="up-2"></span><span
                                    class="mb-0 fs--1 text-900"> {{ trans('labels.dispute_raised') }} </span>
                            </div>
                            <h3 class="fw-semi-bold ms-xl-3 ms-xxl-0 pe-md-2 pe-xxl-0 mb-0 mb-sm-3">
                                {{ $total_disputes }} </h3>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a href="{{ route('jobs.index') }}"
                            class="d-flex flex-column flex-center align-items-sm-start flex-md-row justify-content-md-between flex-xxl-column p-3 ps-sm-3 ps-md-4 p-md-3 h-100 border-1 border-end-xl border-bottom border-bottom-xl-0">
                            <div class="d-flex align-items-center mb-1">
                                <span class="fa-solid fa-square fs--3 me-2 text-info-300"
                                    data-fa-transform="up-2"></span><span class="mb-0 fs--1 text-900">
                                    {{ trans('labels.total_jobs') }} </span>
                            </div>
                            <h3 class="fw-semi-bold ms-xl-3 ms-xxl-0 pe-md-2 pe-xxl-0 mb-0 mb-sm-3"> {{ $total_jobs }}
                            </h3>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a href="{{ route('female-security.index') }}"
                            class="d-flex flex-column flex-center align-items-sm-start flex-md-row justify-content-md-between flex-xxl-column p-3 ps-sm-3 ps-md-4 p-md-3 h-100 border-1 border-end">
                            <div class="d-flex align-items-center mb-1">
                                <span class="fa-solid fa-square fs--3 me-2 text-danger-200"
                                    data-fa-transform="up-2"></span><span class="mb-0 fs--1 text-900">
                                    {{ trans('labels.female_security') }} {{ trans('labels.pending') }} </span>
                            </div>
                            <h3 class="fw-semi-bold ms-xl-3 ms-xxl-0 pe-md-2 pe-xxl-0 mb-0 mb-sm-3"> {{ $total_fsp_jobs }}
                            </h3>
                        </a>
                    </div>

                    <div class="col-6 col-xl-3">
                        <a href="{{ route('jobs.index') }}"
                            class="d-flex flex-column flex-center align-items-sm-start flex-md-row justify-content-md-between flex-xxl-column p-3 ps-sm-3 ps-md-4 p-md-3 h-100 border-1 border-end">
                            <div class="d-flex align-items-center mb-1">
                                <span class="fa-solid fa-square fs--3 me-2 text-warning-300"
                                    data-fa-transform="up-2"></span><span class="mb-0 fs--1 text-900">
                                    {{ trans('labels.available_jobs') }} </span>
                            </div>
                            <h3 class="fw-semi-bold ms-xl-3 ms-xxl-0 pe-md-2 pe-xxl-0 mb-0 mb-sm-3">
                                {{ $total_avail_jobs }} </h3>
                        </a>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div
                            class="d-flex flex-column flex-center align-items-sm-start flex-md-row justify-content-md-between flex-xxl-column p-3 ps-sm-3 ps-md-4 p-md-3 h-100 border-1">
                            <div class="d-flex align-items-center mb-1">
                                <span class="fa-solid fa-square fs--3 me-2 text-danger"
                                    data-fa-transform="up-2"></span><span class="mb-0 fs--1 text-900">
                                    {{ trans('labels.closed_jobs') }} </span>
                            </div>
                            <h3 class="fw-semi-bold ms-xl-3 ms-xxl-0 pe-md-2 pe-xxl-0 mb-0 mb-sm-3">
                                {{ $total_closed_jobs }} </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xxl-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3> Users Overview </h3>
                    <div>
                        <select class="form-select form-select-sm" name="users" id="users_filter">
                            @foreach ($arr as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <canvas id="userschart" height="90px" class="mb-5"></canvas>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3> Earnings </h3>
                    <div>
                        <select class="form-select form-select-sm" name="earnings" id="earnings_filter">
                            @foreach ($arr as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <canvas id="earningschart" height="90px" class="mb-5"></canvas>
            </div>
        </div>
        <div class="row g-3 my-5 mt-n7">
            <div class="col-xl-8">
                <div class="">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h3> Jobs Post Overview </h3>
                            <div>
                                <select class="form-select form-select-sm" name="jobs_post" id="jobspost_filter">
                                    @foreach ($arr as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <canvas id="jobspostchart" style="max-height: 300px" class="mb-5"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h3> Otp Overview </h3>
                            <div>
                                <select class="form-select form-select-sm" name="otphistory" id="otphistory_filter">
                                    @foreach ($arr as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <canvas id="otphistorychart"style="max-height: 300px" class="mb-5"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ url('resources/views/admin/dashboard/dashboard.js') }}"></script>
@endsection
