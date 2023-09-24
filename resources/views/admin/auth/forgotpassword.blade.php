@extends('admin.layout.authdefault')
@section('content')
    <div class="text-center mb-5">
        <h4 class="text-1000 mb-3">{{ trans('labels.forgot_password_q') }}</h4>
        <p class="text-700">{{ trans('labels.forgot_password_note') }}</p>
    </div>
    <form class="needs-validation" novalidate="" action="{{ route('password.send') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label required" for="email">{{ trans('labels.email') }}</label>
                    <div class="form-icon-container">
                        <input class="form-control form-icon-input" id="email" type="email" name="email"
                            value="{{ old('email') }}" placeholder="{{ trans('labels.email') }}" />
                        <span class="fas fa-user text-900 fs--1 form-icon"></span>
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-primary w-100 my-3">{{ trans('labels.send') }} {{ trans('labels.password') }}
                    {{-- <i class="fas fa-chevron-right ms-2"></i> --}}
                </button>
            </div>
        </div>
    </form>
    <div class="text-center">
        <a class="fs--1 fw-semi-bold" href="{{ route('admin.login') }}">{{ trans('labels.remember_password_q') }}</a>
    </div>
@endsection
