@extends('layouts.public')

@section('title', \App\Models\Setting::get('nama_website', 'Portal Informasi Dinas Perikanan'))

@push('styles')
<style>
    /* Hero Slider */
    .hero-slider-section {
        position: relative;
        overflow: hidden;
    }
    .hero-slide-item {
        position: relative;
        height: 540px;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
    }
    @media (max-width: 768px) {
        .hero-slide-item { height: 380px; }
    }
    .hero-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(0, 42, 92, 0.88) 0%, rgba(0, 119, 182, 0.72) 100%);
    }
    .hero-content {
        position: relative;
        z-index: 2;
        color: white;
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
                <div class="swiper-slide hero-slide-item" style="background-image: url('{{ asset('storage/' . $banner->image) }}');">
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
                                    <a href="{{ route('service.index') }}" class="btn btn-gold btn-lg shadow-lg">
                                        <i class="bi bi-grid-fill me-2"></i> Lihat Layanan Publik
                                    </a>
                                    <a href="{{ route('profile') }}" class="btn btn-outline-light btn-lg fw-bold px-4 rounded-pill">
                                        <i class="bi bi-info-circle me-2"></i> Profil Dinas
                                    </a>
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
                            <div>
                                <span class="badge bg-light text-primary border small rounded-pill px-2.5 py-1 mb-1">
                                    {{ $category->services->count() }} Layanan
                                </span>
                                <h5 class="fw-bold text-primary-dark mb-0 fs-6" style="font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.4;">
                                    {{ $category->name }}
                                </h5>
                            </div>
                        </div>

                        @if($category->description)
                            <p class="text-muted small mb-3" style="line-height: 1.55;">
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
                                            <span class="fw-medium lh-sm">{{ $service->title }}</span>
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
                    <div class="card-custom overflow-hidden position-relative h-100">
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
