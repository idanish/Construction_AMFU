<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integrated Finance Dashboard (Mobile Toggle Fix)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* ---------------------------------------------------- */
/* Custom CSS and Variables (UNCHANGED from previous fix) */
/* ---------------------------------------------------- */
:root {
    --primary-color: #ffab00;
    --sidebar-width: 280px; 
    --sidebar-collapsed-width: 70px; 
    --content-header-height: 75px; 
    --sidebar-bg-color: #f8f9fa; 
}

/* 1. Sidebar Styling */
.sidebar-desktop {
    width: var(--sidebar-width);
    background-color: var(--sidebar-bg-color);
    height: 100vh;
    position: fixed;
    top: 0; 
    left: 0;
    overflow-y: auto; 
    overflow-x: hidden; 
    padding: 0 !important; 
    z-index: 1030; 
    transition: width 0.3s ease-in-out; 
    border-right: 1px solid #dee2e6;
}

/* Sidebar Logo/Header area */
.sidebar-header {
    height: var(--content-header-height); 
    display: flex;
    align-items: center;
    justify-content: start; 
    padding: 0 1rem;
    border-bottom: 1px solid #dee2e6;
    background-color: #fff; 
}

/* Sidebar Menu Items */
.sidebar-desktop .p-3 {
    padding: 1rem !important;
}

.sidebar-desktop .btn-toggle {
    padding: .5rem .75rem; 
    font-weight: 600;
    font-size: 1.05rem; 
    color: #495057;
    border: none;
    width: 100%;
    text-align: left;
    display: flex;
    align-items: center;
    position: relative; 
    margin-bottom: 0.2rem; 
}
.sidebar-desktop .btn-toggle:hover,
.sidebar-desktop .btn-toggle.active { 
    color: var(--primary-color);
    background-color: #e9ecef;
}
.sidebar-desktop .btn-toggle::after {
    content: "\F282"; 
    font-family: "bootstrap-icons";
    font-size: 0.8rem;
    margin-left: auto;
    transition: transform 0.3s ease-in-out;
}
.sidebar-desktop .btn-toggle[aria-expanded="true"]::after {
    transform: rotate(180deg);
}

/* Sub-menu styling */
.sidebar-desktop .btn-toggle-nav a {
    padding: .35rem 1rem; 
    padding-left: 3.25rem; 
    font-weight: 500;
    font-size: 1rem;
    color: #6c757d;
    display: flex; 
    align-items: center;
}
.sidebar-desktop .btn-toggle-nav a i {
    font-size: 0.9rem; 
    margin-right: 0.5rem;
    width: 1rem; 
    text-align: center;
}
.sidebar-desktop .btn-toggle-nav a:hover,
.sidebar-desktop .btn-toggle-nav a.active {
    color: #212529;
    background-color: #dee2e6;
}

/* 2. Main Content Area */
.main-content {
    margin-left: var(--sidebar-width); 
    padding: 0 !important; 
    min-height: 100vh;
    background-color: #f4f7f6;
    transition: margin-left 0.3s ease-in-out; 
}

/* 3. Content Header (Right Side Top Bar) */
.content-header {
    height: var(--content-header-height);
    background-color: #fff;
    border-bottom: 1px solid #dee2e6;
    padding: 0 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky; 
    top: 0;
    z-index: 1000;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05); 
}

.content-body {
    padding: 1.5rem; 
}

/* 4. Sidebar Collapse State */
.sidebar-desktop.collapsed {
    width: var(--sidebar-collapsed-width); 
}
.sidebar-desktop.collapsed .sidebar-header .text-truncate {
    display: none; 
}
.sidebar-desktop.collapsed .sidebar-header {
    justify-content: center; 
}
.sidebar-desktop.collapsed h6 {
    display: none !important;
}
.sidebar-desktop.collapsed .btn-toggle {
    text-align: center;
    justify-content: center;
    padding: 0.75rem 0.5rem;
}
.sidebar-desktop.collapsed .btn-toggle::after {
    display: none; 
}
.sidebar-desktop.collapsed .btn-toggle {
    font-size: 0; 
    white-space: nowrap; 
    overflow: hidden;
    text-overflow: ellipsis;
}
.sidebar-desktop.collapsed .btn-toggle i {
    font-size: 1.25rem;
    margin-right: 0 !important;
    display: block !important; 
    line-height: 1; 
    width: auto !important; 
}
.sidebar-desktop.collapsed .collapse {
    visibility: hidden; 
    height: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
}
.main-content.sidebar-collapsed {
    margin-left: var(--sidebar-collapsed-width);
}


/* 5. Responsiveness (Mobile View) */
@media (max-width: 991.98px) {
    .main-content {
        margin-left: 0;
        padding-top: var(--content-header-height) !important; 
    }
    .content-header {
        position: fixed; 
        width: 100%;
        padding: 0 1rem; /* Mobile mein thodi kam padding */
    }
    .content-body {
        padding-top: 1.5rem; 
    }
    .search-form {
        display: none !important;
    }
    /* Desktop toggle button ko chhupa diya */
    #sidebarToggle {
        display: none !important;
    }
}
</style>

