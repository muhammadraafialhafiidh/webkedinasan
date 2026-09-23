@extends('layouts.public')

@section('title', \App\Models\Setting::get('nama_website', 'Portal Informasi Dinas Perikanan'))

@push('styles')
<style>
    /* Hero Slider */
    .hero-slider-section {
        position: relative;
        overflow: hidden;
        width: 100%;
    }
    .hero-slide-item {
        position: relative;
        min-height: 540px;
        height: auto;
        padding: 60px 0 70px 0;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }
    .hero-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(0, 42, 92, 0.88) 0%, rgba(0, 119, 182, 0.72) 100%);
        z-index: 1;
    }
    .hero-content {
        position: relative;
        z-index: 2;
        color: white;
        width: 100%;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Hero Typography & Elements */
    .hero-content h1 {
        font-size: 2.75rem !important;
        line-height: 1.2 !important;
        letter-spacing: -0.02em;
        word-break: break-word;
    }

    .hero-content .lead {
        font-size: 1.25rem !important;
        line-height: 1.55;
        max-width: 600px;
    }

    .hero-slider-section .swiper-pagination {
        bottom: 16px !important;
        z-index: 3;
    }

    /* Tablet (768px – 1199px) */
    @media (max-width: 1199.98px) and (min-width: 768px) {
        .hero-slide-item {
            min-height: 480px;
            padding: 50px 0 65px 0;
        }
        .hero-content h1 {
            font-size: 2.25rem !important;
            line-height: 1.25 !important;
        }
        .hero-content .lead {
            font-size: 1.1rem !important;
            line-height: 1.5;
            margin-bottom: 1.25rem !important;
        }
    }

    /* Mobile (<768px) */
    @media (max-width: 767.98px) {
        .hero-slide-item {
            min-height: 440px;
            height: auto;
            padding: 44px 0 56px 0;
        }
        .hero-content h1 {
            font-size: 1.625rem !important;
            line-height: 1.3 !important;
            margin-bottom: 0.85rem !important;
            word-break: break-word;
        }
        .hero-content .lead {
            font-size: 0.95rem !important;
            line-height: 1.5 !important;
            margin-bottom: 1.25rem !important;
            max-width: 100% !important;
        }
        .hero-content .badge {
            font-size: 0.7rem !important;
            padding: 0.35rem 0.75rem !important;
            margin-bottom: 0.75rem !important;
        }
        .hero-content .d-flex.gap-3 {
            gap: 0.75rem !important;
        }
        .hero-content .btn-lg {
            padding: 0.65rem 1.25rem !important;
            font-size: 0.9rem !important;
        }
    }

    /* Small Mobile (<480px) */
    @media (max-width: 479.98px) {
        .hero-slide-item {
            min-height: 420px;
            height: auto;
            padding: 36px 0 52px 0;
        }
        .hero-content h1 {
            font-size: 1.375rem !important;
            line-height: 1.35 !important;
            margin-bottom: 0.75rem !important;
        }
        .hero-content .lead {
            font-size: 0.875rem !important;
            line-height: 1.45 !important;
            margin-bottom: 1rem !important;
        }
        .hero-content .d-flex.gap-3 {
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100% !important;
            gap: 0.625rem !important;
        }
        .hero-content .btn {
            width: 100% !important;
            text-align: center !important;
            justify-content: center !important;
            font-size: 0.875rem !important;
            padding: 0.6rem 1rem !important;
        }
    }

    /* Stats Card */
    .stat-card {
        background: var(--surface);
        border-radius: var(--radius-md);
        padding: 24px;
        border-left: 5px solid var(--gold);
        box-shadow: var(--shadow-sm);
        border-top: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        transition: transform var(--transition), box-shadow var(--transition);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    .stat-number {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 2.25rem;
        color: var(--primary);
        line-height: 1;
        letter-spacing: -0.03em;
    }

    /* Section Headers */
    .section-title-wrap {
        margin-bottom: 40px;
    }
    .section-title {
        font-weight: 800;
        font-size: 2rem;
        color: var(--primary-dark);
        position: relative;
        display: inline-block;
    }
    .section-title::after {
        content: '';
        display: block;
        width: 50%;
        height: 4px;
        background: var(--gold);
        margin-top: 8px;
        border-radius: 2px;
    }

    /* News Card Aspect Ratio */
    .news-thumb-wrapper {
        aspect-ratio: 16 / 9;
        overflow: hidden;
        position: relative;
    }
    .news-thumb-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .card-custom:hover .news-thumb-wrapper img {
        transform: scale(1.06);
    }

    /* Service Icon Box */
    .service-icon-box {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(0, 180, 216, 0.15) 0%, rgba(0, 63, 136, 0.1) 100%);
        color: var(--ocean);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 18px;
        transition: transform var(--transition);
    }
    .card-custom:hover .service-icon-box {
        transform: scale(1.1) rotate(5deg);
        background: var(--primary);
        color: var(--surface);
    }
</style>
@endpush

@section('content')

<!-- Hero Slider -->
<section class="hero-slider-section">
    <div class="swiper heroSwiper">
        <div class="swiper-wrapper">
            @forelse($banners as $banner)
                <div class="swiper-slide hero-slide-item" style="background-image: url('{{ $banner->image_url }}');">
                    <div class="hero-overlay"></div>
                    <div class="container hero-content py-4">
                        <div class="row">
                            <div class="col-lg-8 col-xl-7">
                                <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2 text-uppercase rounded-pill" style="letter-spacing: 1px; font-size: 0.75rem;">
                                    <i class="bi bi-shield-check me-1"></i> Portal Resmi Dinas
                                </span>
                                <h1 class="display-5 fw-extrabold text-white mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.15;">
                                    {{ $banner->title ?? \App\Models\Setting::get('nama_website') }}
                                </h1>
                                <p class="lead text-white-50 mb-4 fs-5" style="max-width: 600px;">
                                    {{ \App\Models\Setting::get('tagline', 'Kabupaten Banyumas') }}
                                </p>
                                <div class="d-flex gap-3 flex-wrap align-items-center">
                                    @if($banner->target_url)
                                        @if($banner->link_type === 'berita')
                                            <a href="{{ $banner->target_url }}" class="btn btn-gold btn-lg shadow-lg">
                                                <i class="bi bi-newspaper me-2"></i> Baca Berita
                                            </a>
                                        @elseif($banner->link_type === 'pelayanan')
                                            <a href="{{ $banner->target_url }}" class="btn btn-gold btn-lg shadow-lg">
                                                <i class="bi bi-grid-fill me-2"></i> Buka Layanan
                                            </a>
                                        @elseif($banner->link_type === 'external')
                                            <a href="{{ $banner->target_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-gold btn-lg shadow-lg">
                                                <i class="bi bi-box-arrow-up-right me-2"></i> Kunjungi Tautan
                                            </a>
                                        @else
                                            <a href="{{ $banner->target_url }}" class="btn btn-gold btn-lg shadow-lg">
                                                <i class="bi bi-arrow-right-circle-fill me-2"></i> Lihat Detail
                                            </a>
                                        @endif
                                        <a href="{{ route('profile') }}" class="btn btn-outline-light btn-lg fw-bold px-4 rounded-pill">
                                            <i class="bi bi-info-circle me-2"></i> Profil Dinas
                                        </a>
                                    @else
                                        <a href="{{ route('service.index') }}" class="btn btn-gold btn-lg shadow-lg">
                                            <i class="bi bi-grid-fill me-2"></i> Lihat Layanan Publik
                                        </a>
                                        <a href="{{ route('profile') }}" class="btn btn-outline-light btn-lg fw-bold px-4 rounded-pill">
                                            <i class="bi bi-info-circle me-2"></i> Profil Dinas
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide hero-slide-item" style="background-color: var(--primary-dark);">
                    <div class="hero-overlay"></div>
                    <div class="container hero-content py-4">
                        <div class="row">
                            <div class="col-lg-8">
                                <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2 text-uppercase rounded-pill" style="letter-spacing: 1px;">Portal Resmi</span>
                                <h1 class="display-5 fw-extrabold text-white mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                    Selamat Datang di Portal Informasi Dinas Perikanan
                                </h1>
                                <p class="lead text-white-50 mb-4 fs-5">Melayani dengan Profesional, Membangun Perikanan Berkelanjutan</p>
                                <a href="{{ route('service.index') }}" class="btn btn-gold btn-lg shadow-lg">
                                    <i class="bi bi-grid-fill me-2"></i> Lihat Layanan Publik
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next text-white d-none d-md-flex"></div>
        <div class="swiper-button-prev text-white d-none d-md-flex"></div>
    </div>
</section>

<!-- Website Statistics Section -->
<section class="py-5" style="background-color: var(--background);">
    <div class="container">
        <div class="text-center section-title-wrap mb-4">
            <span class="text-uppercase text-primary fw-bold small" style="letter-spacing: 1.5px;">Statistik Kunjungan</span>
            <h2 class="section-title mx-auto">Statistik Website</h2>
            <p class="text-muted small mb-0 mt-1">Pantau jumlah kunjungan Portal Informasi Dinas Perikanan.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="stat-card p-4 rounded-4 bg-white border shadow-sm h-100 text-center">
                    <div class="stat-number display-6 fw-extrabold text-primary mb-1 counter-number" data-target="{{ $visitorStats['hari_ini'] ?? 0 }}">0</div>
                    <div class="text-muted fw-semibold small">Hari Ini</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="stat-card p-4 rounded-4 bg-white border shadow-sm h-100 text-center">
                    <div class="stat-number display-6 fw-extrabold text-ocean mb-1 counter-number" data-target="{{ $visitorStats['minggu_ini'] ?? 0 }}">0</div>
                    <div class="text-muted fw-semibold small">Minggu Ini</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="stat-card p-4 rounded-4 bg-white border shadow-sm h-100 text-center">
                    <div class="stat-number display-6 fw-extrabold text-teal mb-1 counter-number" data-target="{{ $visitorStats['bulan_ini'] ?? 0 }}">0</div>
                    <div class="text-muted fw-semibold small">Bulan Ini</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="stat-card p-4 rounded-4 bg-white border shadow-sm h-100 text-center">
                    <div class="stat-number display-6 fw-extrabold text-dark mb-1 counter-number" data-target="{{ $visitorStats['total'] ?? 0 }}">0</div>
                    <div class="text-muted fw-semibold small">Total Pengunjung</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end section-title-wrap flex-wrap gap-3">
            <div>
                <span class="text-uppercase text-primary fw-bold small" style="letter-spacing: 1.5px;">Kabar Terkini</span>
                <h2 class="section-title mb-0">Berita & Informasi Dinas</h2>
            </div>
            <a href="{{ route('news.index') }}" class="btn btn-outline-primary fw-bold rounded-pill px-4">
                Lihat Semua Berita <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($latestNews as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="card-custom h-100 d-flex flex-column position-relative">
                        <div class="news-thumb-wrapper">
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" onerror="this.src='https://images.unsplash.com/photo-1534951009808-766178b47a4f?auto=format&fit=crop&w=600&q=80'">
                        </div>
                        <div class="p-4 d-flex flex-column flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge-ocean">{{ $item->newsCategory->name ?? 'Informasi' }}</span>
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</small>
                            </div>
                            <h5 class="fw-bold mb-2">
                                <a href="{{ route('news.show', $item->slug) }}" class="text-dark text-decoration-none hover-primary stretched-link">
                                    {{ Str::limit($item->title, 60) }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-4 flex-grow-1">
                                {{ Str::limit(strip_tags($item->content), 100) }}
                            </p>
                            <span class="fw-bold text-primary mt-auto d-inline-flex align-items-center gap-1">
                                Selengkapnya <i class="bi bi-chevron-right"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">Belum ada berita dipublikasikan.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Services Section -->
<section class="py-5" style="background-color: var(--background);">
    <div class="container">
        <div class="text-center section-title-wrap mb-5">
            <span class="text-uppercase text-primary fw-bold small" style="letter-spacing: 1.5px;">Standar Pelayanan</span>
            <h2 class="section-title mx-auto">Layanan per Bidang Dinas</h2>
        </div>

        <div class="row g-4">
            @forelse($serviceCategories as $category)
                <div class="col-lg-4 col-md-6">
                    <div class="card-custom h-100 p-4 d-flex flex-column">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="service-icon-box flex-shrink-0">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                            <div class="min-w-0 flex-grow-1">
                                <span class="badge bg-light text-primary border small rounded-pill px-2.5 py-1 mb-1">
                                    {{ $category->services->count() }} Layanan
                                </span>
                                <h5 class="fw-bold text-primary-dark mb-0 fs-6 text-break-word" style="font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.4;">
                                    {{ $category->name }}
                                </h5>
                            </div>
                        </div>

                        @if($category->description)
                            <p class="text-muted small mb-3 text-break-word" style="line-height: 1.55;">
                                {{ Str::limit(strip_tags($category->description), 90) }}
                            </p>
                        @endif

                        <div class="border-top pt-3 flex-grow-1">
                            <span class="fw-bold text-muted small text-uppercase d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">Daftar Layanan:</span>
                            <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                                @forelse($category->services as $service)
                                    <li>
                                        <a href="{{ route('service.show', $service->slug) }}" class="text-dark text-decoration-none d-flex align-items-start gap-2 hover-primary transition-all">
                                            <i class="bi bi-check2-circle text-primary small mt-1 flex-shrink-0"></i>
                                            <span class="fw-medium lh-sm min-w-0 flex-grow-1 text-break-word">{{ $service->title }}</span>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-muted small fst-italic">Belum ada layanan tersedia.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('service.index', ['kategori' => $category->slug]) }}" class="btn btn-sm btn-outline-primary rounded-pill fw-bold w-100 py-2">
                                Lihat Semua Layanan Bidang Ini <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">Belum ada data bidang layanan.</div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('service.index') }}" class="btn btn-primary-custom btn-lg rounded-pill shadow-lg">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i> Lihat Seluruh Layanan Publik
            </a>
        </div>
    </div>
</section>

<!-- Photo Gallery Preview Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end section-title-wrap flex-wrap gap-3">
            <div>
                <span class="text-uppercase text-primary fw-bold small" style="letter-spacing: 1.5px;">Dokumentasi</span>
                <h2 class="section-title mb-0">Galeri Foto Kegiatan</h2>
            </div>
            <a href="{{ route('gallery.photo') }}" class="btn btn-outline-primary fw-bold rounded-pill px-4">
                Lihat Galeri Foto <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($latestPhotos as $photo)
                <div class="col-lg-4 col-md-6">
                    <div class="card-custom overflow-hidden position-relative h-100 d-flex flex-column">
                        @if(($photo->source_type ?? 'upload') === 'instagram')
                            <!-- Media Container 4:3 Instagram -->
                            <a href="{{ $photo->external_url }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none" title="Buka postingan di Instagram">
                                <div class="news-thumb-wrapper position-relative" 
                                     style="aspect-ratio: 4/3; overflow: hidden; background: linear-gradient(135deg, #405DE6 0%, #5851DB 25%, #833AB4 50%, #C13584 75%, #E1306C 100%);">
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-white text-center position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 shadow-sm" style="width: 48px; height: 48px; background: rgba(255,255,255,0.22); backdrop-filter: blur(6px); border: 2px solid rgba(255,255,255,0.4);">
                                            <i class="bi bi-instagram fs-3 text-white"></i>
                                        </div>
                                        <span class="badge bg-white text-dark rounded-pill px-2.5 py-1 small fw-bold shadow-sm" style="font-size: 0.7rem;">
                                            <i class="bi bi-instagram me-1 text-danger"></i>Postingan Instagram
                                        </span>
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="btn btn-sm btn-dark bg-opacity-75 rounded-circle p-1" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-box-arrow-up-right text-white small"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="p-3 bg-white d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <h6 class="fw-bold mb-1 text-truncate" title="{{ strip_tags($photo->title) }}">{{ strip_tags($photo->title) ?: 'Dokumentasi Instagram' }}</h6>
                                    @if($photo->gallery_album_id)
                                        <a href="{{ route('gallery.photo.show', $photo->gallery_album_id) }}" class="text-muted text-decoration-none small hover-primary d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-folder-fill text-warning"></i><span>{{ $photo->album->name ?? 'Album Dinas' }}</span>
                                        </a>
                                    @else
                                        <small class="text-muted"><i class="bi bi-folder me-1"></i>Album Dinas</small>
                                    @endif
                                </div>
                                <div class="mt-2 pt-1 border-top">
                                    <a href="{{ $photo->external_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger rounded-pill w-100 py-1" style="font-size: 0.75rem;">
                                        <i class="bi bi-instagram me-1"></i> Buka di Instagram
                                    </a>
                                </div>
                            </div>
                        @else
                            <a href="{{ asset('storage/' . $photo->image) }}" class="glightbox" data-gallery="home-gallery" data-title="{{ strip_tags($photo->title) }}">
                                <img src="{{ asset('storage/' . $photo->image) }}" alt="{{ $photo->title }}" class="w-100" style="aspect-ratio: 4/3; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=600&q=80'">
                            </a>
                            <div class="p-3 bg-white">
                                <h6 class="fw-bold mb-1 text-truncate">{{ strip_tags($photo->title) }}</h6>
                                @if($photo->gallery_album_id)
                                    <a href="{{ route('gallery.photo.show', $photo->gallery_album_id) }}" class="text-muted text-decoration-none small hover-primary d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-folder-fill text-warning"></i><span>{{ $photo->album->name ?? 'Album Dinas' }}</span>
                                    </a>
                                @else
                                    <small class="text-muted"><i class="bi bi-folder me-1"></i>Album Dinas</small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">Belum ada galeri foto.</div>
            @endforelse
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swiper !== 'undefined') {
            const heroSwiper = new Swiper('.heroSwiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        }

        // Counter number animation for website statistics
        const counters = document.querySelectorAll('.counter-number');
        if (counters.length > 0 && typeof IntersectionObserver !== 'undefined') {
            const observer = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const target = parseInt(el.getAttribute('data-target') || '0', 10);
                        if (target === 0) {
                            el.innerText = '0';
                            obs.unobserve(el);
                            return;
                        }

                        let count = 0;
                        const duration = 1000; // ms
                        const increment = Math.max(1, Math.ceil(target / (duration / 16)));

                        const updateCount = function () {
                            count += increment;
                            if (count >= target) {
                                el.innerText = new Intl.NumberFormat('id-ID').format(target);
                            } else {
                                el.innerText = new Intl.NumberFormat('id-ID').format(count);
                                requestAnimationFrame(updateCount);
                            }
                        };
                        updateCount();
                        obs.unobserve(el);
                    }
                });
            }, { threshold: 0.1 });

            counters.forEach(function (c) { observer.observe(c); });
        }
    });
</script>
@endpush
