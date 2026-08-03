<header class="cms-topbar d-flex align-items-center justify-content-between px-3 px-md-4 bg-white border-bottom shadow-sm" style="height: var(--topbar-height);">
    <!-- Left Toggle & Breadcrumb -->
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-light btn-sm border-0" id="sidebarToggleBtn" type="button">
            <i class="bi bi-list fs-4"></i>
        </button>

        <nav aria-label="breadcrumb" class="d-none d-sm-block">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('cms.dashboard') }}" class="text-decoration-none text-muted">CMS</a></li>
                @yield('cms_breadcrumb')
            </ol>
        </nav>
    </div>

    <!-- Right User Profile Dropdown -->
    <div class="d-flex align-items-center gap-3">
        <!-- View Public Site Link -->
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill d-none d-md-inline-flex align-items-center gap-1">
            <i class="bi bi-globe"></i> Lihat Website
        </a>

        <!-- User Dropdown -->
        <div class="dropdown">
            <button class="btn btn-light btn-sm d-flex align-items-center gap-2 border-0 dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-circle bg-primary text-white fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <span class="fw-semibold text-dark small d-none d-sm-inline">{{ Auth::user()->name ?? 'User' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userDropdown">
                <li>
                    <div class="dropdown-header">
                        <div class="fw-bold text-dark">{{ Auth::user()->name ?? 'User' }}</div>
                        <small class="text-muted">{{ Auth::user()->email ?? '' }}</small>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item small" href="{{ route('home') }}" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-2 text-primary"></i>Lihat Website Publik
                    </a>
                </li>
                @if(Auth::user() && Auth::user()->isSuperAdmin())
                    <li>
                        <a class="dropdown-item small" href="{{ route('cms.pengaturan.index') }}">
                            <i class="bi bi-gear me-2 text-secondary"></i>Pengaturan Website
                        </a>
                    </li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('cms.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item small text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar (Logout)
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
