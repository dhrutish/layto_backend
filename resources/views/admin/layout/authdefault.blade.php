<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="msapplication-TileImage" content="{{ asset('storage/app/public/admin/assets/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ env('APP_NAME') }}</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/app/public/admin/assets/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/app/public/admin/assets/img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/app/public/admin/assets/img/favicons/favicon-16x16.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/app/public/admin/assets/img/favicons/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('storage/app/public/admin/assets/img/favicons/manifest.json') }}">

    <script src="{{ asset('storage/app/public/admin/vendors/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/assets/js/config.js') }}"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <link href="{{ asset('storage/app/public/admin/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    {{-- <link type="text/css" rel="stylesheet" id="style-rtl" href="{{ asset('storage/app/public/admin/assets/css/theme-rtl.min.css') }}"> --}}
    <link type="text/css" rel="stylesheet" id="style-default" href="{{ asset('storage/app/public/admin/assets/css/theme.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('storage/app/public/admin/assets/css/custom.css') }}">
    {{-- <link type="text/css" rel="stylesheet" id="user-style-default" href="https://prium.github.io/phoenix/v1.11.0/assets/css/user.min.css"> --}}
    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        // if (phoenixIsRTL) {
        //     var linkDefault = document.getElementById('style-default');
        //     var userLinkDefault = document.getElementById('user-style-default');
        //     linkDefault.setAttribute('disabled', true);
        //     userLinkDefault.setAttribute('disabled', true);
        //     document.querySelector('html').setAttribute('dir', 'rtl');
        // } else {
            if(document.getElementById('style-rtl') && document.getElementById('user-style-rtl')){
                var linkRTL = document.getElementById('style-rtl');
                var userLinkRTL = document.getElementById('user-style-rtl');
                linkRTL.setAttribute('disabled', true);
                userLinkRTL.setAttribute('disabled', true);
            }
        // }
    </script>
</head>

