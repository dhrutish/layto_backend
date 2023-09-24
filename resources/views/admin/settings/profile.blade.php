@extends('admin.layout.default')
@section('content')
    <div class="content">

        @include('admin.layout.breadcrumb')

        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="card">
                    <div class="card-body">
                        <form class="needs-validation" novalidate="" action="{{ route('profile.edit') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="profile-upload">
                                            <div class="profile-edit ">
                                                <input type="file" class="d-none" name="image" id="imageupload"
                                                    accept=".png, .jpg, .jpeg">
                                                <label for="imageupload"
                                                    class="d-flex justify-content-center align-items-center bg-white"><i
                                                        class="fas fa-edit"></i></label>
                                            </div>
                                            <div class="profile-preview">
                                                <div id="imagepreview">
                                                    <img src="{{ auth()->user()->image_url }}" alt=""
                                                        id="imgupload">
                                                </div>
                                            </div>
                                        </div>
                                        @error('image')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label required">{{ trans('labels.name') }}</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            placeholder="{{ trans('labels.name') }}" value="{{ auth()->user()->name }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"
                                            class="form-label required">{{ trans('labels.email') }}</label>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="{{ trans('labels.email') }}" value="{{ auth()->user()->email }}">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile"
                                            class="form-label required">{{ trans('labels.mobile') }}</label>
                                        <input type="text" class="form-control" name="mobile" id="mobile"
                                            placeholder="{{ trans('labels.mobile') }}"
                                            value="{{ auth()->user()->mobile }}">
                                        @error('mobile')
                                            <small class="text-danger">{{ $message }}</small>
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
            <div class="col-md-6 d-flex">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('password.edit') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="current_password"
                                            class="form-label">{{ trans('labels.current_password') }}</label>
                                        <input type="password" class="form-control" name="current_password"
                                            id="current_password" placeholder="{{ trans('labels.current_password') }}" value="{{old('current_password')}}">
                                            @error('current_password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="new_password"
                                            class="form-label">{{ trans('labels.new_password') }}</label>
                                        <input type="password" class="form-control" name="new_password" id="new_password"
                                            placeholder="{{ trans('labels.new_password') }}" value="{{old('new_password')}}">
                                            @error('new_password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="confirm_password"
                                            class="form-label">{{ trans('labels.confirm_password') }}</label>
                                        <input type="password" class="form-control" name="confirm_password"
                                            id="confirm_password" placeholder="{{ trans('labels.confirm_password') }}" value="{{old('confirm_password')}}">
                                            @error('confirm_password')
                                                <small class="text-danger">{{ $message }}</small>
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
        </div>

    </div>
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/settings/settings.js') }}"></script>
@endsection
