@extends('admin.layout.authdefault')
@section('content')
    <div class="text-center mb-7">
        <h3 class="text-1000">{{ trans('labels.sign_in') }}</h3>
        <p class="text-700">{{ trans('labels.sign_in_note') }}</p>
    </div>
    {{-- <button class="btn btn-layto-secondary w-100 mb-3"><span class="fab fa-google text-danger me-2 fs--1"></span>Sign in with google</button>
        <button class="btn btn-layto-secondary w-100"><span class="fab fa-facebook text-primary me-2 fs--1"></span>Sign in with facebook</button>
        <div class="position-relative">
            <hr class="bg-200 mt-5 mb-4" />
            <div class="divider-content-center">or use email</div>
        </div> --}}
    <form class="needs-validation" novalidate="" action="{{ route('admin.check') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label required" for="email">{{ trans('labels.email') }}</label>
                    <div class="form-icon-container">
                        <input class="form-control form-icon-input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="{{ trans('labels.email') }}" />
                        <span class="fas fa-user text-900 fs--1 form-icon"></span>
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label required" for="password">{{ trans('labels.password') }}</label>
                    <div class="form-icon-container">
                        <input class="form-control form-icon-input" type="password" id="password" name="password" value="{{ old('password') }}" placeholder="{{ trans('labels.password') }}" />
                        <span class="fas fa-key text-900 fs--1 form-icon"></span>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-primary w-100 my-3">{{ trans('labels.sign_in') }}</button>
            </div>
        </div>
    </form>
    <div class="text-center">
        <a class="fs--1 fw-semi-bold" href="{{ route('password.forgot') }}">{{ trans('labels.forgot_password_q') }}</a>
    </div>
@endsection
