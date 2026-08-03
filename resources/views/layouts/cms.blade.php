<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard CMS — Panel Informasi Dinas Perikanan')</title>

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

    <!-- CKEditor 5 Superbuild CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>

    <style>
        :root {
            --primary:      #003F88;
            --primary-dark: #002A5C;
            --ocean:        #0077B6;
            --teal:         #00B4D8;
            --gold:         #F4A100;
            --white:        #FFFFFF;
            --light:        #F4F6F9;
            --mid-gray:     #ADB5BD;
            --dark-gray:    #495057;
            --near-black:   #1A1D23;
            --success:      #198754;
            --warning:      #FFC107;
            --danger:       #DC3545;

            --sidebar-width: 260px;
            --topbar-height: 64px;
            --radius-sm: 6px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --shadow-sm: 0 1px 4px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.10);
            --transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light);
            color: var(--near-black);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Layout Container */
        .cms-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling (Sticky 100vh) */
        .cms-sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary);
            color: var(--white);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            height: 100vh;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform var(--transition), width var(--transition);
            box-shadow: 2px 0 12px rgba(0,0,0,0.12);
        }

        /* Custom Thin Scrollbar & Smooth Scrolling */
        .sidebar-menu {
            scroll-behavior: smooth;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 180, 216, 0.4) transparent;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 7px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(0, 180, 216, 0.4);
            border-radius: 9999px;
            transition: background var(--transition);
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: var(--teal);
        }

        /* Scroll Indicator Shadows (Top & Bottom Overlay) */
        .sidebar-menu-wrapper {
            position: relative;
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            overflow: hidden;
        }

        .sidebar-menu-wrapper::before,
        .sidebar-menu-wrapper::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            height: 20px;
            z-index: 10;
            pointer-events: none;
            opacity: 0;
            transition: opacity var(--transition);
        }

        .sidebar-menu-wrapper::before {
            top: 0;
            background: linear-gradient(to bottom, rgba(0, 42, 92, 0.8), transparent);
        }

        .sidebar-menu-wrapper::after {
            bottom: 0;
            background: linear-gradient(to top, rgba(0, 42, 92, 0.8), transparent);
        }

        .sidebar-menu-wrapper.has-scroll-top::before {
            opacity: 1;
        }

        .sidebar-menu-wrapper.has-scroll-bottom::after {
            opacity: 1;
        }

        /* Nav Link Base & Micro-Interactions */
        .cms-sidebar .nav-link {
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            transition: all var(--transition);
            position: relative;
            display: flex;
            align-items: center;
        }

        .cms-sidebar .nav-link i.fs-5 {
            transition: transform var(--transition), color var(--transition);
        }

        .cms-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: var(--white);
            transform: translateX(3px);
        }

        .cms-sidebar .nav-link:hover i.fs-5 {
            color: var(--teal) !important;
            transform: scale(1.1);
        }

        /* Active Menu State */
        .cms-sidebar .nav-link.active {
            background: linear-gradient(90deg, rgba(0, 180, 216, 0.22) 0%, rgba(255, 255, 255, 0.08) 100%);
            color: var(--white);
            border-left: 4px solid var(--teal);
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .cms-sidebar .nav-link.active i.fs-5 {
            color: var(--teal) !important;
        }

        /* Submenu Chevron Rotation Animation */
        .cms-sidebar .nav-link .bi-chevron-down {
            transition: transform var(--transition);
        }

        .cms-sidebar .nav-link:not(.collapsed) .bi-chevron-down {
            transform: rotate(180deg);
        }

        /* Submenu Container Smooth Transition */
        .cms-sidebar .collapse {
            transition: height 0.25s ease;
        }

        .cms-sidebar .collapse .nav-link {
            font-size: 0.875rem;
            padding: 7px 12px;
        }

        /* Main Workspace Area */
        .cms-main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left var(--transition), width var(--transition);
        }

        /* Responsive Sidebar Toggle */
        body.sidebar-collapsed .cms-sidebar {
            transform: translateX(-100%);
        }
        body.sidebar-collapsed .cms-main-content {
            margin-left: 0;
            width: 100%;
        }

        @media (max-width: 991px) {
            .cms-sidebar {
                transform: translateX(-100%);
            }
            .cms-main-content {
                margin-left: 0;
                width: 100%;
            }
            body.sidebar-expanded .cms-sidebar {
                transform: translateX(0);
            }
        }

        /* CMS Table */
        .table-cms {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .table-cms thead th {
            background: var(--light);
            color: var(--near-black);
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 2px solid #DEE2E6;
            white-space: nowrap;
        }
        .table-cms tbody tr:hover {
            background: #F8F9FA;
        }
        .table-cms tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid #EEEEEE;
            vertical-align: middle;
        }

        /* CMS Action Buttons Component */
        .btn-action-group {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-action-grid-2x2 {
            display: inline-grid;
            grid-template-columns: repeat(2, 36px);
            gap: 6px;
            justify-content: center;
            align-items: center;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            line-height: 1;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        .btn-action:active {
            transform: translateY(0);
        }

        /* Variant Edit (Outline Primary) */
        .btn-action-edit {
            color: var(--primary);
            border: 1.5px solid var(--primary);
            background-color: transparent;
        }
        .btn-action-edit:hover, .btn-action-edit:focus {
            color: #ffffff !important;
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* Variant Delete (Outline Danger) */
        .btn-action-delete {
            color: var(--danger);
            border: 1.5px solid var(--danger);
            background-color: transparent;
        }
        .btn-action-delete:hover, .btn-action-delete:focus {
            color: #ffffff !important;
            background-color: var(--danger);
            border-color: var(--danger);
        }

        /* Variant Info / View (Outline Info / Ocean) */
        .btn-action-info {
            color: var(--ocean);
            border: 1.5px solid var(--ocean);
            background-color: transparent;
        }
        .btn-action-info:hover, .btn-action-info:focus {
            color: #ffffff !important;
            background-color: var(--ocean);
            border-color: var(--ocean);
        }

        /* Variant Warning / Toggle / Reset (Outline Warning / Gold) */
        .btn-action-warning {
            color: #d97706;
            border: 1.5px solid #f59e0b;
            background-color: transparent;
        }
        .btn-action-warning:hover, .btn-action-warning:focus {
            color: #ffffff !important;
            background-color: #d97706;
            border-color: #d97706;
        }

        /* Variant Success (Outline Success) */
        .btn-action-success {
            color: var(--success);
            border: 1.5px solid var(--success);
            background-color: transparent;
        }
        .btn-action-success:hover, .btn-action-success:focus {
            color: #ffffff !important;
            background-color: var(--success);
            border-color: var(--success);
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="cms-wrapper">
    <!-- Sidebar -->
    @include('cms.partials.sidebar')

    <!-- Main Content Body -->
    <div class="cms-main-content">
        <!-- Topbar -->
        @include('cms.partials.topbar')

        <!-- Page Content Area -->
        <main class="p-3 p-md-4 flex-grow-1">
            <!-- Floating Pop-up Toast Notification Container -->
            <div id="cmsToastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;">
                @if(session('success'))
                    <div class="toast align-items-center text-white bg-success border-0 show shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4500">
                        <div class="d-flex">
                            <div class="toast-body d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill fs-4"></i>
                                <div>
                                    <strong class="d-block me-auto">Berhasil!</strong>
                                    <span>{{ session('success') }}</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="toast align-items-center text-white bg-danger border-0 show shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="6000">
                        <div class="d-flex">
                            <div class="toast-body d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                <div>
                                    <strong class="d-block me-auto">Perhatian!</strong>
                                    <span>{{ session('error') }}</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="toast align-items-center text-white bg-danger border-0 show shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-x-circle-fill fs-4"></i>
                                    <strong>Kesalahan Input:</strong>
                                </div>
                                <ul class="mb-0 ps-3 small">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-top py-3 px-4 text-center text-muted small">
            <span>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('nama_website') }} — Panel Admin CMS</span>
        </footer>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    window.ckEditors = window.ckEditors || {};

    function initCKEditorOnTextarea(textarea) {
        if (!textarea || textarea.dataset.ckeditorInitialized === 'true' || textarea.hasAttribute('data-no-ckeditor')) return;
        textarea.dataset.ckeditorInitialized = 'true';

        const EditorConstructor = (typeof CKEDITOR !== 'undefined' && CKEDITOR.ClassicEditor)
            ? CKEDITOR.ClassicEditor
            : ((typeof ClassicEditor !== 'undefined') ? ClassicEditor : null);

        if (!EditorConstructor) {
            console.error('CKEditor 5 constructor not available for:', textarea);
            return;
        }

        const config = {
            toolbar: {
                items: [
                    'undo', 'redo', '|',
                    'heading', '|',
                    'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                    'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
                    'alignment', '|',
                    'bulletedList', 'numberedList', 'blockQuote', '|',
                    'link', 'insertTable', 'horizontalLine', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            fontSize: {
                options: [ 9, 11, 13, 'default', 17, 19, 21, 24, 28, 32, 36 ]
            }
        };

        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.ClassicEditor) {
            config.removePlugins = [
                'AIAssistant', 'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges', 'RealTimeCollaborativeRevisionHistory',
                'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
                'RevisionHistory', 'Pagination', 'WProofreader', 'MathType',
                'SlashCommand', 'Template', 'DocumentOutline', 'FormatPainter',
                'TableOfContents', 'PasteFromOfficeEnhanced', 'CaseChange', 'MultiLevelList'
            ];
        }

        EditorConstructor.create(textarea, config).then(function (editor) {
            if (textarea.id) {
                window.ckEditors[textarea.id] = editor;
            }

            // Sync editor content to underlying textarea
            editor.model.document.on('change:data', function () {
                textarea.value = editor.getData();
            });

            // Ensure value is updated before form submit
            if (textarea.form) {
                textarea.form.addEventListener('submit', function () {
                    textarea.value = editor.getData();
                });
            }
        }).catch(function (error) {
            console.error('Error initializing CKEditor 5 on:', textarea, error);
        });
    }

    // Initialize all Bootstrap 5 Tooltips globally
    function initBootstrapTooltips() {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            if (!bootstrap.Tooltip.getInstance(tooltipTriggerEl)) {
                new bootstrap.Tooltip(tooltipTriggerEl, { trigger: 'hover' });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Toggle Sidebar
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        if (sidebarToggleBtn) {
            sidebarToggleBtn.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    document.body.classList.toggle('sidebar-expanded');
                } else {
                    document.body.classList.toggle('sidebar-collapsed');
                }
            });
        }

        // Auto initialize and show all Bootstrap 5 Toasts
        const toastElList = document.querySelectorAll('#cmsToastContainer .toast');
        toastElList.forEach(function (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        });

        // Initialize tooltips
        initBootstrapTooltips();

        // Auto-initialize CKEditor 5 on all <textarea> elements in CMS
        document.querySelectorAll('textarea').forEach(function (textarea) {
            initCKEditorOnTextarea(textarea);
        });

        // -------------------------------------------------------------
        // Sidebar UX Improvements (Auto-Scroll, Scroll Shadow Indicators)
        // -------------------------------------------------------------
        const sidebarMenu = document.querySelector('.sidebar-menu');
        const sidebarWrapper = document.querySelector('.sidebar-menu-wrapper');

        if (sidebarMenu) {
            // Auto scroll to active item
            const activeLink = sidebarMenu.querySelector('.nav-link.active');
            if (activeLink) {
                setTimeout(function () {
                    activeLink.scrollIntoView({
                        block: 'nearest',
                        behavior: 'smooth'
                    });
                }, 150);
            }

            // Real-time scroll indicator shadows
            function updateSidebarScrollShadows() {
                if (!sidebarWrapper) return;
                const scrollTop = sidebarMenu.scrollTop;
                const scrollHeight = sidebarMenu.scrollHeight;
                const clientHeight = sidebarMenu.clientHeight;

                if (scrollTop > 8) {
                    sidebarWrapper.classList.add('has-scroll-top');
                } else {
                    sidebarWrapper.classList.remove('has-scroll-top');
                }

                if (scrollTop + clientHeight < scrollHeight - 8) {
                    sidebarWrapper.classList.add('has-scroll-bottom');
                } else {
                    sidebarWrapper.classList.remove('has-scroll-bottom');
                }
            }

            sidebarMenu.addEventListener('scroll', updateSidebarScrollShadows);
            updateSidebarScrollShadows();
        }
    });

    // Helper to safely set CKEditor 5 data for dynamic modal populated textareas
    window.setEditorData = function (elementId, data) {
        const el = document.getElementById(elementId);
        if (el) {
            el.value = data || '';
        }
        if (window.ckEditors && window.ckEditors[elementId]) {
            window.ckEditors[elementId].setData(data || '');
        }
    };

    // Global helper for triggering floating pop-up toasts dynamically
    window.showCmsToast = function (type, message, title) {
        const container = document.getElementById('cmsToastContainer');
        if (!container) return;

        const bgClass = type === 'success' ? 'bg-success' : (type === 'danger' || type === 'error' ? 'bg-danger' : 'bg-warning');
        const iconClass = type === 'success' ? 'bi-check-circle-fill' : (type === 'danger' || type === 'error' ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill');
        const defaultTitle = type === 'success' ? 'Berhasil!' : (type === 'danger' || type === 'error' ? 'Gagal!' : 'Informasi');

        const toastHtml = `
            <div class="toast align-items-center text-white ${bgClass} border-0 show shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2">
                        <i class="bi ${iconClass} fs-4"></i>
                        <div>
                            <strong class="d-block me-auto">${title || defaultTitle}</strong>
                            <span>${message}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', toastHtml);
        const lastToast = container.lastElementChild;
        const bsToast = new bootstrap.Toast(lastToast);
        bsToast.show();
    };
</script>
@stack('scripts')
</body>
</html>
