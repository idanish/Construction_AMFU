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
    <!-- <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" /> -->
    <!-- <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" /> -->
    <link rel="icon" type="image/x-icon" href="https://amfu.net/wp-content/uploads/2024/04/cropped-amfu-FAV-32x32.png" />

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
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

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
            /* background: linear-gradient(135deg, #36d1dc, #5b86e5); */
            background: linear-gradient(135deg, #ffc80c, #ffc80d);
            color: #fff;
        }

        .btn-download {
            /* background: linear-gradient(135deg, #1e3c72, #2a5298); */
            background: linear-gradient(135deg, #ffc80c, #ffc80d);
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