<body>

    <!-- Begin page -->
    <div class="layout-wrapper">
        <div class="main-menu">
            <!-- Brand Logo -->
            <div class="logo-box">
                <!-- Brand Logo Light -->
                <a href="#" class="logo-light">
                    <h3 class="logo-lg" style="color:blue" >IT-FMS Team</h3>
                    <img src="{{ asset('home/Admin/dist') }}/assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="28">
                </a>

                <!-- Brand Logo Dark -->
                <a href="#" class="logo-dark">
                    <h3 class="logo-lg" style="color:white" >IT-FMS Team</h3>
                    <img src="{{ asset('home/Admin/dist') }}/assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="28">
                </a>
            </div>

            <!--- Menu -->
            <div data-simplebar>
                <ul class="app-menu">

                    <li class="menu-title">Fitures</li>
                    <li class="menu-item">
                        <a href="{{ route('dashboard.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-home-smile"></i></span>
                            <span class="menu-text"> Home </span>
                        </a>
                    </li>

                    <li class="menu-title">Tower</li>
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
                        <a href="{{ route('maps.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-map"></i></span>
                            <span class="menu-text"> Maps Tower</span>
                        </a>
                    </li>
                    <li class="menu-title">Units</li>
                    <li class="menu-item">
                        <a href="{{ route('client.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-calendar"></i></span>
                            <span class="menu-text"> Clients </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('unit.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-file"></i></span>
                            <span class="menu-text"> Unit </span>
                        </a>
                    </li>
                    <li class="menu-title">Ritation</li>
                    {{-- <li class="menu-item">
                        <a href="{{ route('ritation.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bxs-eraser"></i></span>
                            <span class="menu-text"> Ritation Not Realtime</span>
                        </a>
                    </li> --}}
                    <li class="menu-item">
                        <a href="{{ route('periodicrealtime.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-cookie"></i></span>
                            <span class="menu-text"> Periodic Realtime</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('realtimeritation.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-share-alt"></i></span>
                            <span class="menu-text"> Realtime Ritation</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('connectivityrate.index') }}" class="menu-link waves-effect waves-light">
                            <span class="menu-icon"><i class="bx bx-braille"></i></span>
                            <span class="menu-text">Connectivity Rate</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="page-content">
