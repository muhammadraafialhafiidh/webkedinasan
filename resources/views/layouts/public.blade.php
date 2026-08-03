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
            flex: 1;
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
            transition: transform 0.25s ease;
        }

        .navbar-brand:hover {
            transform: translateY(-1px);
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
                <a href="{{ route('cms.login') }}" class="text-white text-decoration-none border-start ps-3 border-secondary opacity-75 hover-white"><i class="bi bi-person-lock me-1"></i> CMS Login</a>
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

            // Auto-close mobile navbar on click outside or link selection
            const navbarMain = document.getElementById('navbarMain');
            if (navbarMain) {
                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(navbarMain, { toggle: false });

                document.addEventListener('click', function (e) {
                    const isClickInsideNavbar = navbarMain.contains(e.target) || e.target.closest('.navbar-toggler');
                    if (!isClickInsideNavbar && navbarMain.classList.contains('show')) {
                        bsCollapse.hide();
                    }
                });

                const navLinks = navbarMain.querySelectorAll('.nav-link:not(.dropdown-toggle), .dropdown-item, .btn');
                navLinks.forEach(function (link) {
                    link.addEventListener('click', function () {
                        if (navbarMain.classList.contains('show')) {
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
