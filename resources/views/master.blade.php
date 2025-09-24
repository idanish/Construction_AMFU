<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', ' - Finance Module')</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"
        integrity="sha512-fZzZkYxjQy9B7M1gYiXlv2gn6w+fV50Vf3+Yv4PDe8UnD1iXxNhYExfL9M5Kj82Y6cH1wQn1yhv7cA5CjGg7FA=="
        crossorigin="anonymous" />


    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />

    <!-- SweetAlert2 CSS + JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Icons (Bootstrap Icons CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Root Variables */
        :root {
            --btn-radius: 30px;
            --btn-padding: 10px 20px;
            --btn-font-weight: 600;
            --btn-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            --btn-hover-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            --btn-scale: 1.05;
        }

        /* VIP Buttons Base Style */
        .vip-btn {
            transition: all 0.3s ease;
            border-radius: var(--btn-radius);
            padding: var(--btn-padding);
            font-weight: var(--btn-font-weight);
            box-shadow: var(--btn-shadow);
        }

        .vip-btn:hover {
            transform: scale(var(--btn-scale));
            box-shadow: var(--btn-hover-shadow);
        }

        a.vip-btn,
        button.vip-btn {
            transition: all 0.3s ease;
            border-radius: var(--btn-radius);
            padding: var(--btn-padding);
            font-weight: var(--btn-font-weight);
            box-shadow: var(--btn-shadow);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;

        }

        a.vip-btn:hover,
        button.vip-btn:hover {
            transform: scale(var(--btn-scale));
            box-shadow: var(--btn-hover-shadow);
        }

        /* VIP Hover Glow Effect */
        .vip-btn {
            transition: all 0.3s ease;
            border-radius: 30px;
            padding: 10px 20px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border: none;
            cursor: pointer;
        }

        .vip-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        /* Submit Button Color */
        .btn-submit {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: #fff;
        }

        .btn-download {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            /* Dark Blue Gradient */
            color: #fff;
        }

        .btn-filter {
            background: linear-gradient(135deg, #f7971e, #ffd200);
            /* Orange-Yellow Gradient */
            color: #000;
            /* Text dark for contrast */
        }

        .btn-excel {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            /* Green Gradient */
            color: #fff;
        }

        .btn-pdf {
            background: linear-gradient(135deg, #eb3349, #f45c43);
            /* Red Gradient */
            color: #fff;
        }
    </style>


</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

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
                            {{ Auth::check() ? Auth::user()->roles->pluck('name')->first() : 'Construction' }}
                        </span>
                    </a>
                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item active">
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

                                <a href="{{ route('requests.create') }}" class="menu-link">
                                    <div data-i18n="Add-Request">Add Request</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('requests.pending') }}" class="menu-link">
                                    <div data-i18n="Add-Request">Pending Request</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('requests.rejected') }}" class="menu-link">
                                    <div data-i18n="Add-Request">Rejected Request</div>
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
                                    <div data-i18n="All-Invoices">Invoices</div>
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
                                    <div data-i18n="All-Procurements">Procurements</div>
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
                                <a href="{{ route('profile.settings') }}" class="menu-link">
                                    <div data-i18n="profile-Settings">Profile Settings</div>
                                </a>
                            </li>

                            <!-- Admin Only -->
                            <li class="menu-item">
                                <a href="{{ route('audit.logs.index') }}" class="menu-link">
                                    <div data-i18n="audit-logs">Activity Logs</div>
                                </a>
                            </li>
                            {{-- @can('admin') --}}
                            <li class="menu-item">
                                <a href="{{ route('settings.backup&restore') }}" class="menu-link">
                                    <div data-i18n="backup">Backup</div>
                                </a>
                            </li>
                            {{-- @endcan --}}

                        </ul>
                    </li>
                    </li>
                </ul>
            </aside>



            <!-- / Sidebar Menu -->
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="d-flex align-items-center w-100 justify-content-between">
                        <!-- Left Side: Welcome -->
                        <div class="d-flex flex-column">
                            <h6 class="mb-0">
                                Welcome, <span class="fw-bold">{{ Auth::user()->name ?? 'Guest' }}</span>
                            </h6>
                            <small class="text-muted">
                                {{ Auth::user()->roles->pluck('name')->implode(', ') ?? 'No Role' }}
                            </small>
                        </div>

                        <!-- Right Side: Search + Profile -->
                        <div class="d-flex align-items-center">
                            <!-- Search bar -->
                            <div class="me-3">
                                <div class="d-flex align-items-center">
                                    <form action="{{ route('search.results') }}" method="GET" class="input-group">
                                        <input type="text" name="query" placeholder="Search..."
                                            class="form-control form-control-sm border-0 shadow-none"
                                            aria-label="Search...">
                                        <button type="submit" class="btn btn-light border-0 shadow-none">
                                            <i class="bx bx-search"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Notification Dropdown --}}
                            <div class="nav-item dropdown me-3">
                                @php
                                    $user = auth()->user();
                                    $role = $user->roles->pluck('name')->first();

                                    $notifications = \App\Models\Notification::where(function ($q) use ($user, $role) {
                                        $q->where('user_id', $user->id)->orWhere(function ($q2) use ($role) {
                                            $q2->whereNotNull('role')->where('role', $role);
                                        });
                                    })
                                        ->latest()
                                        ->take(5)
                                        ->get();

                                    $unreadCount = \App\Models\Notification::where(function ($q) use ($user, $role) {
                                        $q->where('user_id', $user->id)->orWhere(function ($q2) use ($role) {
                                            $q2->whereNotNull('role')->where('role', $role);
                                        });
                                    })
                                        ->where('is_read', false)
                                        ->count();
                                @endphp

                                <button class="btn btn-sm position-relative" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-bell fs-4"></i>
                                    @if ($unreadCount > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                                    @forelse($notifications as $notification)
                                        <li
                                            class="px-3 py-2 border-bottom @if (!$notification->is_read) bg-light @endif">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <div>{{ $notification->message }}</div>
                                                    <small
                                                        class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('notifications.read', $notification->id) }}">
                                                    @csrf
                                                    <button class="btn btn-link btn-sm p-0">Read</button>
                                                </form>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="px-3 py-2">No notifications</li>
                                    @endforelse
                                    <li><a class="dropdown-item text-center"
                                            href="{{ route('notifications.index') }}">Show All</a></li>
                                </ul>
                            </div>




                            <!-- User Dropdown -->
                            <div class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-online">
                                            <img src="{{ Auth::user()->profile_picture
                                                ? asset('storage/' . Auth::user()->profile_picture)
                                                : asset('assets/img/avatars/1.png') }}"
                                                alt="User Avatar" class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">


                                    <!-- Name & Role -->
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="fw-bold">{{ Auth::user()->name ?? 'Guest' }}</span>
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>


                                    <!-- Profile Settings -->
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Profile Settings</span>
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <!-- Logout -->
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Log Out</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>



                <!-- / Navbar -->

                <!-- Main Content -->
                <div class="container mt-4">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif



                    <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
                        <div class="card">
                            <div class="row row-bordered g-0 my-4">
                                <div class="col-md-12 px-5">


                                    @yield('content')

                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">

                        </div>
                    </footer>
                </div>
                <!-- / Layout container -->

                <!-- Overlay -->
                <div class="layout-overlay layout-menu-toggle"></div>
            </div>
        </div>

        <!-- Core JS -->
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

        <!-- Vendors JS -->
        <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

        <!-- Main JS -->
        <script src="{{ asset('assets/js/main.js') }}"></script>

        <!-- Dropzone JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"
            integrity="sha512-SnHkO9cP47yt0J6fH9o7hF3V7jLzRRYxChh9z7nTS+7A17R37Cy6x8G4fXajNwT6SRWKmFFDKRP8+bdj9dErYw=="
            crossorigin="anonymous"></script>

        {{-- J Queery CDN --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        {{-- DataTables JS --}}
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // let table = new DataTable('#myTable');

            document.addEventListener('DOMContentLoaded', function() {
                @if (session()->has('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif

                @if (session()->has('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{ session('error') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif
            });
            Dropzone.autoDiscover = false;

            let attachmentDropzone = new Dropzone("#attachmentDropzone", {
                url: "{{ route('finance.invoices.store') }}", // Laravel store route
                paramName: "attachment", // input name
                maxFiles: 1, // ek hi file
                acceptedFiles: ".pdf,.jpg,.jpeg,.png",
                addRemoveLinks: true,
                autoProcessQueue: false, // form submit hone par hi process hoga
            });

            // Jab form submit ho
            document.getElementById("invoiceForm").addEventListener("submit", function(e) {
                e.preventDefault();

                if (attachmentDropzone.getQueuedFiles().length > 0) {
                    attachmentDropzone.processQueue(); // pehle file upload kar
                } else {
                    this.submit(); // agar file nahi to seedha form submit kar
                }
            });

            // Jab file successfully upload ho jaye
            attachmentDropzone.on("success", function(file, response) {
                document.getElementById("invoiceForm").submit(); // ab form submit kar
            });
        </script>
</body>

</html>
