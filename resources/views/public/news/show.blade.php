@extends('layouts.public')

@section('title', $news->title . ' — ' . \App\Models\Setting::get('nama_website'))
@section('meta_description', Str::limit(strip_tags($news->content), 150))

@section('content')

@include('partials.breadcrumb')

<div class="container py-4 mb-5">
    <div class="row g-4">
        <!-- Main Content (8 col) -->
        <div class="col-lg-8">
            <article class="bg-white p-4 p-md-5 rounded-4 border shadow-sm">
                <div class="d-flex align-items-center gap-2.5 mb-3 flex-wrap">
                    <span class="badge-ocean">{{ $news->newsCategory->name ?? 'Informasi' }}</span>
                    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ $news->published_at ? $news->published_at->format('d F Y') : '' }}</span>
                    <span class="text-muted small ms-auto"><i class="bi bi-eye me-1"></i>{{ number_format($news->views_count ?? 0, 0, ',', '.') }} Dilihat</span>
                </div>

                <h1 class="fw-bold text-primary-dark mb-4" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.1rem; line-height: 1.3; letter-spacing: -0.02em;">
                    {{ $news->title }}
                </h1>

                @if($news->thumbnail)
                    <div class="mb-4 rounded-3 overflow-hidden shadow-sm">
                        <a href="{{ asset('storage/' . $news->thumbnail) }}" class="glightbox" data-title="{{ strip_tags($news->title) }}">
                            <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="{{ $news->title }}" class="w-100" style="max-height: 460px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1534951009808-766178b47a4f?auto=format&fit=crop&w=1200&q=80'">
                        </a>
                    </div>
                @endif

                <div class="news-html-content text-dark" style="font-size: 1.05rem; line-height: 1.85;">
                    {!! $news->content !!}
                </div>

                <hr class="my-4.5 opacity-25">

                <!-- Social Share -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <span class="fw-bold text-muted small"><i class="bi bi-share-fill me-1.5"></i>Bagikan berita ini:</span>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="bi bi-facebook me-1"></i>Facebook</a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($news->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3"><i class="bi bi-twitter-x me-1"></i>X</a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' - ' . url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3"><i class="bi bi-whatsapp me-1"></i>WhatsApp</a>
                    </div>
                </div>
            </article>

            <!-- Related News Section -->
            @if($relatedNews->count() > 0)
                <div class="mt-5">
                    <h4 class="fw-bold text-primary-dark mb-4" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Berita Terkait
                    </h4>
                    <div class="row g-3">
                        @foreach($relatedNews as $rel)
                            <div class="col-md-6">
                                <div class="card-custom h-100 p-3 d-flex flex-column position-relative">
                                    <div class="d-flex gap-3 align-items-start">
                                        <img src="{{ asset('storage/' . $rel->thumbnail) }}" alt="{{ $rel->title }}" class="rounded-2" style="width: 80px; height: 60px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1534951009808-766178b47a4f?auto=format&fit=crop&w=200&q=80'">
                                        <div>
                                            <span class="badge bg-light text-primary border small mb-1">{{ $rel->newsCategory->name ?? '' }}</span>
                                            <h6 class="fw-bold mb-1 fs-6">
                                                <a href="{{ route('news.show', $rel->slug) }}" class="text-dark text-decoration-none stretched-link">
                                                    {{ Str::limit($rel->title, 45) }}
                                                </a>
                                            </h6>
                                            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $rel->published_at ? $rel->published_at->format('d M Y') : '' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar (4 col) -->
        <div class="col-lg-4">
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3">Tentang Dinas</h5>
                <p class="small text-muted mb-3 leading-relaxed">
                    {{ strip_tags(\App\Models\Setting::get('deskripsi')) }}
                </p>
                <a href="{{ route('profile') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-bold w-100 py-2">
                    Pelajari Selengkapnya
                </a>
            </div>

            <div class="card-custom p-4">
                <h5 class="fw-bold text-primary mb-3">Layanan Informasi</h5>
                <p class="small text-muted mb-3 leading-relaxed">Membutuhkan informasi publik lebih lanjut atau ingin menyampaikan aspirasi?</p>
                <a href="{{ route('contact.index') }}" class="btn btn-gold btn-sm rounded-pill fw-bold text-dark w-100 py-2">
                    <i class="bi bi-chat-dots-fill me-1.5"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
