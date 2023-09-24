@if (isset($data) && !empty($data->job_info))
    <div class="card mb-3">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between mb-2">
                <p class="flex-1"> {{ trans('labels.job_name') }} : <a href="{{ route('jobs.show', [$data->job_info->id]) }}" class="text-dark h5"> {{ $data->job_info->title }} </a> </p>
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
            @if (!in_array($data->job_info->status,[2,3,4]))
                <div class="text-end">
                    <a class="btn btn-outline-warning" href="javascript:;" onclick="changestatus('{{ $data->job_info->id }}','2','{{ route('jobs.status') }}')"> <i class="fa-solid fa-close me-2"></i> Close Job </a>
                    <a class="btn btn-outline-danger" href="javascript:;" onclick="changestatus('{{ $data->job_info->id }}','3','{{ route('jobs.status') }}')"> <i class="fa-solid fa-ban me-2"></i> Move to Spam Job </a>
                </div>
            @endif
        </div>
    </div>
@endif
