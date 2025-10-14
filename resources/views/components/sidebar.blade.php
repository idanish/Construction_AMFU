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
                  <img src="https://amfu.net/wp-content/uploads/2024/07/cropped-amfu-for-web-new.png" alt="Logo" width="160px">
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item active">
            <a href="{{ route('admin.dashboard') }}" class="menu-link text-warning">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <!-- Request (All Users) -->
        @can('view-page-requests')
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Requests</span></li>
        <!-- Requests (All Users) -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Requests">Request</div>
            </a>
            <ul class="menu-sub">
                @can('read-request')
                <li class="menu-item">
                    <a href="{{ route('requests.index') }}" class="menu-link">
                        <div data-i18n="Request">Request</div>
                    </a>
                </li>
                @endcan

                @can('create-request')
                <li class="menu-item">
                    <a href="{{ route('requests.create') }}" class="menu-link">
                        <div data-i18n="Add-Request">Add Request</div>
                    </a>
                </li>
                @endcan

                @can('pending-request')
                <li class="menu-item">
                    <a href="{{ route('requests.pending') }}" class="menu-link">
                        <div data-i18n="Add-Request">Pending Request</div>
                    </a>
                </li>
                @endcan

                @can('reject-request')
                <li class="menu-item">
                    <a href="{{ route('requests.rejected') }}" class="menu-link">
                        <div data-i18n="Add-Request">Rejected Request</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        </li>
        @endcan

        @can('view-page-finance')
        <!-- Finance (Admin and Assign Roles) -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Finance</span></li>

        @can('view-page-procurements')
        <!-- Procurements -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div data-i18n="Procurements">Procurements</div>
            </a>
            <ul class="menu-sub">

                @can('read-procurement')
                <li class="menu-item">
                    <a href="{{ route('finance.procurements.index') }}" class="menu-link">
                        <div data-i18n="All-Procurements">Procurements</div>
                    </a>
                </li>
                @endcan

                @can('create-procurement')
                <li class="menu-item">
                    <a href="{{ route('finance.procurements.create') }}" class="menu-link">
                        <div data-i18n="Create-Procurements">Create Procurement</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        @can('view-page-invoices')
        <!-- Invoices -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Invoices">Invoices</div>
            </a>
            <ul class="menu-sub">
                @can('read-invoice')
                <li class="menu-item">
                    <a href="{{ route('finance.invoices.index') }}" class="menu-link">
                        <div data-i18n="All-Invoices">Invoices</div>
                    </a>
                </li>
                @endcan

                @can('create-invoice')
                <li class="menu-item">
                    <a href="{{ route('finance.invoices.create') }}" class="menu-link">
                        <div data-i18n="Create-Invoices">Create Invoice</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        @can('view-page-payments')
        <!-- Payments -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="Payments">Payments</div>
            </a>
            <ul class="menu-sub">
                @can('read-payment')
                <li class="menu-item">
                    <a href="{{ route('finance.payments.index') }}" class="menu-link">
                        <div data-i18n="All-Payments">Payments</div>
                    </a>
                </li>
                @endcan

                @can('create-payment')
                <li class="menu-item">
                    <a href="{{ route('finance.payments.create') }}" class="menu-link">
                        <div data-i18n="Add-Payments">Add Payment</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        @can('view-page-budgets')
        <!-- Budget -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div data-i18n="Budgets">Budgets</div>
            </a>
            <ul class="menu-sub">
                @can('read-budget')
                <li class="menu-item">
                    <a href="{{ route('finance.budgets.index') }}" class="menu-link">
                        <div data-i18n="All-Budgets">Budgets</div>
                    </a>
                </li>
                @endcan

                @can('create-budget')
                <li class="menu-item">
                    <a href="{{ route('finance.budgets.create') }}" class="menu-link">
                        <div data-i18n="Add-Budgets">Add Budget</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        </li>
        @endcan

        @can('view-page-reports-section')
        <!-- Reports (Admin and Assign Roles) -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Reports</span></li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Reports">Reports</div>
            </a>
            <ul class="menu-sub">
                @can('request-reports')
                <li class="menu-item">
                    <a href="{{ route('reports.requests') }}" class="menu-link">
                        <div data-i18n="Request-Reports">Request Reports</div>
                    </a>
                </li>
                @endcan

                @can('finance-reports')
                <li class="menu-item">
                    <a href="{{ route('reports.finance') }}" class="menu-link">
                        <div data-i18n="Finance-Reports">Finance Reports</div>
                    </a>
                </li>
                @endcan

                @can('procurement-reports')
                <li class="menu-item">
                    <a href="{{ route('reports.procurement') }}" class="menu-link">
                        <div data-i18n="reports.procurement">Procurement Reports</div>
                    </a>
                </li>
                @endcan

                @can('audit-reports')
                <li class="menu-item">
                    <a href="{{ route('reports.audit') }}" class="menu-link">
                        <div data-i18n="Audit-Reports">Audit Reports</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        </li>
        @endcan

        @can('view-page-management')
        <!-- Management (Admin and Assign Roles) -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Management</span></li>

        <!-- User Management-->
        @can('view-page-user-management')
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Management">User Management</div>
            </a>
            <ul class="menu-sub">
                @can('read-user')
                <li class="menu-item">
                    <a href="{{ route('admin.user-management') }}" class="menu-link">
                        <div data-i18n="User-Management">Users</div>
                    </a>
                </li>
                @endcan

                @can('create-user')
                <li class="menu-item">
                    <a href="{{ route('admin.register') }}" class="menu-link">
                        <div data-i18n="Register">Create User</div>
                    </a>
                </li>
                @endcan

                @role('Admin')
                <li class="menu-item">
                    <a href="{{ route('admin.deleted-users') }}" class="menu-link">
                        <div data-i18n="user-deleted">Delete Users</div>
                    </a>
                </li>
                @endrole
            </ul>
        </li>
        @endcan

        @can('view-page-role')
        <!-- Roles -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Roles">Roles</div>
            </a>
            <ul class="menu-sub">
                @can('read-role')
                <li class="menu-item">
                    <a href="{{ route('roles.show') }}" class="menu-link">
                        <div data-i18n="All-Roles">Roles</div>
                    </a>
                </li>
                @endcan

                @can('create-role')
                <li class="menu-item">
                    <a href="{{ route('roles.create') }}" class="menu-link">
                        <div data-i18n="Role-create">Create Role</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan
        </li>
        @endcan

        @can('view-page-department')
        <!-- Departments (Admin only) -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-building"></i>
                <div data-i18n="Departments">Departments</div>
            </a>
            <ul class="menu-sub">
                @can('read-department')
                <li class="menu-item">
                    <a href="{{ route('departments.index') }}" class="menu-link">
                        <div data-i18n="All-Departments">Departments</div>
                    </a>
                </li>
                @endcan

                @can('create-department')
                <li class="menu-item">
                    <a href="{{ route('departments.create') }}" class="menu-link">
                        <div data-i18n="Add-Department">Add Department</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        </li>
        @endcan

        @can('view-page-settings')
        <!-- Settings (Admin and Assign Roles except Profile-settings) -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Settings</span>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Settings">Settings</div>
            </a>
            <ul class="menu-sub">
                @can('view-page-profile-settings')
                <li class="menu-item">
                    <a href="{{ route('profile.settings') }}" class="menu-link">
                        <div data-i18n="profile-Settings">Profile Settings</div>
                    </a>
                </li>
                @endcan

                @can('view-activity-log')
                <!-- Admin Only -->
                <li class="menu-item">
                    <a href="{{ route('audit.logs.index') }}" class="menu-link">
                        <div data-i18n="audit-logs">Activity Logs</div>
                    </a>
                </li>
                @endcan

                @can('backup')
                <li class="menu-item">
                    <a href="{{ route('settings.backup&restore') }}" class="menu-link">
                        <div data-i18n="backup">Backup</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        </li>
        @endcan
    </ul>

</aside>
<!-- / Sidebar Menu -->