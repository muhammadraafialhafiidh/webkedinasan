<aside id="cmsSidebar" class="cms-sidebar d-flex flex-column flex-shrink-0">
    <!-- Sidebar Header -->
    <div class="sidebar-header d-flex align-items-center gap-2 px-3 py-3 border-bottom border-secondary border-opacity-25">
        @php
            $logo = \App\Models\Setting::get('logo');
        @endphp
        @if($logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo))
            <img src="{{ asset('storage/' . $logo) }}" alt="Logo" style="height: 36px; max-width: 120px; object-fit: contain;">
        @else
            <i class="bi bi-shield-lock-fill text-warning fs-3"></i>
        @endif
        <div class="sidebar-title-box">
            <h6 class="fw-bold text-white mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif;">CMS Perikanan</h6>
            <small class="text-white-50" style="font-size: 0.75rem;">Panel Kelola Konten</small>
        </div>
    </div>

    <!-- User Info Box -->
    <div class="user-info-box px-3 py-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center gap-2 bg-black bg-opacity-10">
        <div class="avatar-circle bg-warning text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>
        <div class="overflow-hidden">
            <div class="fw-bold text-white text-truncate small">{{ Auth::user()->name ?? 'User' }}</div>
            <span class="badge bg-warning text-dark font-monospace" style="font-size: 0.65rem;">
                @if(Auth::user() && Auth::user()->isRootSuperAdmin())
                    Root Super Admin
                @elseif(Auth::user() && Auth::user()->isSuperAdmin())
                    Super Admin
                @else
                    Admin Dinas
                @endif
            </span>
        </div>
    </div>

    <!-- Navigation Menu Wrapper -->
    <div class="sidebar-menu-wrapper position-relative flex-grow-1 d-flex flex-column overflow-hidden">
        <div class="sidebar-menu flex-grow-1 overflow-auto py-2">
            <ul class="nav nav-pills flex-column mb-auto gap-1 px-2">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('cms.dashboard') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('cms.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 fs-5"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Statistik Website -->
                <li class="nav-item">
                    <a href="{{ route('cms.statistik.index') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('cms.statistik*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line fs-5"></i>
                        <span>Statistik Website</span>
                    </a>
                </li>

                <!-- Berita Submenu -->
                <li class="nav-item">
                    <a href="#menuBerita" data-bs-toggle="collapse" class="nav-link text-white d-flex align-items-center justify-content-between {{ request()->routeIs('cms.berita*') || request()->routeIs('cms.kategori-berita*') ? 'active' : 'collapsed' }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-newspaper fs-5"></i>
                            <span>Manajemen Berita</span>
                        </div>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('cms.berita*') || request()->routeIs('cms.kategori-berita*') ? 'show' : '' }} ms-3 mt-1" id="menuBerita">
                        <ul class="nav nav-pills flex-column gap-1">
                            <li>
                                <a href="{{ route('cms.berita.index') }}" class="nav-link text-white-50 small py-1 px-3 {{ request()->routeIs('cms.berita*') ? 'active text-white fw-bold' : '' }}">
                                    <i class="bi bi-list-ul me-2"></i>Semua Berita
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cms.kategori-berita.index') }}" class="nav-link text-white-50 small py-1 px-3 {{ request()->routeIs('cms.kategori-berita*') ? 'active text-white fw-bold' : '' }}">
                                    <i class="bi bi-tags me-2"></i>Kategori Berita
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Layanan Submenu -->
                <li class="nav-item">
                    <a href="#menuLayanan" data-bs-toggle="collapse" class="nav-link text-white d-flex align-items-center justify-content-between {{ request()->routeIs('cms.layanan*') || request()->routeIs('cms.kategori-layanan*') || request()->routeIs('cms.penanggung-jawab*') ? 'active' : 'collapsed' }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-tools fs-5"></i>
                            <span>Manajemen Layanan</span>
                        </div>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('cms.layanan*') || request()->routeIs('cms.kategori-layanan*') || request()->routeIs('cms.penanggung-jawab*') ? 'show' : '' }} ms-3 mt-1" id="menuLayanan">
                        <ul class="nav nav-pills flex-column gap-1">
                            <li>
                                <a href="{{ route('cms.layanan.index') }}" class="nav-link text-white-50 small py-1 px-3 {{ request()->routeIs('cms.layanan.index') || request()->routeIs('cms.layanan.create') || request()->routeIs('cms.layanan.edit') ? 'active text-white fw-bold' : '' }}">
                                    <i class="bi bi-grid me-2"></i>Daftar Layanan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cms.kategori-layanan.index') }}" class="nav-link text-white-50 small py-1 px-3 {{ request()->routeIs('cms.kategori-layanan*') ? 'active text-white fw-bold' : '' }}">
                                    <i class="bi bi-diagram-2 me-2"></i>Kategori / Bidang
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cms.penanggung-jawab.index') }}" class="nav-link text-white-50 small py-1 px-3 {{ request()->routeIs('cms.penanggung-jawab*') ? 'active text-white fw-bold' : '' }}">
                                    <i class="bi bi-person-badge me-2"></i>Penanggung Jawab
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Galeri Submenu -->
                <li class="nav-item">
                    <a href="#menuGaleri" data-bs-toggle="collapse" class="nav-link text-white d-flex align-items-center justify-content-between {{ request()->routeIs('cms.galeri*') ? 'active' : 'collapsed' }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-images fs-5"></i>
                            <span>Galeri Media</span>
                        </div>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('cms.galeri*') ? 'show' : '' }} ms-3 mt-1" id="menuGaleri">
                        <ul class="nav nav-pills flex-column gap-1">
                            <li>
                                <a href="{{ route('cms.galeri.album.index') }}" class="nav-link text-white-50 small py-1 px-3 {{ request()->routeIs('cms.galeri.album*') || request()->routeIs('cms.galeri.foto*') ? 'active text-white fw-bold' : '' }}">
                                    <i class="bi bi-image me-2"></i>Foto & Album
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cms.galeri.video.index') }}" class="nav-link text-white-50 small py-1 px-3 {{ request()->routeIs('cms.galeri.video*') ? 'active text-white fw-bold' : '' }}">
                                    <i class="bi bi-youtube me-2"></i>Galeri Video
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Dokumen -->
                <li class="nav-item">
                    <a href="{{ route('cms.dokumen.index') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('cms.dokumen*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-arrow-down fs-5"></i>
                        <span>Dokumen</span>
                    </a>
                </li>

                <!-- Banner / Slider -->
                <li class="nav-item">
                    <a href="{{ route('cms.banner.index') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('cms.banner*') ? 'active' : '' }}">
                        <i class="bi bi-sliders fs-5"></i>
                        <span>Banner / Slider</span>
                    </a>
                </li>

                <!-- Profil Dinas -->
                <li class="nav-item">
                    <a href="{{ route('cms.profil.index') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('cms.profil*') ? 'active' : '' }}">
                        <i class="bi bi-building fs-5"></i>
                        <span>Profil Dinas</span>
                    </a>
                </li>

                <!-- Pesan Masuk -->
                @php $unreadCount = \App\Models\Contact::where('is_read', false)->count(); @endphp
                <li class="nav-item">
                    <a href="{{ route('cms.pesan.index') }}" class="nav-link text-white d-flex align-items-center justify-content-between {{ request()->routeIs('cms.pesan*') ? 'active' : '' }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-envelope-paper fs-5"></i>
                            <span>Pesan Masuk</span>
                        </div>
                        @if($unreadCount > 0)
                            <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>

                <!-- Super Admin Only Section -->
                @if(Auth::user() && Auth::user()->isSuperAdmin())
                    <li class="nav-header text-uppercase text-white-50 fw-bold px-3 pt-3 pb-1" style="font-size: 0.7rem; letter-spacing: 1px;">
                        Super Admin Only
                    </li>

                    <!-- User Management -->
                    <li class="nav-item">
                        <a href="{{ route('cms.user.index') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('cms.user*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill fs-5"></i>
                            <span>Manajemen User</span>
                        </a>
                    </li>

                    <!-- Role Matrix -->
                    <li class="nav-item">
                        <a href="{{ route('cms.role.index') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('cms.role*') ? 'active' : '' }}">
                            <i class="bi bi-key-fill fs-5"></i>
                            <span>Hak Akses Role</span>
                        </a>
                    </li>

                    <!-- Website Settings -->
                    <li class="nav-item">
                        <a href="{{ route('cms.pengaturan.index') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('cms.pengaturan*') ? 'active' : '' }}">
                            <i class="bi bi-gear-fill fs-5"></i>
                            <span>Pengaturan Website</span>
                        </a>
                    </li>

                    <!-- Activity Log -->
                    <li class="nav-item">
                        <a href="{{ route('cms.log-aktivitas.index') }}" class="nav-link text-white d-flex align-items-center gap-2 {{ request()->routeIs('cms.log-aktivitas*') ? 'active' : '' }}">
                            <i class="bi bi-journal-text fs-5"></i>
                            <span>Log Aktivitas</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <!-- Sidebar Footer / Logout -->
    <div class="sidebar-footer p-3 border-top border-secondary border-opacity-25">
        <form action="{{ route('cms.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar (Logout)</span>
            </button>
        </form>
    </div>
</aside>
