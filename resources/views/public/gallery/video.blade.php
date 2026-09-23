@extends('layouts.public')

@section('title', 'Galeri Video — ' . \App\Models\Setting::get('nama_website'))

@section('content')

@include('partials.breadcrumb')

<div class="container py-4 mb-5">
    <div class="text-center mb-5">
        <span class="text-uppercase text-primary fw-bold small" style="letter-spacing: 1.5px;">Edukasi & Publikasi</span>
        <h1 class="fw-extrabold text-primary-dark display-6 mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">Galeri Video Dinas</h1>
        <p class="text-muted" style="max-width: 650px; margin: 0 auto;">Kumpulan video informasi, panduan teknis budidaya, dan dokumentasi program dinas</p>
    </div>

    <div class="row g-4">
        @forelse($videos as $video)
            <div class="col-lg-4 col-md-6">
                <div class="card-custom h-100 d-flex flex-column overflow-hidden">
                    <div class="{{ $video->source_type === 'instagram' ? 'ratio ratio-4x3' : 'ratio ratio-16x9' }} bg-dark position-relative">
                        @if(($video->source_type ?? 'youtube') === 'file')
                            <video controls class="w-100 h-100" style="object-fit: cover;" @if($video->thumbnail) poster="{{ asset('storage/' . $video->thumbnail) }}" @endif>
                                <source src="{{ $video->video_file_url }}" type="video/mp4">
                                Browser Anda tidak mendukung pemutaran video.
                            </video>
                        @elseif($video->source_type === 'google_drive')
                            <iframe src="{{ $video->google_drive_embed_url }}" title="{{ $video->title }}" allow="autoplay; fullscreen" allowfullscreen loading="lazy" class="w-100 h-100 border-0"></iframe>
                        @elseif($video->source_type === 'instagram')
                            @if($video->instagram_embed_url)
                                <iframe src="{{ $video->instagram_embed_url }}" title="{{ $video->title }}" allowfullscreen loading="lazy" class="w-100 h-100 border-0"></iframe>
                            @else
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white text-center p-3" style="background: linear-gradient(135deg, #405DE6 0%, #5851DB 25%, #833AB4 50%, #C13584 75%, #E1306C 100%);">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 shadow-sm" style="width: 48px; height: 48px; background: rgba(255,255,255,0.22); backdrop-filter: blur(6px); border: 2px solid rgba(255,255,255,0.4);">
                                        <i class="bi bi-instagram fs-3 text-white"></i>
                                    </div>
                                    <span class="badge bg-white text-dark rounded-pill px-2.5 py-1 small fw-bold shadow-sm" style="font-size: 0.7rem;">
                                        <i class="bi bi-instagram me-1 text-danger"></i>Postingan / Reel Instagram
                                    </span>
                                </div>
                            @endif
                        @else
                            <iframe src="{{ $video->youtube_embed_url }}" title="{{ $video->title }}" allowfullscreen loading="lazy" class="w-100 h-100 border-0"></iframe>
                        @endif
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <h5 class="fw-bold text-primary-dark fs-6 mb-0 min-w-0 flex-grow-1 text-break-word" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                {{ $video->title }}
                            </h5>
                            @if(($video->source_type ?? 'youtube') === 'youtube')
                                <span class="badge bg-danger px-2.5 py-1.5 rounded-pill flex-shrink-0"><i class="bi bi-youtube me-1"></i>YouTube</span>
                            @elseif($video->source_type === 'google_drive')
                                <span class="badge bg-success px-2.5 py-1.5 rounded-pill flex-shrink-0"><i class="bi bi-google me-1"></i>Google Drive</span>
                            @elseif($video->source_type === 'instagram')
                                <span class="badge text-white px-2.5 py-1.5 rounded-pill flex-shrink-0" style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);"><i class="bi bi-instagram me-1"></i>Instagram</span>
                            @else
                                <span class="badge bg-primary px-2.5 py-1.5 rounded-pill flex-shrink-0"><i class="bi bi-file-earmark-play me-1"></i>Video</span>
                            @endif
                        </div>
                        <p class="text-muted small mb-0 flex-grow-1 leading-relaxed text-break-word">
                            {{ strip_tags($video->description) }}
                        </p>
                        @if($video->source_type === 'instagram' && $video->url)
                            <div class="mt-3 pt-2 border-top">
                                <a href="{{ $video->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger rounded-pill w-100 py-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-instagram me-1"></i> Buka di Instagram
                                </a>
                            </div>
                        @endif
                        <div class="mt-3 pt-2.5 border-top text-muted small">
                            <i class="bi bi-calendar3 me-1"></i>{{ $video->created_at ? $video->created_at->format('d M Y') : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-white rounded-3 border">
                <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                    <i class="bi bi-film fs-2 text-muted"></i>
                </div>
                <h6 class="fw-bold text-dark">Galeri Video Kosong</h6>
                <p class="text-muted small mb-0">Belum ada video dipublikasikan.</p>
            </div>
        @endforelse
    </div>

    @if($videos->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $videos->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection
