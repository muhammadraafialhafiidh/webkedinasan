<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::get('nama_website', 'Portal Informasi Dinas Perikanan'))</title>
    <meta name="description" content="@yield('meta_description', strip_tags(\App\Models\Setting::get('deskripsi', 'Website resmi Dinas Perikanan')))">

    <!-- Favicon -->
    @php
        $fav = \App\Models\Setting::get('favicon');
        $favUrl = ($fav && \Illuminate\Support\Facades\Storage::disk('public')->exists($fav))
            ? asset('storage/' . $fav)
            : asset('favicon.ico');
    @endphp
    <link rel="icon" href="{{ $favUrl }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <!-- GLightbox CSS -->
    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">

    <style>
        :root {
            --primary:      #003F88;
            --primary-dark: #002A5C;
            --ocean:        #0077B6;
            --teal:         #00B4D8;
            --gold:         #F4A100;
            --gold-hover:   #D98E00;
            --surface:      #FFFFFF;
            --background:   #F8FAFC;
            --light-blue:   #EBF8FF;
            --dark-gray:    #334155;
            --near-black:   #0F172A;
            --muted-gray:   #64748B;
            --border-color: #E2E8F0;
            --success:      #10B981;
            --warning:      #F59E0B;
            --danger:       #EF4444;

            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 24px;
            --radius-pill: 9999px;
            
            --shadow-xs: 0 1px 3px rgba(15, 23, 42, 0.05);
            --shadow-sm: 0 4px 12px rgba(15, 23, 42, 0.06);
            --shadow-md: 0 10px 25px -5px rgba(15, 23, 42, 0.10);
            --shadow-lg: 0 20px 35px -10px rgba(15, 23, 42, 0.15);
            --transition: 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        html {
            scroll-behavior: smooth;
        }

        /* Accessibility & Focus Indicators */
        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        textarea:focus-visible,
        select:focus-visible {
            outline: 3px solid var(--teal) !important;
            outline-offset: 2px !important;
            box-shadow: 0 0 0 4px rgba(0, 180, 216, 0.25) !important;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--dark-gray);
            background-color: var(--background);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--near-black);
            letter-spacing: -0.02em;
        }

        .main-content {
            flex: 1 1 auto;
            min-width: 0;
            width: 100%;
        }

        .hover-white:hover {
            color: var(--surface) !important;
            text-decoration: underline !important;
        }

        /* Buttons & Cards */
        .btn-primary-custom {
            background-color: var(--primary);
            color: var(--surface) !important;
            border: none;
            padding: 10px 24px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            transition: all var(--transition);
            box-shadow: 0 4px 12px rgba(0, 63, 136, 0.2);
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 42, 92, 0.3);
        }

        .btn-gold {
            background-color: var(--gold);
            color: var(--near-black) !important;
            font-weight: 700;
            border-radius: var(--radius-pill);
            padding: 10px 24px;
            transition: all var(--transition);
            box-shadow: 0 4px 14px rgba(244, 161, 0, 0.25);
        }
        .btn-gold:hover {
            background-color: var(--gold-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(217, 142, 0, 0.35);
        }

        .card-custom {
            background: var(--surface);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: box-shadow var(--transition), transform var(--transition), border-color var(--transition);
        }

        .card-custom:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
            border-color: rgba(0, 119, 182, 0.3);
        }

        .hover-primary {
            transition: color var(--transition);
        }

        .hover-primary:hover {
            color: var(--primary) !important;
        }

        .card-custom:hover h5 a:not(.no-card-hover),
        .card-custom:hover h6 a:not(.no-card-hover) {
            color: var(--primary) !important;
        }

        .card-custom:hover .btn-outline-primary {
            background-color: var(--primary);
            color: var(--surface) !important;
            border-color: var(--primary);
        }

        .card-custom:hover .bi-chevron-right,
        .card-custom:hover .bi-arrow-right {
            transform: translateX(4px);
            transition: transform 0.2s ease;
        }

        /* Custom Breadcrumb Styling */
        .custom-breadcrumb .breadcrumb {
            min-width: 0;
            max-width: 100%;
        }

        .custom-breadcrumb .breadcrumb-item {
            min-width: 0;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            content: "›" !important;
            padding: 0 0.45rem;
            color: var(--muted-gray);
            font-weight: 700;
            font-size: 1.1rem;
            line-height: 1;
        }

        .custom-breadcrumb .breadcrumb-item a {
            color: var(--dark-gray);
            font-weight: 500;
            transition: color 0.2s ease;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .custom-breadcrumb .breadcrumb-item a:hover {
            color: var(--primary) !important;
        }

        .custom-breadcrumb .breadcrumb-item.active {
            color: var(--primary) !important;
            font-weight: 600;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        /* Targeted Text Wrapping & Overflow Prevention */
        .min-w-0 {
            min-width: 0 !important;
        }

        .text-break-word {
            overflow-wrap: anywhere !important;
            word-break: break-word !important;
        }

        .row > * {
            min-width: 0;
        }

        .card-custom, .stat-card, article {
            min-width: 0;
            max-width: 100%;
        }

        p,
        .card-custom h1, .card-custom h2, .card-custom h3, .card-custom h4, .card-custom h5, .card-custom h6,
        article h1, article h2, article h3, article h4, article h5, article h6,
        .content-body h1, .content-body h2, .content-body h3, .content-body h4, .content-body h5, .content-body h6,
        .news-html-content h1, .news-html-content h2, .news-html-content h3, .news-html-content h4, .news-html-content h5, .news-html-content h6,
        .service-html-content h1, .service-html-content h2, .service-html-content h3, .service-html-content h4, .service-html-content h5, .service-html-content h6 {
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .card-custom .badge:not(.badge-pill-counter),
        .badge-ocean,
        .badge-gold {
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-word;
            line-height: 1.35;
            max-width: 100%;
        }

        /* Rich Text / HTML dari CMS Handling */
        .content-body,
        .news-html-content,
        .service-html-content,
        .rich-text {
            min-width: 0;
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .content-body p,
        .news-html-content p,
        .service-html-content p,
        .rich-text p,
        .content-body div,
        .news-html-content div,
        .service-html-content div,
        .rich-text div,
        .content-body ul,
        .news-html-content ul,
        .service-html-content ul,
        .rich-text ul,
        .content-body ol,
        .news-html-content ol,
        .service-html-content ol,
        .rich-text ol,
        .content-body li,
        .news-html-content li,
        .service-html-content li,
        .rich-text li,
        .content-body blockquote,
        .news-html-content blockquote,
        .service-html-content blockquote,
        .rich-text blockquote {
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .content-body a,
        .news-html-content a,
        .service-html-content a,
        .rich-text a {
            overflow-wrap: anywhere;
            word-break: break-word;
            max-width: 100%;
        }

        .service-html-content a {
            color: var(--primary);
            text-decoration: underline;
            font-weight: 500;
            transition: color var(--transition);
        }

        .service-html-content a:hover {
            color: var(--ocean);
            text-decoration: underline;
        }

        .service-description p {
            margin-bottom: 0.85rem;
        }

        .service-description p:last-child {
            margin-bottom: 0;
        }

        .content-body img,
        .news-html-content img,
        .service-html-content img,
        .rich-text img,
        .content-body figure,
        .news-html-content figure,
        .service-html-content figure,
        .rich-text figure {
            max-width: 100% !important;
            height: auto !important;
            object-fit: contain;
        }

        .content-body iframe,
        .news-html-content iframe,
        .service-html-content iframe,
        .rich-text iframe,
        .content-body video,
        .news-html-content video,
        .service-html-content video,
        .rich-text video {
            max-width: 100% !important;
        }

        .content-body pre,
        .news-html-content pre,
        .service-html-content pre,
        .rich-text pre {
            max-width: 100%;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: anywhere;
            padding: 1rem;
            background: #f1f5f9;
            border-radius: var(--radius-sm);
        }

        .content-body code,
        .news-html-content code,
        .service-html-content code,
        .rich-text code {
            max-width: 100%;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        /* Tabel rich-text CMS: scroll hanya terjadi pada area tabel, bukan seluruh halaman */
        .content-body table,
        .news-html-content table,
        .service-html-content table,
        .rich-text table {
            display: block;
            width: 100% !important;
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-collapse: collapse;
            margin-bottom: 1.25rem;
        }

        .content-body table th,
        .content-body table td,
        .news-html-content table th,
        .news-html-content table td,
        .service-html-content table th,
        .service-html-content table td,
        .rich-text table th,
        .rich-text table td {
            white-space: normal;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .badge-ocean {
            background-color: rgba(0, 119, 182, 0.1);
            color: var(--ocean);
            border: 1px solid rgba(0, 119, 182, 0.2);
            border-radius: var(--radius-pill);
            padding: 5px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .badge-gold {
            background-color: rgba(244, 161, 0, 0.15);
            color: #925400;
            border: 1px solid rgba(244, 161, 0, 0.3);
            border-radius: var(--radius-pill);
            padding: 5px 12px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Top info bar */
        .top-info-bar {
            background-color: var(--primary-dark);
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.825rem;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        @media (max-width: 1199.98px) {
            .top-info-bar {
                display: none !important;
            }
        }

        /* Back to top button */
        #backToTop {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            border: none;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            z-index: 999;
            cursor: pointer;
            transition: all var(--transition);
        }

        #backToTop:hover {
            background-color: var(--ocean);
            transform: translateY(-4px) scale(1.05);
            box-shadow: var(--shadow-lg);
        }

        /* Custom Navbar & Dropdown Styling */
        .navbar {
            border-bottom: none !important;
            box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.12), var(--shadow-sm) !important;
        }

        .navbar .nav-link {
            position: relative;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .navbar .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.15) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .navbar .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .navbar .dropdown-menu {
            background-color: var(--primary) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25) !important;
            backdrop-filter: blur(12px);
        }

        .navbar .dropdown-item {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .navbar .dropdown-item:hover,
        .navbar .dropdown-item:focus {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.18) !important;
            transform: translateX(4px);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
            flex: 1 1 auto;
            margin-right: 0.5rem;
            overflow: hidden;
            transition: transform 0.25s ease;
        }

        .navbar-brand:hover {
            transform: translateY(-1px);
        }

        /* Desktop Container (≥1200px) */
        @media (min-width: 1200px) {
            .navbar .container {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: nowrap !important;
                max-width: 1440px !important;
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
            .navbar-brand {
                order: 1 !important;
                flex: 0 1 auto !important;
                margin-right: 2rem !important;
            }
            .navbar-collapse {
                order: 2 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-end !important;
                max-height: none !important;
                overflow: visible !important;
            }
            .navbar-nav {
                display: flex !important;
                align-items: center !important;
                gap: 0.5rem !important;
            }
            .navbar-nav .nav-link {
                padding: 0.5rem 0.85rem !important;
                font-size: 0.95rem !important;
                font-weight: 600 !important;
                display: inline-flex;
                align-items: center;
            }
            .navbar-nav .btn-gold {
                margin-left: 0.75rem !important;
                display: inline-flex;
                align-items: center;
            }
        }

        @media (min-width: 1440px) {
            .navbar .container {
                max-width: 1536px !important;
                padding-left: 2rem !important;
                padding-right: 2rem !important;
            }
            .navbar-nav {
                gap: 0.75rem !important;
            }
            .navbar-nav .nav-link {
                padding: 0.55rem 1rem !important;
                font-size: 0.975rem !important;
            }
        }

        /* Mobile & Tablet Container (<1200px) */
        @media (max-width: 1199.98px) {
            .navbar .container {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                flex-wrap: wrap !important;
            }

            /* Explicit Row 1 & Row 2 Order Enforcements */
            .navbar-brand {
                order: 1 !important;
                flex: 1 1 auto !important;
                max-width: calc(100% - 54px) !important;
                min-width: 0 !important;
                margin-right: 0 !important;
            }

            .navbar-toggler {
                order: 2 !important;
                flex-shrink: 0 !important;
                margin-left: auto !important;
                align-self: center !important;
            }

            /* Full-width Mobile Collapse Dropdown Panel on Row 2 (order 3) */
            .navbar-collapse {
                order: 3 !important;
                flex-basis: 100% !important;
                width: 100% !important;
                margin-top: 0.75rem;
                padding: 1.25rem 1rem 2.25rem 1rem;
                background-color: var(--primary-dark);
                border-radius: var(--radius-md);
                border: 1px solid rgba(255, 255, 255, 0.12);
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                max-height: calc(100vh - 80px);
                max-height: calc(100dvh - 80px);
                overflow-y: auto;
                overflow-x: hidden;
                -webkit-overflow-scrolling: touch;
                overscroll-behavior: contain;
                scrollbar-width: thin;
                scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
            }

            .navbar-collapse::-webkit-scrollbar {
                width: 5px;
            }
            .navbar-collapse::-webkit-scrollbar-track {
                background: transparent;
            }
            .navbar-collapse::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 4px;
            }
            .navbar-collapse::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.5);
            }

            .navbar-collapse .navbar-nav {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 0.4rem;
                margin-bottom: 0 !important;
            }

            .navbar-collapse .nav-item {
                width: 100%;
            }

            .navbar-collapse .nav-link {
                display: flex;
                align-items: center;
                width: 100%;
                padding: 0.65rem 1rem !important;
                border-radius: var(--radius-sm);
                font-size: 0.95rem;
            }

            .navbar-collapse .nav-item .btn-gold {
                width: 100% !important;
                justify-content: center;
                margin-top: 0.5rem;
                padding: 0.65rem 1rem !important;
            }

            .navbar-collapse .dropdown-menu {
                position: static !important;
                float: none !important;
                width: 100% !important;
                margin-top: 0.4rem !important;
                background-color: rgba(0, 0, 0, 0.25) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                box-shadow: none !important;
                padding: 0.5rem !important;
                border-radius: var(--radius-sm) !important;
            }

            .navbar-collapse .dropdown-item {
                padding: 0.5rem 0.85rem !important;
                font-size: 0.9rem;
            }
        }

        .navbar-brand img,
        .navbar-brand > div.bg-warning {
            flex-shrink: 0 !important;
            object-fit: contain;
            transition: height 0.2s ease, width 0.2s ease;
        }

        .navbar-brand > div:not(.bg-warning) {
            min-width: 0;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .navbar-brand,
        .navbar-brand div,
        .navbar-brand span {
            white-space: normal !important;
        }

        .navbar-brand span {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.01em;
            word-break: break-word;
            overflow-wrap: anywhere;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .navbar-brand small {
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .navbar-toggler {
            flex-shrink: 0 !important;
            margin-left: auto;
        }

        /* Responsive Breakpoints for Navbar Brand */
        /* Desktop (≥1200px) */
        @media (min-width: 1200px) {
            .navbar-brand span {
                font-size: 1.15rem !important;
                line-height: 1.25 !important;
            }
            .navbar-brand small {
                font-size: 0.725rem !important;
            }
        }

        /* Laptop & Large Tablet (992px - 1199.98px) */
        @media (max-width: 1199.98px) and (min-width: 992px) {
            .navbar-brand span {
                font-size: 1.05rem !important;
                line-height: 1.25 !important;
            }
            .navbar-brand img {
                height: 40px !important;
            }
        }

        /* Tablet (768px - 991.98px) */
        @media (max-width: 991.98px) and (min-width: 768px) {
            .navbar-brand span {
                font-size: 0.95rem !important;
                line-height: 1.25 !important;
            }
            .navbar-brand img {
                height: 38px !important;
            }
        }

        /* Mobile Medium/Large (480px - 767.98px) */
        @media (max-width: 767.98px) and (min-width: 480px) {
            .navbar-brand {
                gap: 0.6rem;
            }
            .navbar-brand img {
                height: 36px !important;
                max-width: 130px !important;
            }
            .navbar-brand span {
                font-size: 0.85rem !important;
                line-height: 1.25 !important;
            }
            .navbar-brand small {
                font-size: 0.675rem !important;
            }
        }

        /* Small Mobile (360px - 479.98px) */
        @media (max-width: 479.98px) and (min-width: 360px) {
            .navbar-brand {
                gap: 0.5rem;
            }
            .navbar-brand img {
                height: 34px !important;
                max-width: 110px !important;
            }
            .navbar-brand span {
                font-size: 0.785rem !important;
                line-height: 1.2 !important;
            }
        }

        /* Extra Small Mobile (320px - 359.98px) */
        @media (max-width: 359.98px) {
            .navbar-brand {
                gap: 0.4rem;
                margin-right: 0.25rem;
            }
            .navbar-brand img {
                height: 30px !important;
                max-width: 95px !important;
            }
            .navbar-brand span {
                font-size: 0.725rem !important;
                line-height: 1.18 !important;
            }
            .navbar-toggler {
                padding: 0.25rem 0.4rem !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Top Info Bar -->
    <div class="top-info-bar">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <span><i class="bi bi-telephone-fill text-warning me-1"></i> {{ \App\Models\Setting::get('telepon', '(0281) 123456') }}</span>
                <span class="d-none d-md-inline"><i class="bi bi-envelope-fill text-warning me-1"></i> {{ \App\Models\Setting::get('email', 'info@perikanan.go.id') }}</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-sm-inline"><i class="bi bi-clock-fill text-warning me-1"></i> {{ \App\Models\Setting::get('jam_operasional', 'Senin–Jumat: 08.00–16.00 WIB') }}</span>
                <!-- <a href="{{ route('cms.login') }}" class="text-white text-decoration-none border-start ps-3 border-secondary opacity-75 hover-white"><i class="bi bi-person-lock me-1"></i> CMS Login</a> -->
            </div>
        </div>
    </div>

    <!-- Header & Navigation -->
    @include('partials.navbar')

    <!-- Main Content Area -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Back to Top Button -->
    <button id="backToTop" title="Kembali ke atas"><i class="bi bi-arrow-up-short fs-3"></i></button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- GLightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // GLightbox init
            if (typeof GLightbox !== 'undefined') {
                const lightbox = GLightbox({
                    selector: '.glightbox',
                    touchNavigation: true,
                    loop: true,
                    autoplayVideos: true
                });
            }

            // Back to top behavior
            const backToTopBtn = document.getElementById('backToTop');
            if (backToTopBtn) {
                window.addEventListener('scroll', function () {
                    if (window.scrollY > 300) {
                        backToTopBtn.style.display = 'flex';
                    } else {
                        backToTopBtn.style.display = 'none';
                    }
                });

                backToTopBtn.addEventListener('click', function () {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            // Mobile navbar behavior & body scroll lock
            const navbarMain = document.getElementById('navbarMain');
            if (navbarMain) {
                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(navbarMain, { toggle: false });
                let savedScrollY = 0;
                let isBodyLocked = false;

                function lockBodyScroll() {
                    if (isBodyLocked || window.innerWidth >= 1200) return;
                    savedScrollY = window.scrollY || window.pageYOffset || document.documentElement.scrollTop || 0;
                    document.body.style.position = 'fixed';
                    document.body.style.top = `-${savedScrollY}px`;
                    document.body.style.left = '0';
                    document.body.style.right = '0';
                    document.body.style.width = '100%';
                    document.body.style.overflow = 'hidden';
                    isBodyLocked = true;
                }

                function unlockBodyScroll() {
                    if (!isBodyLocked) return;
                    document.body.style.position = '';
                    document.body.style.top = '';
                    document.body.style.left = '';
                    document.body.style.right = '';
                    document.body.style.width = '';
                    document.body.style.overflow = '';
                    window.scrollTo(0, savedScrollY);
                    isBodyLocked = false;
                }

                // Event listener saat collapse mulai atau selesai dibuka
                navbarMain.addEventListener('show.bs.collapse', function () {
                    lockBodyScroll();
                });

                navbarMain.addEventListener('shown.bs.collapse', function () {
                    lockBodyScroll();
                });

                // Event listener saat collapse mulai atau selesai ditutup
                navbarMain.addEventListener('hide.bs.collapse', function () {
                    unlockBodyScroll();
                });

                navbarMain.addEventListener('hidden.bs.collapse', function () {
                    unlockBodyScroll();
                });

                // Window resize listener
                window.addEventListener('resize', function () {
                    if (window.innerWidth >= 1200) {
                        unlockBodyScroll();
                        if (navbarMain.classList.contains('show')) {
                            bsCollapse.hide();
                        }
                    }
                });

                // Auto-close saat klik di luar area navbar pada mobile
                document.addEventListener('click', function (e) {
                    if (window.innerWidth < 1200 && navbarMain.classList.contains('show')) {
                        const isClickInsideNavbar = navbarMain.contains(e.target) || e.target.closest('.navbar-toggler');
                        if (!isClickInsideNavbar) {
                            bsCollapse.hide();
                        }
                    }
                });

                // Auto-close saat item menu/link navigasi diklik (bukan dropdown toggle)
                const navLinks = navbarMain.querySelectorAll('.nav-link:not(.dropdown-toggle), .dropdown-item, .btn');
                navLinks.forEach(function (link) {
                    link.addEventListener('click', function () {
                        if (window.innerWidth < 1200 && navbarMain.classList.contains('show')) {
                            bsCollapse.hide();
                        }
                    });
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
