<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
    /* Sidebar ka background aur size set karna */
    :root {
        --sidebar-width: 280px;
        /* Sidebar ki chaurayi */
        --topbar-height: 56px;
        /* Top bar ki oonchai */
        --sidebar-bg-color: #f8f9fa;
        /* Light grey background */
        --topbar-bg-color: #343a40;
        /* Dark background for top bar */
    }

    /* 1. Top Bar Styling */
    #top-navbar {
        background-color: var(--topbar-bg-color);
        height: var(--topbar-height);
        box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        z-index: 1030;
        /* Sidebar se upar rakhein */
    }

    .search-form {
        max-width: 400px;
        width: 100%;
    }

    /* 2. Sidebar Styling (Desktop) - FINAL FIXES */
    .sidebar-desktop {
        width: var(--sidebar-width);
        background-color: var(--sidebar-bg-color);
        height: 100vh;
        /* Poori height */
        position: fixed;
        top: 0;
        left: 0;

        /* Scroll fix: Zaroorat padne par vertical scrollbar dikhega */
        overflow-y: auto;

        /* Top bar ki space chhodne ke liye padding */
        padding-top: var(--topbar-height) !important;

        z-index: 1029;
    }

    /* Menu Button Styling (Bigger size aur more padding/margin) */
    .sidebar-desktop .btn-toggle {
        /* Padding increase kiya */
        padding: .5rem .75rem;
        font-weight: 600;
        /* Font size increase kiya */
        font-size: 1.05rem;
        color: #495057;
        background-color: transparent;
        border: none;
        width: 100%;
        text-align: left;
        display: flex;
        align-items: center;
        /* Margin for line spacing */
        margin-bottom: 0.2rem;
    }

    .sidebar-desktop .btn-toggle:hover,
    .sidebar-desktop .btn-toggle.collapsed:not([aria-expanded="false"]) {
        color: #0d6efd;
        background-color: #e9ecef;
    }

    /* Sub-menu link styling (Bigger size aur more padding/margin) */
    .sidebar-desktop .btn-toggle-nav a {
        /* Padding increase kiya */
        padding: .35rem 1rem;
        font-weight: 500;
        font-size: 1rem;
        color: #6c757d;
        /* Line spacing increase kiya */
        margin-bottom: 0.1rem;
        display: block;
        /* Poori line par click ho sake */
    }

    .sidebar-desktop .btn-toggle-nav a:hover {
        color: #212529;
        background-color: #dee2e6;
    }

    /* Logout button ka style */
    .mt-auto .btn-toggle {
        color: #dc3545 !important;
        font-weight: bold;
    }

    .mt-auto .btn-toggle:hover {
        background-color: #f8d7da;
    }


    /* 3. Main Content Styling */
    .main-content {
        /* Desktop mein sidebar jitni space chhod kar shuru hoga */
        margin-left: var(--sidebar-width);
        padding-top: calc(var(--topbar-height) + 1rem) !important;
        min-height: 100vh;
        background-color: #f4f7f6;
    }


    /* 4. Responsiveness (Mobile View) */
    @media (max-width: 991.98px) {

        /* Mobile par sidebar ki margin hata dein */
        .main-content {
            margin-left: 0;
        }

        /* Top bar se 'Welcome Admin' chhota kar dein */
        .navbar-brand {
            font-size: 1rem;
        }

        /* Offcanvas (Mobile Sidebar) ka style */
        .offcanvas {
            width: 280px;
        }
    }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="top-navbar">
        <div class="container-fluid">
            <button class="btn p-0 me-3 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand text-white" href="#">
                <span class="me-2 fw-bold text-warning fs-5">LOGO</span>
                Welcome Admin
            </a>

            <form class="d-none d-md-flex mx-auto search-form">
                <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
            </form>

            <div class="d-flex align-items-center">
                <button class="btn text-white me-3 position-relative">
                    <i class="bi bi-bell-fill fs-5"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                </button>

                <div class="dropdown">
                    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://via.placeholder.com/32/FFFFFF/000000?text=P" alt="Profile" width="32"
                            height="32" class="rounded-circle border border-2 border-white">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser1">
                        <li><span class="dropdown-item-text fw-bold">User Ka Naam</span></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Profile Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <div class="d-none d-lg-block flex-shrink-0 p-3 sidebar-desktop" id="sidebarDesktop">
            <div class="d-flex flex-column h-100">

                <h6 class="text-uppercase text-muted my-3 px-3">Main Navigation</h6>
                <ul class="list-unstyled ps-0 flex-grow-1 sidebar-menu">
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                            data-bs-target="#request-collapse" aria-expanded="false">
                            <i class="bi bi-file-earmark-text me-2"></i> Request
                        </button>
                        <div class="collapse ms-3" id="request-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="link-dark rounded">Request</a></li>
                                <li><a href="#" class="link-dark rounded">Add Request</a></li>
                                <li><a href="#" class="link-dark rounded">Pending Request</a></li>
                                <li><a href="#" class="link-dark rounded">Rejected Request</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="border-top my-3"></li>

                    <h6 class="text-uppercase text-muted my-3 px-3">Finance</h6>
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                            data-bs-target="#budget-collapse" aria-expanded="false">
                            <i class="bi bi-cash me-2"></i> Budgets
                        </button>
                        <div class="collapse ms-3" id="budget-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="link-dark rounded">Budget</a></li>
                                <li><a href="#" class="link-dark rounded">Add Budget</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                            data-bs-target="#proc-collapse" aria-expanded="false">
                            <i class="bi bi-bag me-2"></i> Procurements
                        </button>
                        <div class="collapse ms-3" id="proc-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="link-dark rounded">Procurements</a></li>
                                <li><a href="#" class="link-dark rounded">Add Procurements</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                            data-bs-target="#invoice-collapse" aria-expanded="false">
                            <i class="bi bi-receipt me-2"></i> Invoices
                        </button>
                        <div class="collapse ms-3" id="invoice-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="link-dark rounded">Invoices</a></li>
                                <li><a href="#" class="link-dark rounded">Add Invoices</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                            data-bs-target="#payment-collapse" aria-expanded="false">
                            <i class="bi bi-credit-card me-2"></i> Payments
                        </button>
                        <div class="collapse ms-3" id="payment-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="link-dark rounded">Payments</a></li>
                                <li><a href="#" class="link-dark rounded">Add Payments</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="border-top my-3"></li>

                    <h6 class="text-uppercase text-muted my-3 px-3">Reports</h6>
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                            data-bs-target="#reports-collapse" aria-expanded="false">
                            <i class="bi bi-bar-chart-line me-2"></i> Reports
                        </button>
                        <div class="collapse ms-3" id="reports-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="link-dark rounded">Request Report</a></li>
                                <li><a href="#" class="link-dark rounded">Finance Report</a></li>
                                <li><a href="#" class="link-dark rounded">Procurement Report</a></li>
                                <li><a href="#" class="link-dark rounded">Audit Report</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="border-top my-3"></li>

                    <h6 class="text-uppercase text-muted my-3 px-3">Management</h6>
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                            data-bs-target="#user-mgmt-collapse" aria-expanded="false">
                            <i class="bi bi-person-gear me-2"></i> User Management
                        </button>
                        <div class="collapse ms-3" id="user-mgmt-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="link-dark rounded">Users</a></li>
                                <li><a href="#" class="link-dark rounded">Add User</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                            data-bs-target="#roles-collapse" aria-expanded="false">
                            <i class="bi bi-person-badge me-2"></i> Roles
                        </button>
                        <div class="collapse ms-3" id="roles-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="link-dark rounded">Roles</a></li>
                                <li><a href="#" class="link-dark rounded">Add Role</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="mb-1">
                        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse"
                            data-bs-target="#dept-collapse" aria-expanded="false">
                            <i class="bi bi-building me-2"></i> Departments
                        </button>
                        <div class="collapse ms-3" id="dept-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="link-dark rounded">Departments</a></li>
                                <li><a href="#" class="link-dark rounded">Add Departments</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="border-top my-3"></li>

                    <h6 class="text-uppercase text-muted my-3 px-3">Settings</h6>
                    <li class="mb-1"><a href="#" class="btn btn-toggle align-items-center rounded"><i
                                class="bi bi-gear-fill me-2"></i> Settings</a></li>
                    <li class="mb-1"><a href="#" class="btn btn-toggle align-items-center rounded"><i
                                class="bi bi-person-circle me-2"></i> Profile Settings</a></li>
                    <li class="mb-1"><a href="#" class="btn btn-toggle align-items-center rounded"><i
                                class="bi bi-cloud-arrow-up me-2"></i> Backup</a></li>

                </ul>

                <div class="mt-auto pt-3 border-top">
                    <a href="#" class="btn btn-toggle align-items-center rounded text-danger fw-bold w-100"><i
                            class="bi bi-box-arrow-right me-2"></i> Logout</a>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="sidebarOffcanvas"
            aria-labelledby="sidebarOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title fw-bold" id="sidebarOffcanvasLabel">Dashboard Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <p class="text-muted">Mobile menu content yahan aayega.</p>
            </div>
        </div>

        <main class="flex-grow-1 p-3 main-content">
            <h1 class="mt-5 pt-3">Dashboard Overview</h1>
            <p>Yeh area Top Bar aur Sidebar ke mutabiq adjust ho chuka hai.</p>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="p-4 bg-white rounded shadow-sm">
                        <h3>Main Metrics</h3>
                        <p>Yahan data cards aayenge.</p>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="p-4 bg-white rounded shadow-sm">
                        <h3>Latest Activity</h3>
                        <p>Yahan notifications ya recent activity list aayegi.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html> -->

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Dashboard - Finance Module</title>
    <meta name="description" content="A single file dashboard template based on user's Laravel structure." />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* ==================== */
        /* Custom Variables */
        /* ==================== */
        :root {
            --sidebar-width: 260px; /* Thora sa bara width */
            --topbar-height: 60px;
            --sidebar-bg-color: #ffffff; /* Template ki tarah white/light */
            --topbar-bg-color: #f7f7f7;
            --menu-link-padding-y: 10px; /* Line spacing increase kiya */
            --menu-link-font-size: 0.95rem; /* Font size thora bara kiya */
        }
        
        /* Layout Structure */
        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ==================== */
        /* Sidebar (layout-menu) Styling - Scrolling & Spacing Fixes */
        /* ==================== */
        .layout-menu {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg-color);
            z-index: 1050;
            /* Scrolling fix */
            overflow-y: auto; 
            padding-bottom: 20px; /* Bottom padding for better look */
        }

        /* App Brand Link (Logo Area) */
        .app-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid #eee;
        }
        .app-brand-text {
            font-size: 1.15rem;
            color: #696cff; /* Template Blue Colour */
        }
        .app-brand img {
            max-width: 40px;
            height: auto;
        }

        /* Main Menu UL */
        .menu-inner {
            height: calc(100% - var(--topbar-height) - 10px); /* Adjust height for content */
            display: flex; /* For sticky elements like Logout */
            flex-direction: column;
            padding: 0 0.5rem !important; /* Menu padding */
        }
        
        /* Menu Header (Section Title) */
        .menu-header {
            padding: 1.25rem 1.5rem 0.5rem 1.5rem;
        }

        /* Menu Item (li) Styling */
        .menu-item {
            margin-bottom: 5px; /* Line Spacing increase kiya */
        }

        /* Menu Link (a) Styling - Size/Padding Fix */
        .menu-link {
            display: flex;
            align-items: center;
            padding: var(--menu-link-padding-y) 1.5rem;
            color: #697a8d;
            font-size: var(--menu-link-font-size); 
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
        }

        .menu-link:hover {
            color: #000;
            background-color: rgba(105, 108, 255, 0.06); /* Light hover effect */
        }
        .menu-item.active > .menu-link,
        .menu-item.active > .menu-link:hover {
            background-color: #696cff !important; 
            color: #fff !important;
            box-shadow: 0 0.125rem 0.25rem 0 rgba(105, 108, 255, 0.4);
        }

        /* Sub Menu Styling */
        .menu-sub {
            padding-left: 1.5rem;
            list-style: none;
        }
        .menu-sub .menu-link {
            padding-top: 7px;
            padding-bottom: 7px;
            font-size: 0.9rem;
        }

        /* Logout Fix: Logout ko hamesha bottom par rakhne ke liye */
        /* Kyunki yeh poore ul ke andar hai, hum isay ul ke bilkul end mein rakhenge */
        .menu-inner > .menu-item:last-child {
            margin-top: auto; /* This pushes it to the bottom */
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .menu-inner > .menu-item:last-child .menu-link {
            color: #ff3e1d; /* Danger color for logout */
        }
        
        /* Icons */
        .menu-icon {
            font-size: 1.3rem; /* Icons ka size bara kiya */
            margin-right: 12px;
        }


        /* ==================== */
        /* Main Content (layout-page) Styling */
        /* ==================== */
        .layout-page {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: all 0.2s;
        }

        /* Navbar Styling */
        #layout-navbar {
            height: var(--topbar-height);
            background-color: var(--topbar-bg-color);
            position: sticky;
            top: 0;
            z-index: 1040;
            padding: 0 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(105, 108, 255, 0.1);
        }
        
        .layout-navbar .d-flex.flex-column {
            line-height: 1.2;
        }
        
        /* Main Content Area */
        .container {
            padding-top: 1.5rem;
            padding-bottom: 3rem;
        }

        /* Responsiveness */
        @media (max-width: 1200px) { /* xl breakpoint for template */
            .layout-menu {
                /* Sidebar hidden on mobile/tablet, only appears via offcanvas toggle */
                transform: translateX(-100%); 
                transition: transform 0.3s;
            }
            .layout-page {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body class="light-style layout-menu-fixed">

    <div class="layout-wrapper layout-content-navbar">
        
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="#" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <i class="bi bi-box-fill fs-3 text-primary"></i> 
                    </span>
                    <span class="app-brand-text demo menu-text fw-bolder ms-2">
                        Construction 
                    </span>
                </a>
                <a href="javascript:void(0);"
                    class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <li class="menu-item active">
                    <a href="#" class="menu-link">
                        <i class="menu-icon tf-icons bi bi-house-door-fill"></i>
                        <div data-i18n="Analytics">Dashboard</div>
                    </a>
                </li>

                <li class="menu-header small text-uppercase"><span class="menu-header-text">Requests</span></li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bi bi-receipt"></i>
                        <div data-i18n="Requests">Request</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Request">Request</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Add-Request">Add Request</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Pending-Request">Pending Request</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Rejected-Request">Rejected Request</div></a></li>
                    </ul>
                </li>
                
                <li class="menu-header small text-uppercase"><span class="menu-header-text">Finance</span></li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bi bi-bag"></i>
                        <div data-i18n="Procurements">Procurements</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="All-Procurements">Procurements</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Create-Procurements">Create Procurement</div></a></li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bi bi-file-earmark-text"></i>
                        <div data-i18n="Invoices">Invoices</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="All-Invoices">Invoices</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Create-Invoices">Create Invoice</div></a></li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bi bi-credit-card"></i>
                        <div data-i18n="Payments">Payments</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="All-Payments">Payments</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Add-Payments">Add Payment</div></a></li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bi bi-graph-up"></i>
                        <div data-i18n="Budgets">Budgets</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="All-Budgets">Budgets</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Add-Budgets">Add Budget</div></a></li>
                    </ul>
                </li>

                <li class="menu-header small text-uppercase"><span class="menu-header-text">Reports</span></li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bi bi-file-earmark-bar-graph"></i>
                        <div data-i18n="Reports">Reports</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Request-Reports">Request Reports</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Finance-Reports">Finance Reports</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Procurement-Reports">Procurement Reports</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Audit-Reports">Audit Reports</div></a></li>
                    </ul>
                </li>

                <li class="menu-header small text-uppercase"><span class="menu-header-text">Management</span></li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bi bi-people"></i>
                        <div data-i18n="User-Management">User Management</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Users">Users</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Create-User">Create User</div></a></li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bi bi-person-badge"></i>
                        <div data-i18n="Roles">Roles</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="All-Roles">Roles</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Role-create">Create Role</div></a></li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bi bi-building"></i>
                        <div data-i18n="Departments">Departments</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="All-Departments">Departments</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="Add-Department">Add Department</div></a></li>
                    </ul>
                </li>

                <li class="menu-header small text-uppercase"><span class="menu-header-text">Settings</span></li>
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bi bi-gear"></i>
                        <div data-i18n="Settings">Settings</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="profile-Settings">Profile Settings</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="audit-logs">Activity Logs</div></a></li>
                        <li class="menu-item"><a href="#" class="menu-link"><div data-i18n="backup">Backup</div></a></li>
                    </ul>
                </li>
                
                <li class="menu-item">
                    <form method="POST" action="#">
                        <button type="submit" class="menu-link w-100">
                            <i class="menu-icon tf-icons bi bi-box-arrow-right"></i>
                            <div class="text-danger fw-bold">Log Out</div>
                        </button>
                    </form>
                </li>
            </ul>

        </aside>
        <div class="layout-page">
            
            <nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                id="layout-navbar">
                <div class="d-flex align-items-center w-100 justify-content-between">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bi bi-list bx-sm"></i>
                        </a>
                    </div>
                    
                    <div class="d-flex flex-column">
                        <h6 class="mb-0">
                            Welcome, <span class="fw-bold">User Name</span>
                        </h6>
                        <small class="text-muted">
                            Admin / Finance / Manager
                        </small>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="me-3 d-none d-md-block">
                            <div class="d-flex align-items-center">
                                <form action="#" method="GET" class="input-group">
                                    <input type="text" name="query" placeholder="Search..."
                                        class="form-control form-control-sm border-0 shadow-none"
                                        aria-label="Search...">
                                    <button type="submit" class="btn btn-light border-0 shadow-none">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="nav-item dropdown me-3">
                            <button class="btn btn-sm position-relative" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                                <li class="px-3 py-2">No notifications data shown in static file.</li>
                            </ul>
                        </div>


                        <div class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-online">
                                        <img src="https://via.placeholder.com/40/0000FF/FFFFFF?text=P"
                                            alt="User Avatar" class="w-px-40 h-auto rounded-circle" width="40" height="40" />
                                    </div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i><span class="fw-bold">User Name</span></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i><span class="align-middle">Profile Settings</span></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="#">
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4">
                        <span class="text-muted fw-light">Dashboard /</span> Main Content
                    </h4>

                    <div class="row">
                        <div class="col-lg-12 mb-4 order-0">
                            <div class="card">
                                <div class="d-flex align-items-end row">
                                    <div class="col-sm-12 p-4">
                                        <h2>Main Page Content Yahan Aayega</h2>
                                        <p>Yeh woh area hai jahan aapka data tables aur forms display honge. Scrolling aur menu spacing ab behtar honi chahiye.</p>
                                        <div style="height: 1000px; background-color: #f0f0f0; padding: 20px;">
                                            <p>Scrollable space example.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">
                            © 2024, made with ❤️ by [Your Name]
                        </div>
                    </div>
                </footer>
                </div>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('layout-menu');
            const toggleButtons = document.querySelectorAll('.layout-menu-toggle');
            
            toggleButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Simple toggle: in a real template, this would use a complex JS library
                    const isHidden = sidebar.style.transform === 'translateX(-100%)' || !sidebar.style.transform;
                    sidebar.style.transform = isHidden ? 'translateX(0)' : 'translateX(-100%)';
                });
            });
            
            // To close the sidebar when clicking outside on mobile (simple version)
            document.querySelector('.layout-page').addEventListener('click', (e) => {
                 if (window.innerWidth < 1200 && sidebar.style.transform === 'translateX(0)') {
                     // Check if click is outside the sidebar area
                     if (!sidebar.contains(e.target) && !Array.from(toggleButtons).some(btn => btn.contains(e.target))) {
                         sidebar.style.transform = 'translateX(-100%)';
                     }
                 }
            });
        });
    </script>
</body>
</html>