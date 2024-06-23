<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item nav-category">Menu</li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('unit.index') }}">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Unit</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('settings.index') }}">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Settings</span>
            </a>
        </li>
    </ul>
</nav>
<div class="main-panel">
