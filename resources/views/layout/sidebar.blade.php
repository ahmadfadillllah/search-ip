<body>

    <!-- Begin page -->
    <div class="layout-wrapper">
        <div class="main-menu">
            <!-- Brand Logo -->
            <div class="logo-box">
                <!-- Brand Logo Light -->
                <a href="#" class="logo-light">
                    <h3 class="logo-lg" style="color:white" >IT Team</h3>
                    <img src="{{ asset('home/Admin/dist') }}/assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="28">
                </a>

                <!-- Brand Logo Dark -->
                <a href="#" class="logo-dark">
                    <h3 class="logo-lg" style="color:white" >IT Team</h3>
                    <img src="{{ asset('home/Admin/dist') }}/assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="28">
                </a>
            </div>

            <!--- Menu -->
            <div data-simplebar>
                <ul class="app-menu">

                    {{-- <li class="menu-title">Menu</li>

                    <li class="menu-item">
                        <a href="{{ route('dashboard.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-home-smile"></i></span>
                            <span class="menu-text"> Dashboards </span>
                            <span class="badge bg-primary rounded ms-auto">01</span>
                        </a>
                    </li> --}}

                    <li class="menu-title">Fitures</li>
                    <li class="menu-item">
                        <a href="{{ route('client.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-calendar"></i></span>
                            <span class="menu-text"> Clients </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('access_point.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-layout"></i></span>
                            <span class="menu-text"> Access Point </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('topology.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-aperture"></i></span>
                            <span class="menu-text"> Topology </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('unit.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-file"></i></span>
                            <span class="menu-text"> Unit </span>
                        </a>
                    </li>

                    <li class="menu-title">Extra Pages</li>

                    <li class="menu-item">
                        <a href="#" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-layout"></i></span>
                            <span class="menu-text"> Settings </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="page-content">
