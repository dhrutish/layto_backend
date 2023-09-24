
<div class="card mb-3">
    <div class="card-body d-flex justify-content-between align-items-center py-3">
        @php
            $urlSegments = explode('/', request()->getPathInfo());
            $lastsegment = str_replace('-','_',end($urlSegments));
            $pageTitle = trans('labels.' . $lastsegment) == 'labels.'. $lastsegment ? ucfirst(str_replace('_',' ',$lastsegment)) : trans('labels.' . $lastsegment);
        @endphp
        <h5 class="text-primary">{{ $pageTitle }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ URL::to('dashboard') }}">{{ trans('labels.dashboard') }}</a></li>
                @php
                    $breadcrumb = '';
                @endphp
                @foreach ($urlSegments as $segment)
                    @if (!empty($segment))
                        @php
                            $breadcrumb .= '/' . $segment;
                            $segment = str_replace('-','_',$segment);
                            $title = trans('labels.' . $segment) == 'labels.'. $segment ? ucfirst(str_replace('_',' ',$segment)) : trans('labels.' . $segment)
                        @endphp
                        <li class="breadcrumb-item{{ $loop->last ? ' active' : '' }}" {{ $loop->last ? ' aria-current="page"' : '' }}>
                            @if ($loop->last)
                                {{ $title }}
                            @else
                                <a href="{{ $breadcrumb }}">{{ $title }}</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
</div>
