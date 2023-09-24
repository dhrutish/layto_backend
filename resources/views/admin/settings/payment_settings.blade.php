@extends('admin.layout.default')
@section('content')
    <div class="content">

        @include('admin.layout.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate="" action="{{ route('payment.settings.edit') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="public_key" class="form-label required">{{ trans('labels.public_key') }}</label>
                                <input type="text" class="form-control" name="public_key" id="public_key" value="{{ env('RAZORPAY_PUBLIC_KEY') }}" placeholder="{{ trans('labels.public_key') }}">
                                @error('public_key')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="secret_key" class="form-label required">{{ trans('labels.secret_key') }}</label>
                                <input type="text" class="form-control" name="secret_key" id="secret_key" value="{{ env('RAZORPAY_SECRET_KEY') }}" placeholder="{{ trans('labels.secret_key') }}">
                                @error('secret_key')
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