<body>
    @include('admin.layout.preloader')
    <main class="main" id="top">
        <div class="container-fluid px-0" data-layout="container">
            <div class="container">
                <div class="row flex-center min-vh-100 py-5">
                    <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3">
                        <a class="d-flex flex-center text-decoration-none mb-4" href="{{ URL::to('/') }}">
                            <div class="d-flex align-items-center fw-bolder fs-5 d-inline-block">
                                <img src="{{ asset('storage/app/public/admin/assets/img/icons/logo.png') }}"
                                    alt="phoenix" height="60" />
                            </div>
                        </a>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="support-chat-container">
            <div class="container-fluid support-chat">
                <div class="card bg-white">
                    <div class="card-header d-flex flex-between-center px-4 py-3 border-bottom">
                        <h5 class="mb-0 d-flex align-items-center gap-2">Demo widget<span
                                class="fa-solid fa-circle text-success fs--3"></span></h5>
                        <div class="btn-reveal-trigger"><button
                                class="btn btn-link p-0 dropdown-toggle dropdown-caret-none transition-none d-flex"
                                type="button" id="support-chat-dropdown" data-bs-toggle="dropdown"
                                data-boundary="window" aria-haspopup="true" aria-expanded="false"
                                data-bs-reference="parent"><span class="fas fa-ellipsis-h text-900"></span></button>
                            <div class="dropdown-menu dropdown-menu-end py-2" aria-labelledby="support-chat-dropdown"><a
                                    class="dropdown-item" href="sign-in.html#!">Request a callback</a><a
                                    class="dropdown-item" href="sign-in.html#!">Search in chat</a><a
                                    class="dropdown-item" href="sign-in.html#!">Show history</a><a class="dropdown-item"
                                    href="sign-in.html#!">Report to Admin</a><a class="dropdown-item btn-support-chat"
                                    href="sign-in.html#!">Close Support</a></div>
                        </div>
                    </div>
                    <div class="card-body chat p-0">
                        <div class="d-flex flex-column-reverse scrollbar h-100 p-3">
                            <div class="text-end mt-6"><a
                                    class="mb-2 d-inline-flex align-items-center text-decoration-none text-1100 hover-bg-soft rounded-pill border border-primary py-2 ps-4 pe-3"
                                    href="sign-in.html#!">
                                    <p class="mb-0 fw-semi-bold fs--1">I need help with something</p><span
                                        class="fa-solid fa-paper-plane text-primary fs--1 ms-3"></span>
                                </a><a
                                    class="mb-2 d-inline-flex align-items-center text-decoration-none text-1100 hover-bg-soft rounded-pill border border-primary py-2 ps-4 pe-3"
                                    href="sign-in.html#!">
                                    <p class="mb-0 fw-semi-bold fs--1">I can’t reorder a product I previously ordered
                                    </p><span class="fa-solid fa-paper-plane text-primary fs--1 ms-3"></span>
                                </a><a
                                    class="mb-2 d-inline-flex align-items-center text-decoration-none text-1100 hover-bg-soft rounded-pill border border-primary py-2 ps-4 pe-3"
                                    href="sign-in.html#!">
                                    <p class="mb-0 fw-semi-bold fs--1">How do I place an order?</p><span
                                        class="fa-solid fa-paper-plane text-primary fs--1 ms-3"></span>
                                </a><a
                                    class="false d-inline-flex align-items-center text-decoration-none text-1100 hover-bg-soft rounded-pill border border-primary py-2 ps-4 pe-3"
                                    href="sign-in.html#!">
                                    <p class="mb-0 fw-semi-bold fs--1">My payment method not working</p><span
                                        class="fa-solid fa-paper-plane text-primary fs--1 ms-3"></span>
                                </a></div>
                            <div class="text-center mt-auto">
                                <div class="avatar avatar-3xl status-online"><img
                                        class="rounded-circle border border-3 border-white"
                                        src="../../../assets/img/team/30.webp" alt="" /></div>
                                <h5 class="mt-2 mb-3">Eric</h5>
                                <p class="text-center text-black mb-0">Ask us anything – we’ll get back to you here or
                                    by email within 24 hours.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center gap-2 border-top ps-3 pe-4 py-3">
                        <div class="d-flex align-items-center flex-1 gap-3 border rounded-pill px-4"><input
                                class="form-control outline-none border-0 flex-1 fs--1 px-0" type="text"
                                placeholder="Write message" /><label
                                class="btn btn-link d-flex p-0 text-500 fs--1 border-0" for="supportChatPhotos"><span
                                    class="fa-solid fa-image"></span></label><input class="d-none" type="file"
                                accept="image/*" id="supportChatPhotos" /><label
                                class="btn btn-link d-flex p-0 text-500 fs--1 border-0" for="supportChatAttachment">
                                <span class="fa-solid fa-paperclip"></span></label><input class="d-none"
                                type="file" id="supportChatAttachment" /></div><button
                            class="btn p-0 border-0 send-btn"><span
                                class="fa-solid fa-paper-plane fs--1"></span></button>
                    </div>
                </div>
            </div><button class="btn p-0 border border-200 btn-support-chat"><span
                    class="fs-0 btn-text text-primary text-nowrap">Chat demo</span><span
                    class="fa-solid fa-circle text-success fs--1 ms-2"></span><span
                    class="fa-solid fa-chevron-down text-primary fs-1"></span></button>
        </div> --}}
    </main>

    @include('admin.layout.toastr')

    <script src="{{ asset('storage/app/public/admin/assets/js/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/vendors/lodash/lodash.min.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="{{ asset('storage/app/public/admin/vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/vendors/dayjs/dayjs.min.js') }}"></script>
    <script src="{{ asset('storage/app/public/admin/assets/js/phoenix.js') }}"></script>

    <script src="{{ url('storage/app/public/admin/assets/js/sweetalert2.all.min.js') }}"></script>

    <script src="{{ url('storage/app/public/admin/assets/js/toastr.js') }}"></script>
    <script>
        let are_you_sure = {{ Js::from(trans('messages.are_you_sure')) }};
        let yes = {{ Js::from(trans('messages.yes_sure')) }};
        let no = {{ Js::from(trans('messages.no_cancel')) }};
        let wrong = {{ Js::from(trans('messages.error')) }};
        let oops = {{ Js::from(trans('messages.oops')) }};
        let bs_spinner = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

        @if (Session::has('error'))
            showtoast('danger', {{ Js::from(Session::get('error')) }});
        @endif
        @if (Session::has('success'))
            showtoast('success', {{ Js::from(Session::get('success')) }});
        @endif
    </script>
    <script src="{{ url('storage/app/public/admin/assets/js/custom.js') }}"></script>
    @yield('scripts')
</body>

</html>
