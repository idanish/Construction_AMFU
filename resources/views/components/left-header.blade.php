<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --sidebar-bg: #ffffff;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --menu-active-color: #696cff;
            --menu-active-bg: rgba(105, 108, 255, 0.16);
            --body-bg: #f5f5f9;
        }

        body { font-family: 'Public Sans', sans-serif; background-color: var(--body-bg); margin: 0; overflow-x: hidden; }

        /* --- Sidebar --- */
        #layout-menu {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            background: var(--sidebar-bg);
            box-shadow: 0 0.125rem 0.25rem rgba(161, 172, 184, 0.4);
            transition: all 0.3s ease-in-out;
            z-index: 1100;
            display: flex;
            flex-direction: column;
        }

        /* --- Responsive Logic --- */
        @media (max-width: 1199.98px) {
            #layout-menu {
                left: calc(-1 * var(--sidebar-width)); /* Hide off-screen */
            }
            #layout-menu.mobile-open {
                left: 0; /* Slide in */
            }
            #layout-page {
                margin-left: 0 !important;
            }
            .navbar {
                margin: 10px !important;
            }
        }

        @media (min-width: 1200px) {
            #layout-menu.collapsed { width: var(--sidebar-collapsed-width); }
            #layout-menu.collapsed .menu-text, 
            #layout-menu.collapsed .menu-header,
            #layout-menu.collapsed .menu-toggle::after { display: none; }
            #layout-page.expanded { margin-left: var(--sidebar-collapsed-width); }
        }

        /* Overlay */
        .layout-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1050;
        }
        .layout-overlay.show { display: block; }

        #layout-page {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease-in-out;
        }

        /* Rest of your styles */
        .app-brand { padding: 1.5rem 1rem; height: 64px; display: flex; align-items: center; justify-content: space-between; }
        .menu-inner { padding: 10px 0; list-style: none; flex-grow: 1; overflow-y: auto; }
        .menu-item { padding: 0 1rem; margin-bottom: 4px; }
        .menu-link { display: flex; align-items: center; padding: 10px 15px; text-decoration: none; color: #697a8d; border-radius: 0.375rem; white-space: nowrap; }
        .menu-item.active .menu-link { background-color: var(--menu-active-bg); color: var(--menu-active-color) !important; font-weight: 600; }
        .menu-icon { font-size: 1.3rem; margin-right: 0.8rem; }
        .menu-header { padding: 1.5rem 1rem 0.5rem 1.5rem; font-size: 0.75rem; color: #a1acb8; text-transform: uppercase; }
        .menu-sub { list-style: none; padding-left: 2rem; }
        .navbar { background: #fff; box-shadow: 0 0 0.375rem 0.25rem rgba(161, 172, 184, 0.15); margin: 12px 25px; border-radius: 0.375rem; }
        .vip-btn { border-radius: 30px; padding: 8px 16px; font-weight: 600; border: none; background: linear-gradient(135deg, #ffc80c, #ffc80d); color: #fff; }
        
        /* Mobile specific adjustments */
        @media (max-width: 576px) {
            .navbar .fw-semibold { display: none; } /* Hide "Welcome Admin" on very small screens */
            .vip-btn { padding: 5px 10px; font-size: 12px; }
        }
    </style>
</head>
<body>