@extends('admin.layout.default')
@section('content')
    <div class="content">

        @include('admin.layout.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate="" action="{{ route('email.settings.edit') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mailer" class="form-label required">{{ trans('labels.mail_mailer') }}</label>
                                <input type="text" class="form-control" name="mailer" id="mailer"
                                    value="{{ env('MAIL_MAILER') }}" placeholder="{{ trans('labels.mail_mailer') }}">
                                @error('mailer')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="port" class="form-label required">{{ trans('labels.mail_port') }}</label>
                                <input type="text" class="form-control" name="port" id="port"
                                    value="{{ env('MAIL_PORT') }}" placeholder="Enter Port">
                                @error('port')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="host" class="form-label required">{{ trans('labels.mail_host') }}</label>
                                <input type="text" class="form-control" name="host" id="host"
                                    value="{{ env('MAIL_HOST') }}" placeholder="{{ trans('labels.mail_host') }}">
                                @error('host')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="encryption"
                                    class="form-label required">{{ trans('labels.mail_encryption') }}</label>
                                <select class="form-select" name="encryption" id="encryption">
                                    <option selected value="tls"
                                        {{ env('MAIL_ENCRYPTION') == 'tls' ? 'selected' : '' }}>
                                        {{ trans('labels.mail_tls') }} </option>
                                    <option value="ssl" {{ env('MAIL_ENCRYPTION') == 'ssl' ? 'selected' : '' }}>
                                        {{ trans('labels.mail_ssl') }} </option>
                                </select>
                                @error('encryption')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label required"
                                    for="username">{{ trans('labels.mail_username') }}</label>
                                <input type="text" class="form-control" name="username" id="username"
                                    value="{{ env('MAIL_USERNAME') }}" placeholder="{{ trans('labels.mail_username') }}">
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">{{ trans('labels.mail_password') }}</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="{{ trans('labels.mail_password') }}">
                                @error('password')
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
