@extends('admin.layout.default')
@section('content')
    <div class="content">
        @include('admin.layout.breadcrumb')
        <div class="card">
            <div class="card-body">
                <form class="needs-validation" novalidate="" action="{{ route('general.settings.edit') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sign_up_seeker"
                                    class="form-label required">{{ trans('labels.sign_up_seeker') }}</label>
                                <input type="number" class="form-control" name="sign_up_seeker" id="sign_up_seeker"
                                    placeholder="{{ trans('labels.sign_up_seeker') }}"
                                    value="{{ old('sign_up_seeker') != '' && old('sign_up_seeker') != settingsdata()->sign_up_seeker ? old('sign_up_seeker') : settingsdata()->sign_up_seeker }}">
                                @error('sign_up_seeker')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sign_up_provider"
                                    class="form-label required">{{ trans('labels.sign_up_provider') }}</label>
                                <input type="number" class="form-control" name="sign_up_provider" id="sign_up_provider"
                                    placeholder="{{ trans('labels.sign_up_provider') }}"
                                    value="{{ old('sign_up_provider') != '' && old('sign_up_provider') != settingsdata()->sign_up_provider ? old('sign_up_provider') : settingsdata()->sign_up_provider }}">
                                @error('sign_up_provider')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <hr class="w-100">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="job_post_coins"
                                    class="form-label required">{{ trans('labels.job_post_coins') }}</label>
                                <input type="number" class="form-control" name="job_post_coins" id="job_post_coins"
                                    placeholder="{{ trans('labels.job_post_coins') }}"
                                    value="{{ old('job_post_coins') != '' && old('job_post_coins') != settingsdata()->job_post_coins ? old('job_post_coins') : settingsdata()->job_post_coins }}">
                                @error('job_post_coins')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apply_job_coins"
                                    class="form-label required">{{ trans('labels.apply_job_coins') }}</label>
                                <input type="number" class="form-control" name="apply_job_coins" id="apply_job_coins"
                                    placeholder="{{ trans('labels.apply_job_coins') }}"
                                    value="{{ old('apply_job_coins') != '' && old('apply_job_coins') != settingsdata()->apply_job_coins ? old('apply_job_coins') : settingsdata()->apply_job_coins }}">
                                @error('apply_job_coins')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="seeker_connect_coins"
                                    class="form-label required">{{ trans('labels.seeker_connect_coins') }}</label>
                                <input type="number" class="form-control" name="seeker_connect_coins" id="seeker_connect_coins"
                                    placeholder="{{ trans('labels.seeker_connect_coins') }}"
                                    value="{{ old('seeker_connect_coins') != '' && old('seeker_connect_coins') != settingsdata()->seeker_connect_coins ? old('seeker_connect_coins') : settingsdata()->seeker_connect_coins }}">
                                @error('seeker_connect_coins')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="job_auto_close_days"
                                    class="form-label required">{{ trans('labels.job_auto_close_days') }}</label>
                                <input type="number" class="form-control" name="job_auto_close_days"
                                    id="job_auto_close_days" placeholder="{{ trans('labels.job_auto_close_days') }}"
                                    value="{{ old('job_auto_close_days') != '' && old('job_auto_close_days') != settingsdata()->job_auto_close_days ? old('job_auto_close_days') : settingsdata()->job_auto_close_days }}">
                                @error('job_auto_close_days')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <hr class="w-100">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="referral_coins"
                                    class="form-label required">{{ trans('labels.referral_coins') }}</label>
                                <input type="number" class="form-control" name="referral_coins" id="referral_coins"
                                    placeholder="{{ trans('labels.referral_coins') }}"
                                    value="{{ old('referral_coins') != '' && old('referral_coins') != settingsdata()->referral_coins ? old('referral_coins') : settingsdata()->referral_coins }}">
                                @error('referral_coins')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="profile_switch_coins" class="form-label required">
                                    {{ trans('labels.profile_switch_coins') }} </label>
                                <input type="number" class="form-control" name="profile_switch_coins"
                                    id="profile_switch_coins" placeholder="{{ trans('labels.profile_switch_coins') }}"
                                    value="{{ old('profile_switch_coins') != '' && old('profile_switch_coins') != settingsdata()->profile_switch_coins ? old('profile_switch_coins') : settingsdata()->profile_switch_coins }}">
                                @error('profile_switch_coins')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="direct_contact_coins" class="form-label required">
                                    {{ trans('labels.direct_contact_coins') }} </label> --}}
                                <input type="hidden" class="form-control" name="direct_contact_coins"
                                    id="direct_contact_coins" placeholder="{{ trans('labels.direct_contact_coins') }}"
                                    value="{{ old('direct_contact_coins') != '' && old('direct_contact_coins') != settingsdata()->direct_contact_coins ? old('direct_contact_coins') : settingsdata()->direct_contact_coins }}">
                                {{-- @error('direct_contact_coins')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="coin_expire_days"
                                    class="form-label required">{{ trans('labels.coin_expire_days') }}</label>
                                <input type="number" class="form-control" name="coin_expire_days" id="coin_expire_days"
                                    placeholder="{{ trans('labels.coin_expire_days') }}"
                                    value="{{ old('coin_expire_days') != '' && old('coin_expire_days') != settingsdata()->coin_expire_days ? old('coin_expire_days') : settingsdata()->coin_expire_days }}">
                                @error('coin_expire_days')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for=""
                                            class="form-label ps-0">{{ trans('labels.is_gst_included') }}</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="radio" name="is_gst_included"
                                                    value="1" id="is_gst_included1"
                                                    {{ settingsdata()->is_gst_included == 1 ? 'checked' : '' }} checked>
                                                <label class="form-check-label" for="is_gst_included1">
                                                    {{ trans('labels.yes') }} </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="radio" name="is_gst_included"
                                                    value="2" id="is_gst_included2"
                                                    {{ settingsdata()->is_gst_included == 2 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_gst_included2">
                                                    {{ trans('labels.no') }} </label>
                                            </div>
                                        </div>
                                        @error('slot_duration')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8 gst-content">
                                    <div class="form-group">
                                        <label for="gst" class="form-label">{{ trans('labels.gst') }}</label>
                                        <input type="number" class="form-control" name="gst" id="gst"
                                            placeholder="{{ trans('labels.gst') }}"
                                            value="{{ old('gst') != '' && old('gst') != settingsdata()->gst ? old('gst') : settingsdata()->gst }}"
                                            max="100">
                                        @error('gst')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror

                                    </div>
                                </div>
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
