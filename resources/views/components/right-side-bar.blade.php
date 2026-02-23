<!DOCTYPE html>
<html lang="ar" dir="rtl" class="light-style layout-menu-fixed">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>لوحة التحكم - Dashboard</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #ffffff;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --menu-active-color: #696cff;
            --menu-active-bg: rgba(105, 108, 255, 0.16);
            --body-bg: #f5f5f9;
        }

        body { font-family: 'Cairo', sans-serif; background-color: var(--body-bg); margin: 0; overflow-x: hidden; }

        /* --- Sidebar RTL --- */
        #layout-menu {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            right: 0; /* Changed from left to right */
            top: 0;
            background: var(--sidebar-bg);
            box-shadow: -0.125rem 0 0.25rem rgba(161, 172, 184, 0.4);
            transition: all 0.3s ease-in-out;
            z-index: 1100;
            display: flex;
            flex-direction: column;
        }

        /* --- Responsive Logic RTL --- */
        @media (max-width: 1199.98px) {
            #layout-menu {
                right: calc(-1 * var(--sidebar-width)); /* Hide off-screen to the right */
                left: auto;
            }
            #layout-menu.mobile-open {
                right: 0; /* Slide in from right */
            }
            #layout-page {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }
        }

        @media (min-width: 1200px) {
            #layout-menu.collapsed { width: var(--sidebar-collapsed-width); }
            #layout-menu.collapsed .menu-text, 
            #layout-menu.collapsed .menu-header,
            #layout-menu.collapsed .menu-toggle::after { display: none; }
            #layout-page.expanded { margin-right: var(--sidebar-collapsed-width); margin-left: 0; }
        }

        /* Overlay */
        .layout-overlay {
            display: none;
            position: fixed;
            top: 0; right: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1050;
        }
        .layout-overlay.show { display: block; }

        #layout-page {
            margin-right: var(--sidebar-width); /* Changed from margin-left */
            margin-left: 0;
            transition: all 0.3s ease-in-out;
        }

        /* Layout Elements */
        .app-brand { padding: 1.5rem 1rem; height: 64px; display: flex; align-items: center; justify-content: space-between; }
        .menu-inner { padding: 10px 0; list-style: none; flex-grow: 1; overflow-y: auto; }
        .menu-item { padding: 0 1rem; margin-bottom: 4px; }
        .menu-link { display: flex; align-items: center; padding: 10px 15px; text-decoration: none; color: #697a8d; border-radius: 0.375rem; white-space: nowrap; }
        
        /* Mirroring icons for RTL */
        .menu-icon { font-size: 1.3rem; margin-left: 0.8rem; margin-right: 0; }
        
        .menu-item.active .menu-link { background-color: var(--menu-active-bg); color: var(--menu-active-color) !important; font-weight: 600; }
        .menu-header { padding: 1.5rem 1.5rem 0.5rem 1rem; font-size: 0.75rem; color: #a1acb8; text-transform: uppercase; }
        .menu-sub { list-style: none; padding-right: 2rem; padding-left: 0; }
        
        .navbar { background: #fff; box-shadow: 0 0 0.375rem 0.25rem rgba(161, 172, 184, 0.15); margin: 12px 25px; border-radius: 0.375rem; }
        .vip-btn { border-radius: 30px; padding: 8px 16px; font-weight: 600; border: none; background: linear-gradient(135deg, #ffc80c, #ffc80d); color: #fff; }
        
        /* Toggle button icon should face other way if necessary */
        #btn-toggle i { transform: scaleX(-1); }
    </style>
</head>
<body>

    <div class="layout-overlay" id="layout-overlay"></div>

    <aside id="layout-menu">
        <div class="app-brand">
            <a href="#" class="app-brand-link">
                <img src="https://amfu.net/wp-content/uploads/2024/07/cropped-amfu-for-web-new.png" id="logo-img" alt="Logo" width="140px">
            </a>
            <button class="btn d-xl-none border-0" id="btn-close-sidebar">
                <i class="bx bx-x fs-4"></i>
            </button>
        </div>

        <ul class="menu-inner">
            <li class="menu-item active">
                <a href="#" class="menu-link">
                    <i class="menu-icon bx bx-home-circle"></i>
                    <div class="menu-text">لوحة القيادة</div>
                </a>
            </li>
            <li class="menu-header small">الطلبات</li>
            <li class="menu-item">
                <a href="#requestSub" data-bs-toggle="collapse" class="menu-link menu-toggle">
                    <i class="menu-icon bx bx-receipt"></i>
                    <div class="menu-text">الطلبات</div>
                </a>
                <ul class="collapse menu-sub" id="requestSub">
                    <li class="menu-item"><a href="#" class="menu-link">جميع الطلبات</a></li>
                </ul>
            </li>
        </ul>
        
        <div class="p-3 border-top mt-auto">
            <button class="btn btn-outline-danger w-100">
                <i class="bi bi-box-arrow-left"></i> <span class="menu-text">تسجيل الخروج</span>
            </button>
        </div>
    </aside>

    <div id="layout-page">
        <nav class="navbar navbar-expand-xl align-items-center">
            <div class="container-fluid">
                <button class="btn border-0" id="btn-toggle">
                    <i class="bx bx-menu fs-4"></i>
                </button>
                <div class="ms-auto d-flex align-items-center">
                    <button class="vip-btn me-2">إجراء جديد</button>
                    <span class="fw-semibold px-2">المدير</span>
                </div>
            </div>
        </nav>

        <div class="content-wrapper p-4">
            <div class="card border-0 shadow-sm p-4">
                <h5>نظرة عامة على لوحة القيادة</h5>
                <p>یہ ورژن اب دائیں سے بائیں (RTL) مکمل طور پر سپورٹ کرتا ہے۔</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const btnToggle = document.getElementById('btn-toggle');
        const btnCloseSidebar = document.getElementById('btn-close-sidebar');
        const sidebar = document.getElementById('layout-menu');
        const layoutPage = document.getElementById('layout-page');
        const overlay = document.getElementById('layout-overlay');
        const logoImg = document.getElementById('logo-img');

        function toggleSidebar() {
            if (window.innerWidth >= 1200) {
                sidebar.classList.toggle('collapsed');
                layoutPage.classList.toggle('expanded');
                logoImg.style.width = sidebar.classList.contains('collapsed') ? '50px' : '140px';
            } else {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('show');
            }
        }

        btnToggle.addEventListener('click', toggleSidebar);
        btnCloseSidebar.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1200) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('show');
            }
        });
    </script>
</body>
</html>