<nav class="navbar navbar-vertical navbar-expand-lg" style="display: none">
    <script>
        var navbarStyle = window.config.config.phoenixNavbarStyle;
        if (navbarStyle && navbarStyle !== "transparent") {
            document
                .querySelector("body")
                .classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <li class="nav-item">
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ URL::to('dashboard') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart">
                                        <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                        <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.dashboard') }}</span></span>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('plans') ? 'active' : '' }}" href="{{ route('plans') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="far fa-puzzle-piece"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.plans') }}</span></span>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('transactions') ? 'active' : '' }}" href="{{ route('transactions.index') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fas fa-exchange-alt"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.transactions') }}</span></span>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">{{ trans('labels.users') }}</p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    @if (auth()->user()->type == 1)
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('sub-admins*') ? 'active' : '' }}" href="{{ route('sub.admins') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"> <span data-feather="users"></span> </span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text"> {{ trans('labels.sub_admins') }} </span></span>
                            </div>
                        </a>
                    </div>
                    @endif
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('job-providers*') ? 'active' : '' }}" href="{{ URL::to('job-providers') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="far fa-user-cowboy"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text"> {{ trans('labels.job_providers') }} </span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('job-seekers*') ? 'active' : '' }}" href="{{ URL::to('job-seekers') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="far fa-user-tie"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text"> {{ trans('labels.job_seekers') }} </span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('proofs*') ? 'active' : '' }}" href="{{ URL::to('proofs') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="far fa-address-card"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text"> {{ trans('labels.proofs') }} </span> {{-- <span class="badge bg-danger rounded-pill">{{ pendingproofs('') }}</span> --}} </span>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">{{ trans('labels.jobs_feedbacks') }}</p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('jobs*') ? 'active' : '' }}" href="{{ route('jobs.index') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"> <span data-feather="briefcase"></span> </span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text"> {{ trans('labels.jobs') }} </span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('female-security*') ? 'active' : '' }}" href="{{ route('female-security.index') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"> <span class="fa fa-female"></span> </span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text"> {{ trans('labels.female_security') }} </span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('spam-requests') ? 'active' : '' }}" href="{{ route('spam-requests.index') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.spam_requests') }}</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('feedbacks') ? 'active' : '' }}" href="{{ route('feedbacks.index') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="far fa-comment-dots"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.feedbacks') }}</span></span>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">{{ trans('labels.promotions_marketing') }}</p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('advertisings*') ? 'active' : '' }}" href="{{ URL::to('advertisings') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fas fa-bullhorn"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.advertisings') }}</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('news-feeds*') ? 'active' : '' }}" href="{{ URL::to('news-feeds') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fas fa-newspaper"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.news_feed') }}</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('notify-users*') ? 'active' : '' }}" href="{{ URL::to('notify-users') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fas fa-bell"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.notify_users') }}</span></span>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">{{ trans('labels.master_menus') }}</p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('industry-types*') ? 'active' : '' }}" href="{{ URL::to('industry-types') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fa-regular fa-industry"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.industry_types') }}</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('categories*') ? 'active' : '' }}" href="{{ URL::to('categories') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fa-regular fa-tags"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.categories') }}</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('skills*') ? 'active' : '' }}" href="{{ URL::to('skills') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fa-regular fa-cogs"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.skills') }}</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('payment-types*') ? 'active' : '' }}" href="{{ URL::to('payment-types') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fa-regular fa-rupee-sign"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.payment_types') }}</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('education*') ? 'active' : '' }}" href="{{ URL::to('education') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="far fa-user-graduate"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.education') }}</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('availabilities*') ? 'active' : '' }}" href="{{ URL::to('availabilities') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fa-regular fa-clock"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.availabilities') }}</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="javascript:void(0)#locationsmaster" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="locationsmaster">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon"> <span class="fas fa-caret-right"></span> </div>
                                <span class="nav-link-icon"><i class="far fa-map-marker-alt"></i></span>
                                <span class="nav-link-text">{{ trans('labels.location_master') }}</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent {{ request()->is('states') || request()->is('cities*') || request()->is('areas*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse" id="locationsmaster">
                                <li class="collapsed-nav-item-title d-none">{{ trans('labels.location_master') }}</li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('states') ? 'active' : '' }}" href="{{ URL::to('states') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.states') }}</span> </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('cities') ? 'active' : '' }}" href="{{ URL::to('cities') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.cities') }}</span> </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('areas') ? 'active' : '' }}" href="{{ URL::to('areas') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.areas') }}</span> </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('notes*') ? 'active' : '' }}" href="{{ URL::to('notes') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="far fa-file-text"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.notes') }}</span></span>
                            </div>
                        </a>
                    </div>
                    {{-- <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('salary-types*') ? 'active' : '' }}" href="{{ URL::to('salary-types') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fa-regular fa-rupee-sign"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.salary_types') }}</span></span>
                            </div>
                        </a>
                    </div> --}}
                </li>
                {{-- <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Apps</p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1" href="javascript:void(0)" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span data-feather="message-square"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Chat</span></span>
                            </div>
                        </a>
                    </div>
                    <!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="javascript:void(0)#social" role="button"
                            data-bs-toggle="collapse" aria-expanded="false" aria-controls="social">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon"> <span class="fas fa-caret-right"></span> </div>
                                <span class="nav-link-icon"><span data-feather="share-2"></span></span><span class="nav-link-text">Social</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="social">
                                <li class="collapsed-nav-item-title d-none">Social</li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../apps/social/profile.html" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">Profile</span> </div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../apps/social/settings.html" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">Settings</span> </div>
                                    </a><!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div>
                </li> --}}

                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Others</p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->is('faqs*') ? 'active' : '' }}" href="{{ URL::to('faqs') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><i class="fas fa-question"></i></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">{{ trans('labels.faqs') }}</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="javascript:void(0)#cms" role="button"
                            data-bs-toggle="collapse" aria-expanded="false" aria-controls="cms">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon"> <span class="fas fa-caret-right"></span> </div>
                                <span class="nav-link-icon"><i class="fa-regular fa-list"></i></span>
                                <span class="nav-link-text">{{ trans('labels.cms') }}</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent {{ request()->is('privacy-policy') || request()->is('terms-conditions*') || request()->is('report-spam*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse" id="cms">
                                <li class="collapsed-nav-item-title d-none">{{ trans('labels.cms') }}</li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('privacy-policy') ? 'active' : '' }}" href="{{ route('privacy.policy') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.privacy_policy') }}</span> </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('terms-conditions') ? 'active' : '' }}" href="{{ route('terms.conditions') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.terms_conditions') }}</span> </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('report-spam') ? 'active' : '' }}" href="{{ route('report.spam') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.report_spam') }}</span> </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="javascript:void(0)#settings" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="settings">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon"> <span class="fas fa-caret-right"></span> </div>
                                <span class="nav-link-icon"><span data-feather="settings"></span></span>
                                <span class="nav-link-text">{{ trans('labels.settings') }}</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent {{ request()->is('profile') || request()->is('general-settings*') || request()->is('email-settings*') || request()->is('payment-settings*') || request()->is('twilio-settings*') ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse" id="settings">
                                <li class="collapsed-nav-item-title d-none">{{ trans('labels.settings') }}</li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="{{ route('profile') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.profile') }}</span> </div>
                                    </a>
                                </li>
                                @if (auth()->user()->type == 1)
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('email-settings*') ? 'active' : '' }}" href="{{ route('email.settings') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.email_settings') }}</span> </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('firebase-settings*') ? 'active' : '' }}" href="{{ route('firebase.settings') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.firebase_settings') }}</span> </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('payment-settings*') ? 'active' : '' }}" href="{{ route('payment.settings') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.payment_settings') }}</span> </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('twilio-settings*') ? 'active' : '' }}" href="{{ route('twilio.settings') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.twilio_settings') }}</span> </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('general-settings*') ? 'active' : '' }}" href="{{ route('general.settings') }}" data-bs-toggle="" aria-expanded="false">
                                        <div class="d-flex align-items-center"> <span class="nav-link-text">{{ trans('labels.general_settings') }}</span> </div>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="navbar-vertical-footer">
        <button
            class="btn navbar-vertical-toggle border-0 fw-semi-bold w-100 white-space-nowrap d-flex align-items-center">
            <i class="text-muted fas fa-grip-lines-vertical fs-0"></i>
            <i class="text-muted fas fa-arrow-left fs-0"></i>
            <span class="navbar-vertical-footer-text ms-2">Collapsed View</span>
        </button>
    </div>
</nav>
