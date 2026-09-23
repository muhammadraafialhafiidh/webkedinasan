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
            <h1 class="fw-extrabold text-primary mb-1 display-6 text-break-word" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                {{ $album->name }}
            </h1>
            @if($album->description)
                <p class="text-muted mb-0 text-break-word">{!! nl2br(e(strip_tags($album->description))) !!}</p>
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
                <div class="card-custom overflow-hidden position-relative h-100 d-flex flex-column">
                    @if(($photo->source_type ?? 'upload') === 'instagram')
                        <!-- Media Container 4:3 Instagram (Tanpa Iframe, Desain Card Visual Terstandarisasi) -->
                        <div class="news-thumb-wrapper position-relative cursor-pointer js-ig-photo-trigger" 
                             style="aspect-ratio: 4/3; overflow: hidden; background: linear-gradient(135deg, #405DE6 0%, #5851DB 25%, #833AB4 50%, #C13584 75%, #E1306C 100%); cursor: pointer;"
                             data-title="{{ $photo->title ?: $album->name }}"
                             data-desc="{{ strip_tags($photo->description ?? '') }}"
                             data-url="{{ $photo->external_url }}"
                             role="button" tabindex="0" title="Klik untuk membuka detail postingan Instagram">
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
                        <div class="p-3 bg-white text-center flex-grow-1 d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $photo->title }}">{{ $photo->title ?: $album->name }}</h6>
                                @if($photo->description)
                                    <small class="text-muted d-block text-truncate mt-1" style="font-size: 0.75rem;">{{ strip_tags($photo->description) }}</small>
                                @else
                                    <small class="text-muted d-block text-truncate mt-1" style="font-size: 0.75rem;">Dokumentasi Instagram</small>
                                @endif
                            </div>
                            <div class="mt-2 pt-1 border-top">
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill w-100 py-1 js-ig-photo-trigger" style="font-size: 0.75rem;"
                                        data-title="{{ $photo->title ?: $album->name }}"
                                        data-desc="{{ strip_tags($photo->description ?? '') }}"
                                        data-url="{{ $photo->external_url }}">
                                    <i class="bi bi-instagram me-1"></i> Buka di Instagram
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Media Container 4:3 Upload Foto Lokal -->
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
                        <div class="p-3 bg-white text-center flex-grow-1 d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $photo->title }}">{{ $photo->title ?: $album->name }}</h6>
                                @if($photo->description)
                                    <small class="text-muted d-block text-truncate mt-1" style="font-size: 0.75rem;">{{ strip_tags($photo->description) }}</small>
                                @endif
                            </div>
                        </div>
                    @endif
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
                        $otherIsIgOnly = false;
                        if (!empty($other->cover)) {
                            $otherCover = asset('storage/' . $other->cover);
                        } elseif ($other->photos->count() > 0) {
                            $firstLocal = $other->photos->first(fn($p) => !empty($p->image));
                            if ($firstLocal) {
                                $otherCover = asset('storage/' . $firstLocal->image);
                            } else {
                                $otherIsIgOnly = true;
                            }
                        }
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="card-custom h-100 p-3 d-flex align-items-center gap-3 position-relative">
                            @if($otherIsIgOnly)
                                <div class="rounded d-flex align-items-center justify-content-center text-white flex-shrink-0 shadow-sm" style="width: 90px; height: 70px; background: linear-gradient(135deg, #405DE6, #833AB4, #E1306C);">
                                    <i class="bi bi-instagram fs-3"></i>
                                </div>
                            @else
                                <img src="{{ $otherCover }}" alt="{{ $other->name }}" class="rounded flex-shrink-0" style="width: 90px; height: 70px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=200&q=80'">
                            @endif
                            <div class="flex-grow-1 min-w-0">
                                <span class="badge bg-light text-primary border small mb-1">{{ $other->photos_count ?? $other->photos->count() }} Foto</span>
                                <h6 class="fw-bold mb-1 text-truncate">
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

<!-- Modal Detail Instagram (Ringan & Cepat, Tanpa Iframe) -->
<div class="modal fade" id="instagramPhotoModal" tabindex="-1" aria-labelledby="instagramPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white px-3 py-1.5 rounded-pill fw-semibold" style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); font-size: 0.78rem;">
                        <i class="bi bi-instagram me-1"></i> Postingan Instagram
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">
                        {{ $album->name }}
                    </span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-4 py-4">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow" 
                     style="width: 64px; height: 64px; background: linear-gradient(135deg, #405DE6, #833AB4, #E1306C, #FD1D1D);">
                    <i class="bi bi-instagram fs-2 text-white"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2 text-break-word" id="igModalTitle"></h5>
                <p class="text-muted small mb-4 px-2 text-break-word" id="igModalDesc" style="line-height: 1.6;"></p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                    <a href="#" id="igModalLink" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); border: none;">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Instagram
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openInstagramModal(title, desc, url) {
        document.getElementById('igModalTitle').textContent = title || 'Postingan Instagram';
        const descEl = document.getElementById('igModalDesc');
        if (desc && desc.trim().length > 0) {
            descEl.textContent = desc;
        } else {
            descEl.textContent = 'Postingan ini dipublikasikan melalui akun Instagram resmi dinas.';
        }
        document.getElementById('igModalLink').href = url || '#';
        const modal = new bootstrap.Modal(document.getElementById('instagramPhotoModal'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-ig-photo-trigger').forEach(function(el) {
            const handleTrigger = function() {
                const title = el.dataset.title || '';
                const desc = el.dataset.desc || '';
                const url = el.dataset.url || '';
                openInstagramModal(title, desc, url);
            };

            el.addEventListener('click', handleTrigger);
            el.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    handleTrigger();
                }
            });
        });
    });
</script>
@endpush
