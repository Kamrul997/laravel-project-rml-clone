<div class="sidebar-wrapper">
    <div class="sidebar sidebar-collapse" id="sidebar">
        <div class="sidebar__menu-group">
            <ul class="sidebar_nav">
                <li class="{{ request()->is('administrative/dashboard') ? 'active' : '' }}">
                    <a href="/" class="">
                        <span class="nav-icon uil uil-clipboard-notes"></span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-title mt-30">
                    <span>Components</span>
                </li>

                <li class="has-child {{ request()->is('administrative/*/*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->is('administrative/*/*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-setting"></span>
                        <span class="menu-text">Settings</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        <li class="{{ request()->is('administrative/permission/*') ? 'active' : '' }}">
                            <a href="{{ route('administrative.permission') }}">Permission</a>
                        </li>
                        <li class="{{ request()->is('administrative/role/*') ? 'active' : '' }}">
                            <a href="{{ route('administrative.role') }}">Role</a>
                        </li>
                        <li class="{{ request()->is('administrative/user/*') ? 'active' : '' }}">
                            <a href="{{ route('administrative.user') }}">User</a>
                        </li>
                    </ul>
                </li>

                {{-- <li class="has-child">
                    <a href="#" class="">
                        <span class="nav-icon uil uil-window-section"></span>
                        <span class="menu-text">Theme Settings</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        <li class="l_sidebar">
                            <a href="#" data-layout="light">Light Mode</a>
                        </li>
                        <li class="l_sidebar">
                            <a href="#" data-layout="dark">Dark Mode</a>
                        </li>
                    </ul>
                </li> --}}
            </ul>
        </div>
    </div>
</div>
