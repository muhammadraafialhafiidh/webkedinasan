@extends('layouts.public')

@section('title', 'Galeri Foto — ' . \App\Models\Setting::get('nama_website'))

@section('content')

@include('partials.breadcrumb')

<div class="container py-4 mb-5">
    <div class="text-center mb-5">
        <span class="text-uppercase text-primary fw-bold small" style="letter-spacing: 1.5px;">Dokumentasi Kegiatan</span>
        <h1 class="fw-extrabold text-primary-dark display-6 mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">Album Galeri Foto Dinas</h1>
        <p class="text-muted" style="max-width: 650px; margin: 0 auto;">Pilih album di bawah ini untuk melihat dokumentasi foto program dan kegiatan dinas</p>
    </div>

    <div class="row g-4">
        @forelse($albums as $album)
            @php
                $coverUrl = 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=600&q=80';
                if (!empty($album->cover)) {
                    $coverUrl = asset('storage/' . $album->cover);
                } elseif ($album->photos->count() > 0) {
                    $coverUrl = asset('storage/' . $album->photos->first()->image);
                }
            @endphp
            <div class="col-lg-4 col-md-6">
                <div class="card-custom h-100 d-flex flex-column position-relative">
                    <div class="news-thumb-wrapper" style="aspect-ratio: 16/10; overflow: hidden; position: relative;">
                        <img src="{{ $coverUrl }}" alt="{{ $album->name }}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.4s ease;" onerror="this.src='https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=600&q=80'">
                        <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-3 px-3 py-1.5 rounded-pill small fw-semibold shadow-sm backdrop-blur">
                            <i class="bi bi-images me-1 text-warning"></i>{{ $album->photos_count ?? $album->photos->count() }} Foto
                        </span>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <h5 class="fw-bold mb-2 fs-5">
                            <a href="{{ route('gallery.photo.show', $album->id) }}" class="text-dark text-decoration-none hover-primary stretched-link">
                                {{ $album->name }}
                            </a>
                        </h5>
                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.65;">
                            {{ Str::limit(strip_tags($album->description), 90) ?: 'Dokumentasi foto kegiatan dinas.' }}
                        </p>
                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <span class="small text-muted fw-semibold">
                                <i class="bi bi-calendar3 me-1"></i>{{ $album->created_at ? $album->created_at->format('d M Y') : '-' }}
                            </span>
                            <span class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3">
                                Buka Album <i class="bi bi-chevron-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-white rounded-3 border">
                <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                    <i class="bi bi-images fs-2 text-muted"></i>
                </div>
                <h6 class="fw-bold text-dark">Album Foto Kosong</h6>
                <p class="text-muted small mb-0">Belum ada album galeri foto yang dipublikasikan.</p>
            </div>
        @endforelse
    </div>

    @if($albums->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $albums->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection
