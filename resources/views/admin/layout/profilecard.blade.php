<div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border border-300"
    aria-labelledby="navbarDropdownUser">
    <div class="card position-relative border-0">
        <div class="card-body p-0">
            <div class="text-center pt-4">
                <div class="avatar avatar-xl">
                    <img class="rounded-circle" src="{{ auth()->user()->image_url }}" alt="" />
                </div>
                <h6 class="mt-2 text-black">{{ auth()->user()->name }}</h6>
            </div>
            <hr>
        </div>
        <div class="overflow-auto scrollbar" {{-- style="height: 10rem" --}}>
            <ul class="nav d-flex flex-column mb-2 pb-1">
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('profile') }}">
                        <span class="me-2 text-900" data-feather="user"></span>
                        <span>{{ trans('labels.profile') }}</span>
                    </a>
                </li>
                @if (auth()->user()->type == 1)
                    <li class="nav-item"><a class="nav-link px-3" href="{{ route('general.settings') }}"><span class="me-2 text-900" data-feather="settings"></span> {{ trans('labels.general_settings') }} </a></li>
                @else
                    <li class="nav-item"><a class="nav-link px-3" href="{{ route('profile') }}"><span class="me-2 text-900" data-feather="settings"></span> {{ trans('labels.edit_profile') }} </a></li>
                @endif
                {{-- <li class="nav-item"> <a class="nav-link px-3" href="javascript:void(0)#!"><span class="me-2 text-900" data-feather="pie-chart"></span>Dashboard</a> </li> --}}
                {{-- <li class="nav-item"> <a class="nav-link px-3" href="javascript:void(0)#!"> <span class="me-2 text-900" data-feather="lock"></span>Posts &amp; Activity</a> </li> --}}
                {{-- <li class="nav-item"><a class="nav-link px-3" href="javascript:void(0)#!"><span class="me-2 text-900" data-feather="help-circle"></span>Help Center</a></li> --}}
                {{-- <li class="nav-item"><a class="nav-link px-3" href="javascript:void(0)#!"> <span class="me-2 text-900" data-feather="globe"></span>Language</a></li> --}}
            </ul>
        </div>
        <div class="card-footer p-3">
            {{-- <ul class="nav d-flex flex-column my-3">
            <li class="nav-item">
                <a class="nav-link px-3" href="javascript:void(0)#!"> <span class="me-2 text-900" data-feather="user-plus"></span>Add another account</a>
            </li>
        </ul>
        <hr /> --}}
            <a class="btn btn-layto-secondary d-flex flex-center w-100" href="javascript:;"
                onclick="logout('{{ route('logout') }}')"> <span class="me-2" data-feather="log-out"> </span>
                {{ trans('labels.sign_out') }}</a>
            {{-- <div class="my-2 text-center fw-bold fs--2 text-600">
            <a class="text-600 me-1" href="javascript:void(0)#!">Privacy policy</a>&bull;<a class="text-600 mx-1" href="javascript:void(0)#!">Terms</a>&bull;<a class="text-600 ms-1" href="javascript:void(0)#!">Cookies</a>
        </div> --}}
        </div>
    </div>
</div>
