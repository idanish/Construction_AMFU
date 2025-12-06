<!-- Sidebar Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                @if (isset($setting) && $setting->logo)
                <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" width="120">
                @endif
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">
                <!-- {{ Auth::check() ? Auth::user()->roles->pluck('name')->first() : 'Construction' }} -->
                <img src="https://amfu.net/wp-content/uploads/2024/07/cropped-amfu-for-web-new.png" alt="Logo"
                    width="160px">
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 border-top">
        <!-- Dashboard -->
        <li class="mt-2 menu-item @if(Route::is('admin.dashboard')) active @endif">
            <a href="{{ route('admin.dashboard') }}"
                class="menu-link @if(Route::is('admin.dashboard')) text-warning @endif">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        @php
        // Requests
        $isRequestsActive = Route::is('requests.index') || Route::is('requests.create') ||
        Route::is('requests.pending') || Route::is('requests.rejected');

        // Procurements
        $isProcurementsActive = Route::is('finance.procurements.index') || Route::is('finance.procurements.create');

        // Invoices
        $isInvoicesActive = Route::is('finance.invoices.index') || Route::is('finance.invoices.create');

        // Payments
        $isPaymentsActive = Route::is('finance.payments.index') || Route::is('finance.payments.create');

        // Budgets
        $isBudgetsActive = Route::is('finance.budgets.index') || Route::is('finance.budgets.create');

        // Reports
        $isReportsActive = Route::is('reports.requests') || Route::is('reports.finance') ||
        Route::is('reports.procurement') || Route::is('reports.audit');

        // Users Management
        $isUsersActive = Route::is('admin.user-management') || Route::is('admin.register') ||
        Route::is('admin.deleted-users');

        // Roles
        $isRolesActive = Route::is('roles.show') || Route::is('roles.create');

        // Departments
        $isDepartmentsActive = Route::is('departments.index') || Route::is('departments.create');

        // Settings
        $isSettingsActive = Route::is('profile.settings') || Route::is('audit.logs.index') ||
        Route::is('settings.backup&restore');

        @endphp

        @canany(['read-request', 'create-request', 'pending-request', 'reject-request'])
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Requests</span></li>
        <!-- Requests -->
        <li class="menu-item  list-unstyled {{ $isRequestsActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle {{ $isRequestsActive ? 'text-warning' : '' }}">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Requests">Request</div>
            </a>
            <ul class="menu-sub">

                @can('read-request')
                <li class="menu-item {{ Route::is('requests.index') ? 'active' : '' }}">
                    <a href="{{ route('requests.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-files"></i>
                        <div data-i18n="Request">Request</div>
                    </a>
                </li>
                @endcan

                @can('create-request')
                <li class="menu-item {{ Route::is('requests.create') ? 'active' : '' }}">
                    <a href="{{ route('requests.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-plus-circle"></i>
                        <div data-i18n="Add-Request">Add Request</div>
                    </a>
                </li>
                @endcan

                @can('pending-request')
                <li class="menu-item {{ Route::is('requests.pending') ? 'active' : '' }}">
                    <a href="{{ route('requests.pending') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-hourglass-split"></i>
                        <div data-i18n="Add-Request">Pending Request</div>
                    </a>
                </li>
                @endcan

                @can('reject-request')
                <li class="menu-item {{ Route::is('requests.rejected') ? 'active' : '' }}">
                    <a href="{{ route('requests.rejected') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-x-circle"></i>
                        <div data-i18n="Add-Request">Rejected Request</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        </li>
        @endcanany

        @canany(['read-procurement', 'create-procurement','read-invoice', 'create-invoice', 'read-payment',
        'create-payment', 'read-budget', 'create-budget'])
        <!-- Finance (Admin and Assign Roles) -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Finance</span></li>

        @canany(['read-procurement', 'create-procurement'])
        <!-- Procurements -->
        <li class="menu-item  list-unstyled {{ $isProcurementsActive ? 'active open' : '' }}">
            <a href="javascript:void(0);"
                class="menu-link menu-toggle {{ $isProcurementsActive ? 'text-warning' : '' }}">
                <i class="menu-icon tf-icons bi bi-bag me-2"></i>
                <div data-i18n="Procurements">Procurements</div>
            </a>
            <ul class="menu-sub">

                @can('read-procurement')
                <li class="menu-item {{ Route::is('finance.procurements.index') ? 'active' : '' }}">
                    <a href="{{ route('finance.procurements.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-box-seam"></i>
                        <div data-i18n="All-Procurements">Procurements</div>
                    </a>
                </li>
                @endcan

                @can('create-procurement')
                <li class="menu-item {{ Route::is('finance.procurements.create') ? 'active' : '' }}">
                    <a href="{{ route('finance.procurements.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-plus-circle"></i>
                        <div data-i18n="Create-Procurements">Create Procurement</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        @canany(['read-invoice', 'create-invoice'])
        <!-- Invoices -->
        <li class="menu-item  list-unstyled {{ $isInvoicesActive  ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle {{ $isInvoicesActive ? 'text-warning' : '' }}">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Invoices">Invoices</div>
            </a>
            <ul class="menu-sub">
                @can('read-invoice')
                <li class="menu-item {{ Route::is('finance.invoices.index') ? 'active' : '' }}">
                    <a href="{{ route('finance.invoices.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-receipt"></i>
                        <div data-i18n="All-Invoices">Invoices</div>
                    </a>
                </li>
                @endcan

                @can('create-invoice')
                <li class="menu-item {{ Route::is('finance.invoices.create') ? 'active' : '' }}">
                    <a href="{{ route('finance.invoices.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-plus-circle"></i>
                        <div data-i18n="Create-Invoices">Create Invoice</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        @canany(['read-payment', 'create-payment'])
        <!-- Payments -->
        <li class="menu-item list-unstyled {{ $isPaymentsActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle {{ $isPaymentsActive ? 'text-warning' : '' }}">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="Payments">Payments</div>
            </a>
            <ul class="menu-sub">
                @can('read-payment')
                <li class="menu-item {{ Route::is('finance.payments.index') ? 'active' : '' }}">
                    <a href="{{ route('finance.payments.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-credit-card-2-back"></i>
                        <div data-i18n="All-Payments">Payments</div>
                    </a>
                </li>
                @endcan

                @can('create-payment')
                <li class="menu-item {{ Route::is('finance.payments.create') ? 'active' : '' }}">
                    <a href="{{ route('finance.payments.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-plus-circle"></i>
                        <div data-i18n="Add-Payments">Add Payment</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        @canany(['read-budget', 'create-budget'])
        <!-- Budget -->
        <li class="menu-item list-unstyled {{ $isBudgetsActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle {{ $isBudgetsActive ? 'text-warning' : '' }}">
                <i class="menu-icon tf-icons bi bi-cash me-2"></i>
                <div data-i18n="Budgets">Budgets</div>
            </a>
            <ul class="menu-sub">
                @can('read-budget')
                <li class="menu-item {{ Route::is('finance.budgets.index') ? 'active' : '' }}">
                    <a href="{{ route('finance.budgets.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-currency-dollar"></i>
                        <div data-i18n="All-Budgets">Budgets</div>
                    </a>
                </li>
                @endcan

                @can('create-budget')
                <li class="menu-item {{ Route::is('finance.budgets.create') ? 'active' : '' }}">
                    <a href="{{ route('finance.budgets.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-plus-circle"></i>
                        <div data-i18n="Add-Budgets">Add Budget</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany
        </li>
        @endcanany

        <!-- Reports (Admin and Assign Roles) -->
        @canany(['request-reports', 'finance-reports', 'procurement-reports', 'audit-reports'])
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Reports</span></li>
        <li class="menu-item list-unstyled {{ $isReportsActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle {{ $isReportsActive ? 'text-warning' : '' }}">
                <i class="menu-icon tf-icons bi bi-bar-chart-line me-2"></i>
                <div data-i18n="Reports">Reports</div>
            </a>
            <ul class="menu-sub">
                @can('request-reports')
                <li class="menu-item {{ Route::is('reports.requests') ? 'active' : '' }}">
                    <a href="{{ route('reports.requests') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-file-text"></i>
                        <div data-i18n="Request-Reports">Request Reports</div>
                    </a>
                </li>
                @endcan

                @can('finance-reports')
                <li class="menu-item {{ Route::is('reports.finance') ? 'active' : '' }}">
                    <a href="{{ route('reports.finance') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-graph-up"></i>
                        <div data-i18n="Finance-Reports">Finance Reports</div>
                    </a>
                </li>
                @endcan

                @can('procurement-reports')
                <li class="menu-item {{ Route::is('reports.procurement') ? 'active' : '' }}">
                    <a href="{{ route('reports.procurement') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-truck"></i>
                        <div data-i18n="reports.procurement">Procurement Reports</div>
                    </a>
                </li>
                @endcan

                @can('audit-reports')
                <li class="menu-item {{ Route::is('reports.audit') ? 'active' : '' }}">
                    <a href="{{ route('reports.audit') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-shield-check"></i>
                        <div data-i18n="Audit-Reports">Audit Reports</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        </li>
        @endcanany

        @canany(['read-user', 'create-user', 'delete-user', 'read-role', 'create-role', 'read-department',
        'create-department'])
        <!-- Management (Admin and Assign Roles) -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Management</span></li>

        <!-- User Management-->
        @canany(['read-user', 'create-user', 'Log-deleted-user'])
        <li class="menu-item list-unstyled {{ $isUsersActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle {{ $isUsersActive ? 'text-warning' : '' }}">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Management">User Management</div>
            </a>
            <ul class="menu-sub">
                @can('read-user')
                <li class="menu-item {{ Route::is('admin.user-management') ? 'active' : '' }}">
                    <a href="{{ route('admin.user-management') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-people-fill"></i>
                        <div data-i18n="User-Management">Users</div>
                    </a>
                </li>
                @endcan

                @can('create-user')
                <li class="menu-item {{ Route::is('admin.register') ? 'active' : '' }}">
                    <a href="{{ route('admin.register') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-person-add"></i>
                        <div data-i18n="Register">Create User</div>
                    </a>
                </li>
                @endcan

                @can('Log-deleted-user')
                <li class="menu-item  {{ Route::is('admin.deleted-users') ? 'active' : '' }}">
                    <a href="{{ route('admin.deleted-users') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-person-x"></i>
                        <div data-i18n="user-deleted">Delete Users</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        <!-- Roles -->
        @canany(['read-role', 'create-role'])
        <li class="menu-item list-unstyled {{ $isRolesActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle {{ $isRolesActive ? 'text-warning' : '' }}">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Roles">Roles</div>
            </a>
            <ul class="menu-sub">
                @can('read-role')
                <li class="menu-item  {{ Route::is('roles.show') ? 'active' : '' }}">
                    <a href="{{ route('roles.show') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-file-person"></i>
                        <div data-i18n="All-Roles">Roles</div>
                    </a>
                </li>
                @endcan

                @can('create-role')
                <li class="menu-item  {{ Route::is('roles.create') ? 'active' : '' }}">
                    <a href="{{ route('roles.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-person-badge-fill"></i>
                        <div data-i18n="Role-create">Create Role</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        <!-- Departments -->
        @canany(['read-department', 'create-department'])
        <li class="menu-item list-unstyled {{ $isDepartmentsActive ? 'active open' : '' }}">
            <a href="javascript:void(0);"
                class="menu-link menu-toggle {{ $isDepartmentsActive ? 'text-warning' : '' }}">
                <i class="menu-icon tf-icons bx bx-building"></i>
                <div data-i18n="Departments">Departments</div>
            </a>
            <ul class="menu-sub">
                @can('read-department')
                <li class="menu-item {{ Route::is('departments.index') ? 'active' : '' }}">
                    <a href="{{ route('departments.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-building-fill-check"></i>
                        <div data-i18n="All-Departments">Departments</div>
                    </a>
                </li>
                @endcan

                @can('create-department')
                <li class="menu-item {{ Route::is('departments.create') ? 'active' : '' }}">
                    <a href="{{ route('departments.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-building-up"></i>
                        <div data-i18n="Add-Department">Add Department</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany
        </li>
        @endcanany

        <!-- Settings (Admin and Assign Roles except Profile-settings) -->
        @canany(['profile-settings', 'view-activity-log', 'backup'])
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Settings</span>
        <li class="menu-item list-unstyled {{ $isSettingsActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle {{ $isSettingsActive ? 'text-warning' : '' }}">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Settings">Settings</div>
            </a>
            <ul class="menu-sub">
                @can('profile-settings')
                <li class="menu-item {{ Route::is('profile.settings') ? 'active' : '' }}">
                    <a href="{{ route('profile.settings') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-person-circle me-2"></i>
                        <div data-i18n="profile-Settings">Profile Settings</div>
                    </a>
                </li>
                @endcan

                @can('view-activity-log')
                <!-- Admin Only -->
                <li class="menu-item {{ Route::is('audit.logs.index') ? 'active' : '' }}">
                    <a href="{{ route('audit.logs.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-gear-fill me-2"></i>
                        <div data-i18n="audit-logs">Activity Logs</div>
                    </a>
                </li>
                @endcan

                @can('backup')
                <li class="menu-item {{ Route::is('settings.backup&restore') ? 'active' : '' }}">
                    <a href="{{ route('settings.backup&restore') }}" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-cloud-arrow-up me-2"></i>
                        <div data-i18n="backup">Backup</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        </li>
        @endcanany
    </ul>

    <div class="p-1 border-top mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-toggle align-items-center rounded text-danger fw-bold w-100">
                <i class="bi bi-box-arrow-right me-2"></i> <span>Logout</span>
            </button>
        </form>
    </div>




</aside>
<!-- / Sidebar Menu -->