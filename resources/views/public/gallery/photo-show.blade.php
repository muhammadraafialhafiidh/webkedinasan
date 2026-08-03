@extends('layouts.public')

@section('title', $album->name . ' — Galeri Foto')

@section('content')

@include('partials.breadcrumb')

<div class="container py-4 mb-5">
    <!-- Album Header & Navigation -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge-ocean"><i class="bi bi-folder-fill me-1"></i>Album Foto</span>
                <span class="badge bg-light text-primary border px-3 py-1 rounded-pill small fw-semibold">
                    {{ $album->photos->count() }} Foto Dokumentasi
                </span>
            </div>
            <h1 class="fw-extrabold text-primary mb-1 display-6" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                {{ $album->name }}
            </h1>
            @if($album->description)
                <p class="text-muted mb-0">{!! nl2br(e(strip_tags($album->description))) !!}</p>
            @endif
        </div>
        <div>
            <a href="{{ route('gallery.photo') }}" class="btn btn-outline-secondary rounded-pill fw-bold btn-sm shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Album
            </a>
        </div>
    </div>

    <!-- Photos Grid -->
    <div class="row g-4 mb-5">
        @forelse($album->photos as $photo)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="card-custom overflow-hidden position-relative h-100">
                    <a href="{{ asset('storage/' . $photo->image) }}" class="glightbox" data-gallery="album-photos" data-title="{{ strip_tags($photo->title) }}" data-description="{{ strip_tags($photo->description) }}">
                        <div class="news-thumb-wrapper" style="aspect-ratio: 4/3; overflow: hidden; position: relative;">
                            <img src="{{ asset('storage/' . $photo->image) }}" alt="{{ $photo->title }}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.4s ease;" onerror="this.src='https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=600&q=80'">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="btn btn-sm btn-dark bg-opacity-75 rounded-circle p-1" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-arrows-angle-expand text-white small"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                    <div class="p-3 bg-white text-center">
                        <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $photo->title }}">{{ $photo->title ?: $album->name }}</h6>
                        @if($photo->description)
                            <small class="text-muted d-block text-truncate mt-1" style="font-size: 0.75rem;">{{ strip_tags($photo->description) }}</small>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-light rounded-3">
                <i class="bi bi-images fs-1 text-muted"></i>
                <p class="mt-2 text-muted">Belum ada foto yang dimasukkan ke dalam album ini.</p>
                <a href="{{ route('gallery.photo') }}" class="btn btn-primary rounded-pill btn-sm fw-bold mt-2">Lihat Album Lain</a>
            </div>
        @endforelse
    </div>

    <!-- Other Albums List -->
    @if($otherAlbums->count() > 0)
        <div class="pt-4 border-top">
            <h4 class="fw-bold text-primary mb-4" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                <i class="bi bi-folder2-open me-2 text-warning"></i>Album Foto Lainnya
            </h4>
            <div class="row g-4">
                @foreach($otherAlbums as $other)
                    @php
                        $otherCover = 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80';
                        if (!empty($other->cover)) {
                            $otherCover = asset('storage/' . $other->cover);
                        } elseif ($other->photos->count() > 0) {
                            $otherCover = asset('storage/' . $other->photos->first()->image);
                        }
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="card-custom h-100 p-3 d-flex align-items-center gap-3 position-relative">
                            <img src="{{ $otherCover }}" alt="{{ $other->name }}" class="rounded" style="width: 90px; height: 70px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=200&q=80'">
                            <div class="flex-grow-1">
                                <span class="badge bg-light text-primary border small mb-1">{{ $other->photos_count }} Foto</span>
                                <h6 class="fw-bold mb-1">
                                    <a href="{{ route('gallery.photo.show', $other->id) }}" class="text-dark text-decoration-none stretched-link">
                                        {{ Str::limit($other->name, 40) }}
                                    </a>
                                </h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@endsection
