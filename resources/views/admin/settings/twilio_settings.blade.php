@extends('admin.layout.default')
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate="" action="{{ route('twilio.settings.edit') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="account_sid"
                                    class="form-label required">{{ trans('labels.account_sid') }}</label>
                                <input type="text" class="form-control" name="account_sid" id="account_sid"
                                    value="{{ env('TWILIO_ACCOUNT_SID') }}" placeholder="{{ trans('labels.account_sid') }}">
                                @error('account_sid')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="auth_token" class="form-label required">{{ trans('labels.auth_token') }}</label>
                                <input type="text" class="form-control" name="auth_token" id="auth_token"
                                    value="{{ env('TWILIO_AUTH_TOKEN') }}" placeholder="{{ trans('labels.auth_token') }}">
                                @error('auth_token')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone_number"
                                    class="form-label required">{{ trans('labels.phone_number') }}</label>
                                <input type="tel" class="form-control" name="phone_number" id="phone_number"
                                    value="{{ env('TWILIO_PHONE_NUMBER') }}"
                                    placeholder="{{ trans('labels.phone_number') }}">
                                @error('phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            {!! form_action_buttons(route('dashboard')) !!}
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/settings/settings.js') }}"></script>
@endsection
