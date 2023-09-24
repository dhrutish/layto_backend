<div class="p-2">
    <p class="d-flex justify-content-center mb-2">
        @for ($i = 1; $i <= 5; $i++)
            <i class="far fa-star {{ $data->rating >= $i ? 'text-warning' : 'text-muted' }} fs-2"></i>
        @endfor
    </p>
    <p class="text-muted text-justify"> {{ $data->comment }} </p>
</div>
<div class="row">
    <div class="col-lg-12 mb-3">
        @if (!empty($data->job_info))
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <p class="flex-1"> {{ trans('labels.job_name') }} : <a href="{{ route('jobs.show',[$data->job_info->id]) }}" class="text-dark h5"> {{ $data->job_info->title }} </a> </p>
                        <p class="">
                            @if ($data->job_info->status == 2)
                                <span class="text-danger"> <i class="fa fa-ban"></i> {{ trans('labels.closed') }} </span>
                            @elseif ($data->job_info->status == 3)
                                <span class="text-danger"> <i class="fa-regular fa-triangle-exclamation"></i> {{ trans('labels.job_spamed') }} </span>
                            @elseif ($data->job_info->status == 4)
                                <span class="text-info"> <i class="fa fa-close"></i> {{ trans('labels.auto_closed') }} </span>
                            @elseif ($data->job_info->status == 5)
                                <span class="text-info"> <i class="fa fa-clock"></i> {{ trans('labels.pending_verification') }} </span>
                            @else
                                <span class="text-success"> <i class="fa fa-check"></i> {{ trans('labels.active') }} </span>
                            @endif
                        </p>
                    </div>
                    <p class="text-muted text-justify"> {{ $data->job_info->description }} </p>
                </div>
            </div>
        @endif
    </div>
    <div class="col-lg-6 mb-3">
        <p class="text-muted"> {{ trans('labels.provider_info') }} </p>
        <div class="card mb-3">
            <div class="card-body p-3">
                <div class="row align-items-center g-3 text-center text-sm-start">
                    <div class="col-12 col-sm-auto mb-sm-2">
                        <div class="avatar avatar-3xl">
                            <img class="rounded-circle" src="{{ $data->provider_info->image_url }}" alt="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-auto flex-1">
                        <h4> <a class="text-dark" href="{{ route('job-providers.show',[$data->provider_info->id]) }}"> {{ $data->provider_info->name }} </a> </h4>
                        <p class="text-800">{{ $data->provider_info->email }}</p>
                        <p class="text-800">{{ $data->provider_info->mobile }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-3">
        <p class="text-muted"> {{ trans('labels.seeker_info') }} </p>
        <div class="card mb-3">
            <div class="card-body p-3">
                <div class="row align-items-center g-3 text-center text-sm-start">
                    <div class="col-12 col-sm-auto mb-sm-2">
                        <div class="avatar avatar-3xl">
                            <img class="rounded-circle" src="{{ $data->seeker_info->image_url }}" alt="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-auto flex-1">
                        <h4> <a class="text-dark" href="{{ route('job-seekers.show',[$data->seeker_info->id]) }}"> {{ $data->seeker_info->name }} </a> </h4>
                        <p class="text-800">{{ $data->seeker_info->email }}</p>
                        <p class="text-800">{{ $data->seeker_info->mobile }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        @if ($data->is_dispute_created == 1)
            <div class="card mb-3">
                <div class="card-body p-3">
                    <b> <i class="fa-solid fa-triangle-exclamation text-danger"></i> {{ trans('labels.dispute') }} </b>
                    <p class="text-muted text-justify"> {{ $data->dispute_description }} </p>
                    @if ($data->dispute_status == 1)
                        <button class="btn btn-sm btn-outline-success" onclick="changestatus('{{ $data->id }}','2','{{ route('feedbacks.status') }}')"> <i class="fa-solid fa-check"></i> {{ trans('labels.accept') }} </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="changestatus('{{ $data->id }}','3','{{ route('feedbacks.status') }}')"> <i class="fa-solid fa-close"></i> {{ trans('labels.reject') }} </button>
                    @elseif ($data->dispute_status == 2)
                        <span class="fs-1 text-success"> <i class="fa-solid fa-check"></i> {{ trans('labels.accepted') }} </span>
                    @elseif ($data->dispute_status == 3)
                        <span class="fs-1 text-danger"> <i class="fa-solid fa-close"></i> {{ trans('labels.rejected') }} </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