</head>
<body>
    
    <div class="d-flex">
        <div class="d-none d-lg-block flex-shrink-0 sidebar-desktop" id="sidebarDesktop">
            <div class="d-flex flex-column h-100"> 
                
                <div class="sidebar-header">
                    <h5 class="my-0">
                        <span class="fw-bold fs-4" style="color: var(--primary-color);">LOGO</span>
                        <span class="text-truncate fw-normal fs-5 ms-2">Admin Panel</span>
                    </h5>
                </div>

                <div class="p-3 flex-grow-1">
                    <a href="#" class="btn btn-toggle align-items-center rounded active"><i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span></a>
                    

                    <h6 class="text-uppercase text-muted my-3 px-3">Main Navigation</h6>
                    <ul class="list-unstyled ps-0 sidebar-menu">
                        <li class="mb-1">
                            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#request-collapse" aria-expanded="false">
                                <i class="bi bi-file-earmark-text me-2"></i> <span>Request</span>
                            </button>
                            <div class="collapse ms-3" id="request-collapse" data-bs-parent="#sidebarDesktop .sidebar-menu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-files"></i> All Request</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-plus-circle"></i> Add Request</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-hourglass-split"></i> Pending Request</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-x-circle"></i> Rejected Request</a></li>
                                </ul>
                            </div>
                        </li>


                        
                        <li class="border-top my-3"></li>

                        <h6 class="text-uppercase text-muted my-3 px-3">Finance</h6>
                        <li class="mb-1">
                            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#budget-collapse" aria-expanded="false">
                                <i class="bi bi-cash me-2"></i> <span>Budgets</span>
                            </button>
                            <div class="collapse ms-3" id="budget-collapse" data-bs-parent="#sidebarDesktop .sidebar-menu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-currency-dollar"></i> Budget Overview</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-plus-circle"></i> Add Budget</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="mb-1">
                            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#proc-collapse" aria-expanded="false">
                                <i class="bi bi-bag me-2"></i> <span>Procurements</span>
                            </button>
                            <div class="collapse ms-3" id="proc-collapse" data-bs-parent="#sidebarDesktop .sidebar-menu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-box-seam"></i> Procurements List</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-plus-circle"></i> Add Procurements</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="mb-1">
                            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#invoice-collapse" aria-expanded="false">
                                <i class="bi bi-receipt me-2"></i> <span>Invoices</span>
                            </button>
                            <div class="collapse ms-3" id="invoice-collapse" data-bs-parent="#sidebarDesktop .sidebar-menu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-receipt"></i> Invoices List</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-plus-circle"></i> Add Invoices</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="mb-1">
                            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#payment-collapse" aria-expanded="false">
                                <i class="bi bi-credit-card me-2"></i> <span>Payments</span>
                            </button>
                            <div class="collapse ms-3" id="payment-collapse" data-bs-parent="#sidebarDesktop .sidebar-menu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-credit-card-2-back"></i> Payments List</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-plus-circle"></i> Add Payments</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="border-top my-3"></li>

                        <h6 class="text-uppercase text-muted my-3 px-3">Reports</h6>
                        <li class="mb-1">
                            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#reports-collapse" aria-expanded="false">
                                <i class="bi bi-bar-chart-line me-2"></i> <span>Reports</span>
                            </button>
                            <div class="collapse ms-3" id="reports-collapse" data-bs-parent="#sidebarDesktop .sidebar-menu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-file-text"></i> Request Report</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-graph-up"></i> Finance Report</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-truck"></i> Procurement Report</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-shield-check"></i> Audit Report</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="border-top my-3"></li>

                        <h6 class="text-uppercase text-muted my-3 px-3">Management</h6>
                        <li class="mb-1">
                            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#user-mgmt-collapse" aria-expanded="false">
                                <i class="bi bi-person-gear me-2"></i> <span>User Management</span>
                            </button>
                            <div class="collapse ms-3" id="user-mgmt-collapse" data-bs-parent="#sidebarDesktop .sidebar-menu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-people-fill"></i> Users List</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-person-add"></i> Add User</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="mb-1">
                            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#roles-collapse" aria-expanded="false">
                                <i class="bi bi-person-badge me-2"></i> <span>Roles</span>
                            </button>
                            <div class="collapse ms-3" id="roles-collapse" data-bs-parent="#sidebarDesktop .sidebar-menu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-person-badge-fill"></i> Roles List</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-file-person"></i> Add Role</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="mb-1">
                            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#dept-collapse" aria-expanded="false">
                                <i class="bi bi-building me-2"></i> <span>Departments</span>
                            </button>
                            <div class="collapse ms-3" id="dept-collapse" data-bs-parent="#sidebarDesktop .sidebar-menu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-building-fill-check"></i> Departments List</a></li>
                                    <li><a href="#" class="link-dark rounded"><i class="bi bi-building-up"></i> Add Departments</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="border-top my-3"></li>

                        <h6 class="text-uppercase text-muted my-3 px-3">Settings</h6>
                        <li class="mb-1"><a href="#" class="btn btn-toggle align-items-center rounded"><i class="bi bi-gear-fill me-2"></i> <span>Settings</span></a></li>
                        <li class="mb-1"><a href="#" class="btn btn-toggle align-items-center rounded"><i class="bi bi-person-circle me-2"></i> <span>Profile Settings</span></a></li>
                        <li class="mb-1"><a href="#" class="btn btn-toggle align-items-center rounded"><i class="bi bi-cloud-arrow-up me-2"></i> <span>Backup</span></a></li>
                        
                    </ul>
                </div>
                
                <div class="p-3 border-top mt-auto"> 
                    <a href="#" class="btn btn-toggle align-items-center rounded text-danger fw-bold w-100" onclick="handleLogout()"><i class="bi bi-box-arrow-right me-2"></i> <span>Logout</span></a>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold" id="sidebarOffcanvasLabel">
                    <span class="me-2 fw-bold" style="color: var(--primary-color);">LOGO</span> Menu
                </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-3">
                <a href="#" class="btn btn-toggle align-items-center rounded active w-100"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>

                <h6 class="text-uppercase text-muted my-3 px-3">Main Navigation</h6>
                <li class="mb-1 list-unstyled">
                    <button class="btn btn-toggle align-items-center rounded collapsed w-100" data-bs-toggle="collapse" data-bs-target="#request-collapse-mobile" aria-expanded="false">
                        <i class="bi bi-file-earmark-text me-2"></i> Request
                    </button>
                    <div class="collapse ms-3" id="request-collapse-mobile">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="#" class="link-dark rounded"><i class="bi bi-files"></i> All Request</a></li>
                            <li><a href="#" class="link-dark rounded"><i class="bi bi-plus-circle"></i> Add Request</a></li>
                        </ul>
                    </div>
                </li>





                <div class="mt-auto pt-3 border-top w-100"> 
                    <a href="#" class="btn btn-toggle align-items-center rounded text-danger fw-bold w-100" onclick="handleLogout()"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
                </div>
            </div>
        </div>


        <main class="flex-grow-1 main-content" id="mainContent">
            
            <header class="content-header">
                
                <button class="btn btn-light d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                    <i class="bi bi-list fs-5"></i> 
                </button>
                
                <button id="sidebarToggle" class="btn btn-light d-none d-lg-block me-3" type="button">
                    <i class="bi bi-arrow-bar-left fs-5"></i> 
                </button>
                
                <h4 class="d-none d-sm-block my-0 text-muted fw-light">Welcome Admin!</h4>

                <div class="d-flex align-items-center ms-auto">
                    
                    <form class="d-none d-md-flex mx-auto search-form me-3">
                        <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
                    </form>

                    <div class="dropdown me-3">
                        <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell-fill fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                             <li><h6 class="dropdown-header">Notifications</h6></li>
                             <li><a class="dropdown-item" href="#">Pending Request #105</a></li>
                             <li><a class="dropdown-item" href="#">New User Signed Up</a></li>
                             <li><hr class="dropdown-divider"></li>
                             <li><a class="dropdown-item text-center" href="#">View All</a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://via.placeholder.com/32/343a40/FFFFFF?text=P" alt="Profile" width="32" height="32" class="rounded-circle border border-2 border-light">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser1">
                            <li><span class="dropdown-item-text fw-bold">User Ka Naam</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Profile Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="handleLogout()">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="content-body">
                <h2 class="mb-4">Dashboard Overview</h2>
                
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 bg-white rounded shadow-sm border-start border-5" style="border-color: var(--primary-color) !important;">
                            <p class="text-muted mb-1">Total Budgets</p>
                            <h3 class="fw-bold" style="color: var(--primary-color);">₹1,00,000</h3>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 bg-white rounded shadow-sm border-start border-5 border-success">
                            <p class="text-muted mb-1">Pending Requests</p>
                            <h3 class="fw-bold text-success">15</h3>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 bg-white rounded shadow-sm border-start border-5 border-danger">
                            <p class="text-muted mb-1">Unpaid Invoices</p>
                            <h3 class="fw-bold text-danger">5</h3>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 bg-white rounded shadow-sm border-start border-5 border-info">
                            <p class="text-muted mb-1">Total Procurements</p>
                            <h3 class="fw-bold text-info">45</h3>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                         <div class="p-4 bg-white rounded shadow-sm">
                            <h4>Recent Activities</h4>
                            <p>Detailed tables or charts yahan show kiye jaa sakte hain.</p>
                         </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global references (same as before)
        const sidebar = document.getElementById('sidebarDesktop');
        const mainContent = document.getElementById('mainContent');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        function handleLogout() {
            alert('Logging out... (Simulation)');
        }
        
        // Custom JavaScript for Sidebar Collapse (Desktop Functionality)
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                // 1. Toggle 'collapsed' class on sidebar and content area
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');

                // 2. Icon change
                const icon = sidebarToggle.querySelector('i');
                if (sidebar.classList.contains('collapsed')) {
                    icon.classList.remove('bi-arrow-bar-left');
                    icon.classList.add('bi-arrow-bar-right');
                } else {
                    icon.classList.remove('bi-arrow-bar-right');
                    icon.classList.add('bi-arrow-bar-left');
                }
            });
        }
    </script>
</body>
</html>