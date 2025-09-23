<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                @if (isset($setting) && $setting->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" width="120">
                @endif
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">
                {{ Auth::check() ? Auth::user()->roles->pluck('name')->first() : 'Construction' }}
            </span>
        </a>


       <!-- 🔹 Collapse Button (Desktop only) -->
        <a href="javascript:void(0);" id="sidebarToggle" class="menu-link text-large ms-auto d-none d-xl-inline-flex">
            <i class="bx bx-menu bx-sm align-middle"></i>
        </a>

        <!-- 🔹 Mobile Toggle Button -->
        <a href="javascript:void(0);" id="mobileSidebarToggle" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>


    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item ">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <!-- Request (All Users) -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Requests</span></li>
        <!-- Requests (All Users) -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Requests">Request</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('requests.index') }}" class="menu-link">
                        <div data-i18n="Request">Request</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Add-Request">Add Request</div>
                    </a>
                </li>
            </ul>
        </li>
        </li>

        <!-- Finance (Admin and Assign Roles) -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Finance</span></li>
        <!-- Budget -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div data-i18n="Budgets">Budgets</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('finance.budgets.index') }}" class="menu-link">
                        <div data-i18n="All-Budgets">Budgets</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('finance.budgets.create') }}" class="menu-link">
                        <div data-i18n="Add-Budgets">Add Budget</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Invoices -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Invoices">Invoices</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('finance.invoices.index') }}" class="menu-link">
                        <div data-i18n="All-Invoices">All Invoices</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('finance.invoices.create') }}" class="menu-link">
                        <div data-i18n="Create-Invoices">Create Invoice</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Payments -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="Payments">Payments</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('finance.payments.index') }}" class="menu-link">
                        <div data-i18n="All-Payments">Payments</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('finance.payments.create') }}" class="menu-link">
                        <div data-i18n="Add-Payments">Add Payment</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Procurements -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div data-i18n="Procurements">Procurements</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('finance.procurements.index') }}" class="menu-link">
                        <div data-i18n="All-Procurements">All Procurements</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('finance.procurements.create') }}" class="menu-link">
                        <div data-i18n="Create-Procurements">Create Procurement</div>
                    </a>
                </li>
            </ul>
        </li>
        </li>

        <!-- Reports (Admin and Assign Roles) -->

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Reports</span></li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Reports">Reports</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('reports.requests') }}" class="menu-link">
                        <div data-i18n="Request-Reports">Request Reports</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('reports.finance') }}" class="menu-link">
                        <div data-i18n="Finance-Reports">Finance Reports</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('reports.procurement') }}" class="menu-link">
                        <div data-i18n="reports.procurement">Procurement Reports</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('reports.workflow') }}" class="menu-link">
                        <div data-i18n="Workflow-Reports">Workflow Reports</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('reports.audit') }}" class="menu-link">
                        <div data-i18n="Audit-Reports">Audit Reports</div>
                    </a>
                </li>
            </ul>
        </li>
        </li>

        <!-- Management (Admin and Assign Roles) -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Management</span></li>
        <!-- User Management (Admin Only) -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Management">User Management</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.user-management') }}" class="menu-link">
                        <div data-i18n="User-Management">Users</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.register') }}" class="menu-link">
                        <div data-i18n="Register">Create User</div>
                    </a>
                </li>

            </ul>
        </li>

        <!-- Roles (Admin Only) -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Roles">Roles</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('roles.show') }}" class="menu-link">
                        <div data-i18n="All-Roles">Roles</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('roles.create') }}" class="menu-link">
                        <div data-i18n="Role-create">Create Role</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Permissions (Admin Only) -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-lock"></i>
                <div data-i18n="Permissions">Permissions</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('permissions.index') }}" class="menu-link">
                        <div data-i18n="Set-Permission">Set Permission</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Services (Admin only) -->
        {{-- <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-briefcase"></i>
                            <div data-i18n="Services">Work Order</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('services.index') }}" class="menu-link">
                                    <div data-i18n="All Services">All Work Order</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('services.create') }}" class="menu-link">

                                    <div data-i18n="Add Services">Add Work Order</div>
                                </a>
                            </li>
                        </ul>
                    </li> --}}

        <!-- Departments (Admin only) -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-building"></i>
                <div data-i18n="Departments">Departments</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('departments.index') }}" class="menu-link">
                        <div data-i18n="All-Departments">Departments</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('departments.create') }}" class="menu-link">
                        <div data-i18n="Add-Department">Add Department</div>
                    </a>
                </li>
            </ul>
        </li>
        </li>

        <!-- Settings (Admin and Assign Roles except Profile-settings) -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Settings</span>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Settings">Settings</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="profile-Settings">Profile Settings</div>
                    </a>
                </li>

                <!-- Admin Only -->
                <li class="menu-item">
                    <a href="{{ route('audit.logs.index') }}" class="menu-link">
                        <div data-i18n="audit-logs">Users Logs</div>
                    </a>
                </li>

            </ul>
        </li>
        </li>
    </ul>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("layout-menu");
        const desktopToggle = document.getElementById("sidebarToggle");
        const mobileToggle = document.getElementById("mobileSidebarToggle");

        // ✅ Desktop collapse toggle
        if (desktopToggle) {
            desktopToggle.addEventListener("click", function() {
                sidebar.classList.toggle("collapsed");

                // Save state in localStorage (desktop only)
                if (sidebar.classList.contains("collapsed")) {
                    localStorage.setItem("sidebar-collapsed", "true");
                } else {
                    localStorage.removeItem("sidebar-collapsed");
                }
            });
        }

        // ✅ Mobile drawer toggle
        if (mobileToggle) {
            mobileToggle.addEventListener("click", function() {
                sidebar.classList.toggle("active"); // "active" ka use mobile overlay ke liye
            });
        }

        // ✅ Restore collapse state on desktop reload
        if (window.innerWidth >= 1200 && localStorage.getItem("sidebar-collapsed")) {
            sidebar.classList.add("collapsed");
        }
    });
</script>