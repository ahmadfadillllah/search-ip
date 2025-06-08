<!-- ========== Topbar Start ========== -->
<div class="navbar-custom">
    <div class="topbar">
        <div class="topbar-menu d-flex align-items-center gap-lg-2 gap-1">

            <!-- Brand Logo -->
            <div class="logo-box">
                <!-- Brand Logo Light -->
                <a href="index.html" class="logo-light">
                    <img src="{{ asset('home/Admin/dist') }}/assets/images/logo-light.png" alt="logo" class="logo-lg" height="22">
                    <img src="{{ asset('home/Admin/dist') }}/assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="22">
                </a>

                <!-- Brand Logo Dark -->
                <a href="index.html" class="logo-dark">
                    <img src="{{ asset('home/Admin/dist') }}/assets/images/logo-dark.png" alt="dark logo" class="logo-lg" height="22">
                    <img src="{{ asset('home/Admin/dist') }}/assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="22">
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="button-toggle-menu">
                <i class="mdi mdi-menu"></i>
            </button>
            <a href="index.html" class="logo-dark">
                <img src="{{ asset('home/Admin/dist') }}/assets/images/sims.png" alt="dark logo" class="logo-lg" height="40">
                <img src="{{ asset('home/Admin/dist') }}/assets/images/logo.png" alt="dark logo" class="logo-lg" height="40" style="margin-left: 10px;">
            </a>
        </div>

        <ul class="topbar-menu d-flex align-items-center gap-4">

            <li class="d-none d-md-inline-block">
                <a class="nav-link" href="" data-bs-toggle="fullscreen">
                    <i class="mdi mdi-fullscreen font-size-24"></i>
                </a>
            </li>


            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle waves-effect waves-light arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="mdi mdi-bell font-size-24"></i>
                    <span class="badge bg-danger rounded-circle noti-icon-badge">1</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg py-0">
                    <div class="p-2 border-top-0 border-start-0 border-end-0 border-dashed border">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 font-size-16 fw-semibold"> Notification</h6>
                            </div>
                            <div class="col-auto">
                                <a href="javascript: void(0);" class="text-dark text-decoration-underline">
                                    <small>Clear All</small>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="px-1" style="max-height: 300px;" data-simplebar>

                        <h5 class="text-muted font-size-13 fw-normal mt-2">Today</h5>
                        <!-- item-->

                        <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card unread-noti shadow-none mb-1">
                            <div class="card-body">
                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="notify-icon bg-primary">
                                            <i class="mdi mdi-comment-account-outline"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <h5 class="noti-item-title fw-semibold font-size-14">Ahmad Fadillah <small class="fw-normal text-muted ms-1">1 min ago</small></h5>
                                        <small class="noti-item-subtitle text-muted">Gunakan Web ini Seperlunya</small>
                                    </div>
                                </div>
                            </div>
                        </a>


                        <div class="text-center">
                            <i class="mdi mdi-dots-circle mdi-spin text-muted h3 mt-0"></i>
                        </div>
                    </div>

                    <!-- All-->
                    <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item border-top border-light py-2">
                        View All
                    </a>

                </div>
            </li>

            <li class="nav-link" id="theme-mode">
                <i class="bx bx-moon font-size-24"></i>
            </li>

            {{-- <li class="dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ asset('home/Admin/dist') }}/assets/images/users/avatar-4.jpg" alt="user-image" class="rounded-circle">
                    <span class="ms-1 d-none d-md-inline-block">
                        IT SIMS <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome, Guest!</h6>
                    </div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>My Account</span>
                    </a>
                </div>
            </li> --}}

        </ul>
    </div>
</div>
<!-- ========== Topbar End ========== -->
<div class="px-3">
