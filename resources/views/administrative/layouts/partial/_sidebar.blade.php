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

                @can('collection_list')
                    <li class="{{ request()->routeIs('collection.index') ? 'active' : '' }}">
                        <a href="{{ route('collection.index') }}" class="">
                            <span class="nav-icon uil uil-list-ul"></span>
                            <span class="menu-text">My Collection</span>
                        </a>
                    </li>
                @endcan
                @can('customer_list')
                    <li class="{{ request()->routeIs('customer.index') ? 'active' : '' }}">
                        <a href="{{ route('customer.index') }}" class="">
                            <span class="nav-icon uil uil-user-nurse"></span>
                            <span class="menu-text">My Customer</span>
                        </a>
                    </li>
                @endcan
                @can('collection_approval_list')
                    <li class="{{ request()->routeIs('pending_collection.index') ? 'active' : '' }}">
                        <a href="{{ route('pending_collection.index') }}" class="">
                            <span class="nav-icon uil uil-check-circle"></span>
                            <span class="menu-text">Collection Approval</span>
                        </a>
                    </li>
                @endcan

                <li class="{{ request()->routeIs('reject_collection') ? 'active' : '' }}">
                    <a href="{{ route('reject_collection') }}" class="">
                        <span class="nav-icon uil uil-exclamation-triangle"></span>
                        <span class="menu-text">Previous Not Approve</span>
                    </a>
                </li>

                @can('organogram_list')
                    <li class="{{ request()->routeIs('c_s_organogram') ? 'active' : '' }}">
                        <a href="{{ route('c_s_organogram') }}" class="">
                            <span class="nav-icon uil uil-analysis"></span>
                            <span class="menu-text">Credit Sales Organogram</span>
                        </a>
                    </li>
                @endcan
                @can('target_list')
                    <li class="{{ request()->is('administrative/target/*') ? 'active' : '' }}">
                        <a href="{{ route('administrative.target') }}" class="">
                            <span class="nav-icon uil uil-record-audio"></span>
                            <span class="menu-text">Target Sheet</span>
                        </a>
                    </li>
                @endcan

                <li class="has-child {{ request()->is('administrative/import-target') ? 'open' : '' }}
                    {{ request()->is('administrative/import-customer') ? 'open' : '' }}
                    {{ request()->is('administrative/import-bank') ? 'open' : '' }}
                    {{ request()->is('administrative/import-account-statement') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->is('administrative/import-target') ? 'active' : '' }}
                        {{ request()->is('administrative/import-customer') ? 'active' : '' }}
                        {{ request()->is('administrative/import-bank') ? 'active' : '' }}
                        {{ request()->is('administrative/import-account-statement') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-import"></span>
                        <span class="menu-text">Import</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        <li class="{{ request()->is('administrative/import-target') ? 'active' : '' }}">
                            <a href="{{ route('view_target_import') }}">Target Sheet</a>
                        </li>
                        <li class="{{ request()->is('administrative/import-customer') ? 'active' : '' }}">
                            <a href="{{ route('view_customer_import') }}">Customer</a>
                        </li>
                        <li class="{{ request()->is('administrative/import-bank') ? 'active' : '' }}">
                            <a href="{{ route('view_bank_import') }}">Bank Sheet</a>
                        </li>
                        <li class="{{ request()->is('administrative/import-account-statement') ? 'active' : '' }}">
                            <a href="{{ route('view_account_statement_import') }}">Accout Statement</a>
                        </li>
                    </ul>
                </li>

                @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('National') || auth()->user()->hasRole('Accounts'))

                <li class="has-child {{ request()->is('administrative/make-payment-list') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->is('administrative/make-payment-list') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-bill"></span>
                        <span class="menu-text">Make payment</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        <li class="{{ request()->is('administrative/make-payment-list') ? 'active' : '' }}">
                            <a href="{{ route('make_payment_index') }}">List</a>
                        </li>
                    </ul>
                </li>

                @endif

                <li class="{{ request()->routeIs('administrative.forecast') ? 'active' : '' }}">
                    <a href="{{ route('administrative.forecast') }}" class="">
                        <span class="nav-icon uil uil-clock-three"></span>
                        <span class="menu-text">Forecast</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('profile.index') ? 'active' : '' }}">
                    <a href="{{ route('profile.index') }}" class="">
                        <span class="nav-icon uil uil-user-nurse"></span>
                        <span class="menu-text">Profile</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('reset_password') ? 'active' : '' }}">
                    <a href="{{ route('reset_password') }}" class="">
                        <span class="nav-icon uil uil-sync"></span>
                        <span class="menu-text">Reset Password</span>
                    </a>
                </li>

                <li class="has-child {{ request()->is('administrative/reports/*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->is('administrative/reports/*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-file-shield-alt"></span>
                        <span class="menu-text">Reports</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        <li class="{{ request()->is('administrative/reports/final-collection') ? 'active' : '' }}">
                            <a href="{{ route('administrative.final.collection') }}">Final Collection</a>
                        </li>
                        <li class="{{ request()->is('administrative/reports/deposit-date-wise-collection') ? 'active' : '' }}">
                            <a href="{{ route('administrative.deposit.date.wise.collection') }}">Deposite Date Wise Collection</a>
                        </li>
                        <li class="{{ request()->is('administrative/reports/approve-date-wise-collection') ? 'active' : '' }}">
                            <a href="{{ route('administrative.approve.date.wise.collection') }}">Approve Date Wise Collection</a>
                        </li>
                        <li class="{{ request()->is('administrative/reports/deposit-slip-wise-report') ? 'active' : '' }}">
                            <a href="{{ route('administrative.deposit.slip.wise.report') }}">Deposit Slip Wise Report</a>
                        </li>
                        <li class="{{ request()->is('administrative/reports/previous-not-approve') ? 'active' : '' }}">
                            <a href="{{ route('administrative.previous.not.approve') }}">Previous Not Approved</a>
                        </li>
                    </ul>
                </li>

                <li class="has-child {{ request()->is('administrative/statement/*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->is('administrative/statement/*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-post-stamp"></span>
                        <span class="menu-text">Statement</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        <li class="{{ request()->is('administrative/statement/account') ? 'active' : '' }}">
                            <a href="{{ route('administrative.statement.account') }}">Account Statement</a>
                        </li>
                        <li class="{{ request()->is('administrative/statement/final') ? 'active' : '' }}">
                            <a href="{{ route('administrative.statement.final') }}">Final Statement</a>
                        </li>
                    </ul>
                </li>

                <li
                    class="has-child {{ request()->is('administrative/role/*') ? 'open' : '' }}
                    {{ request()->is('administrative/permission/*') ? 'open' : '' }}
                    {{ request()->is('administrative/zone/*') ? 'open' : '' }}
                    {{ request()->is('administrative/area/*') ? 'open' : '' }}
                    {{ request()->is('administrative/unit/*') ? 'open' : '' }}
                    {{ request()->is('administrative/sub-unit/*') ? 'open' : '' }}
                    {{ request()->is('administrative/user/*') ? 'open' : '' }}">
                    <a href="#"
                        class="{{ request()->is('administrative/role/*') ? 'active' : '' }}
                        {{ request()->is('administrative/permission/*') ? 'active' : '' }}
                        {{ request()->is('administrative/zone/*') ? 'active' : '' }}
                        {{ request()->is('administrative/area/*') ? 'active' : '' }}
                        {{ request()->is('administrative/unit/*') ? 'active' : '' }}
                        {{ request()->is('administrative/sub-unit/*') ? 'active' : '' }}
                        {{ request()->is('administrative/user/*') ? 'active' : '' }}">
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
                        <li class="{{ request()->is('administrative/zone/*') ? 'active' : '' }}">
                            <a href="{{ route('administrative.zone') }}">Zone</a>
                        </li>
                        <li class="{{ request()->is('administrative/area/*') ? 'active' : '' }}">
                            <a href="{{ route('administrative.area') }}">Area</a>
                        </li>
                        <li class="{{ request()->is('administrative/unit/*') ? 'active' : '' }}">
                            <a href="{{ route('administrative.unit') }}">Unit</a>
                        </li>
                        <li class="{{ request()->is('administrative/sub-unit/*') ? 'active' : '' }}">
                            <a href="{{ route('administrative.sub.unit') }}">Sub Unit</a>
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
