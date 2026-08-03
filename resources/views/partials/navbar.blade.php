<nav class="navbar navbar-expand-xl navbar-dark sticky-top shadow-sm py-2.5" style="background-color: var(--primary); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2.5" href="{{ route('home') }}">
            @php
                $logo = \App\Models\Setting::get('logo');
            @endphp
            @if($logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo))
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo Instansi" style="height: 44px; max-width: 170px; object-fit: contain;">
            @else
                <div class="bg-warning bg-opacity-20 text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi bi-water fs-4 text-warning"></i>
                </div>
            @endif
            <div>
                <span class="fw-bold d-block lh-1 text-white" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.15rem; letter-spacing: -0.01em;">
                    {{ \App\Models\Setting::get('nama_website', 'Portal Informasi Dinas Perikanan') }}
                </span>
                <small class="text-white-50 d-none d-sm-block mt-0.5" style="font-size: 0.725rem; letter-spacing: 0.02em;">
                    {{ \App\Models\Setting::get('tagline', 'Kabupaten Banyumas') }}
                </small>
            </div>
        </a>

        <button class="navbar-toggler border-0 p-2 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-xl-0 align-items-xl-center gap-xl-1 py-2 py-xl-0">
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-2 transition-all {{ request()->routeIs('home') ? 'active fw-bold text-white bg-white bg-opacity-10' : 'text-white-50' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-2 transition-all {{ request()->routeIs('profile*') ? 'active fw-bold text-white bg-white bg-opacity-10' : 'text-white-50' }}" href="{{ route('profile') }}">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-2 transition-all {{ request()->routeIs('news*') ? 'active fw-bold text-white bg-white bg-opacity-10' : 'text-white-50' }}" href="{{ route('news.index') }}">Berita</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-2 transition-all {{ request()->routeIs('service*') ? 'active fw-bold text-white bg-white bg-opacity-10' : 'text-white-50' }}" href="{{ route('service.index') }}">Layanan</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3 rounded-2 transition-all {{ request()->routeIs('gallery*') ? 'active fw-bold text-white bg-white bg-opacity-10' : 'text-white-50' }}" href="#" id="galleryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Galeri
                    </a>
                    <ul class="dropdown-menu border-0 shadow-lg p-2 mt-2 rounded-3" aria-labelledby="galleryDropdown">
                        <li><a class="dropdown-item rounded-2 py-2" href="{{ route('gallery.photo') }}"><i class="bi bi-images me-2 text-primary"></i>Galeri Foto</a></li>
                        <li><a class="dropdown-item rounded-2 py-2" href="{{ route('gallery.video') }}"><i class="bi bi-youtube me-2 text-danger"></i>Galeri Video</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-2 transition-all {{ request()->routeIs('document*') ? 'active fw-bold text-white bg-white bg-opacity-10' : 'text-white-50' }}" href="{{ route('document.index') }}">Dokumen</a>
                </li>
                <li class="nav-item ms-xl-2 mt-2 mt-xl-0">
                    <a class="btn btn-gold btn-sm fw-bold px-3.5 py-2 rounded-pill d-inline-flex align-items-center gap-1.5" href="{{ route('contact.index') }}">
                        <i class="bi bi-telephone-fill"></i>
                        <span>Kontak Kami</span>
                    </a>
                </li>
            </ul>

            <!-- Info Topbar untuk tampilan mobile & laptop kecil (<1200px) -->
            <div class="d-xl-none mt-3 pt-3 border-top border-white border-opacity-10">
                <div class="d-flex flex-column gap-2.5 px-2 pb-2 small text-white-50">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone-fill text-warning"></i>
                        <span>{{ \App\Models\Setting::get('telepon', '(0281) 123456') }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-fill text-warning"></i>
                        <span>{{ \App\Models\Setting::get('email', 'info@perikanan.go.id') }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock-fill text-warning"></i>
                        <span>{{ \App\Models\Setting::get('jam_operasional', 'Senin–Jumat: 08.00–16.00 WIB') }}</span>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('cms.login') }}" class="btn btn-outline-light btn-sm w-100 rounded-pill d-inline-flex align-items-center justify-content-center gap-2 py-1.5 fw-semibold">
                            <i class="bi bi-person-lock"></i>
                            <span>CMS Login</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
