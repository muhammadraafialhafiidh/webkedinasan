@extends('layouts.public')

@section('title', 'Berita & Informasi — ' . \App\Models\Setting::get('nama_website'))

@section('content')

@include('partials.breadcrumb')

<div class="container py-4 mb-5">
    <div class="row g-4">
        <!-- Main News Column (8 col) -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h2 class="fw-bold text-primary-dark mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Arsip Berita & Informasi
                </h2>
                <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">Menampilkan {{ $newsList->total() }} berita</span>
            </div>

            <div class="row g-4 mb-4">
                @forelse($newsList as $news)
                    <div class="col-md-6">
                        <div class="card-custom h-100 d-flex flex-column position-relative">
                            <div class="news-thumb-wrapper" style="aspect-ratio: 16/9; overflow: hidden;">
                                <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="{{ $news->title }}" class="w-100 h-100" style="object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1534951009808-766178b47a4f?auto=format&fit=crop&w=600&q=80'">
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge-ocean">{{ $news->newsCategory->name ?? 'Informasi' }}</span>
                                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $news->published_at ? $news->published_at->format('d M Y') : '-' }}</small>
                                </div>
                                <h5 class="fw-bold mb-2 fs-6">
                                    <a href="{{ route('news.show', $news->slug) }}" class="text-dark text-decoration-none stretched-link">
                                        {{ Str::limit($news->title, 55) }}
                                    </a>
                                </h5>
                                <p class="text-muted small mb-3 flex-grow-1">
                                    {{ Str::limit(strip_tags($news->content), 90) }}
                                </p>
                                <span class="fw-bold text-primary small mt-auto d-inline-flex align-items-center gap-1">
                                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5 text-center bg-white rounded-3 border">
                        <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-newspaper fs-2 text-muted"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Berita Tidak Ditemukan</h6>
                        <p class="text-muted small mb-0">Tidak ditemukan berita sesuai kata kunci pencarian atau filter Anda.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $newsList->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Sidebar Column (4 col) -->
        <div class="col-lg-4">
            <!-- Search Box -->
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-search me-2"></i>Cari Berita</h5>
                <form action="{{ route('news.index') }}" method="GET">
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="q" class="form-control border-end-0 rounded-start-2 py-2" placeholder="Kata kunci judul..." value="{{ request('q') }}">
                        <button class="btn btn-primary px-3 rounded-end-2" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>

            <!-- Category List -->
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-tags-fill me-2"></i>Kategori Berita</h5>
                <div class="list-group list-group-flush">
                    <a href="{{ route('news.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2.5 {{ !request('kategori') ? 'active fw-bold bg-primary border-primary rounded-2 text-white' : 'border-0 rounded-2' }}">
                        Semua Kategori
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('news.index', ['kategori' => $cat->slug]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2.5 mt-1 {{ request('kategori') == $cat->slug ? 'active fw-bold bg-primary border-primary rounded-2 text-white' : 'border-0 rounded-2' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="badge {{ request('kategori') == $cat->slug ? 'bg-white text-primary' : 'bg-light text-dark border' }} rounded-pill">{{ $cat->news_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Popular News -->
            <div class="card-custom p-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-star-fill me-2 text-warning"></i>Berita Terbaru Lainnya</h5>
                <div class="d-flex flex-column gap-3">
                    @foreach($popularNews as $pop)
                        <div class="d-flex gap-3 align-items-start position-relative">
                            <img src="{{ asset('storage/' . $pop->thumbnail) }}" alt="{{ $pop->title }}" class="rounded-2" style="width: 70px; height: 55px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1534951009808-766178b47a4f?auto=format&fit=crop&w=150&q=80'">
                            <div>
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem;"><i class="bi bi-calendar3 me-1"></i>{{ $pop->published_at ? $pop->published_at->format('d M Y') : '' }}</small>
                                <a href="{{ route('news.show', $pop->slug) }}" class="text-dark text-decoration-none fw-semibold small lh-sm stretched-link">
                                    {{ Str::limit($pop->title, 45) }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
