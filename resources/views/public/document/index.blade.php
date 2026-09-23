@extends('layouts.public')

@section('title', 'Dokumen & Download — ' . \App\Models\Setting::get('nama_website'))

@section('content')

@include('partials.breadcrumb')

<div class="container py-4 mb-5">
    <div class="text-center mb-4">
        <span class="text-uppercase text-primary fw-bold small" style="letter-spacing: 1.5px;">Transparansi Publik</span>
        <h1 class="fw-extrabold text-primary-dark display-6 mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">Dokumen & Download</h1>
        <p class="text-muted" style="max-width: 650px; margin: 0 auto;">Unduh laporan resmi, formulir permohonan, dan buku panduan teknis Dinas Perikanan</p>
    </div>

    <!-- Filter Chips & Search -->
    <div class="row g-3 justify-content-between align-items-center mb-4">
        <div class="col-lg-8">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('document.index') }}" class="btn btn-sm rounded-pill px-3.5 py-2 fw-bold transition-all {{ !$selectedCategory ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }}">
                    Semua Dokumen
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('document.index', ['kategori' => $cat]) }}" class="btn btn-sm rounded-pill px-3.5 py-2 fw-bold transition-all {{ $selectedCategory === $cat ? 'btn-primary shadow-sm' : 'btn-outline-primary' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4">
            <form action="{{ route('document.index') }}" method="GET">
                @if($selectedCategory)
                    <input type="hidden" name="kategori" value="{{ $selectedCategory }}">
                @endif
                <div class="input-group">
                    <input type="text" name="q" class="form-control border-end-0 rounded-start-2 py-2" placeholder="Cari nama dokumen..." value="{{ request('q') }}">
                    <button class="btn btn-primary px-3 rounded-end-2" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Document List -->
    <div class="d-flex flex-column gap-3">
        @forelse($documents as $doc)
            <div class="card-custom p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3 min-w-0 flex-grow-1">
                    <a href="{{ asset('storage/' . $doc->file) }}" target="_blank" class="bg-danger bg-opacity-10 text-danger rounded-3 p-3 text-center text-decoration-none d-flex align-items-center justify-content-center flex-shrink-0" style="width: 58px; height: 58px;" title="Buka Dokumen PDF">
                        <i class="bi bi-file-earmark-pdf-fill fs-2"></i>
                    </a>
                    <div class="min-w-0 flex-grow-1">
                        <span class="badge-ocean mb-1 d-inline-block">{{ $doc->category }}</span>
                        <h5 class="fw-bold text-primary-dark mb-1 fs-6 text-break-word" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                            <a href="{{ asset('storage/' . $doc->file) }}" target="_blank" class="text-dark text-decoration-none hover-primary">
                                {{ $doc->title }} <i class="bi bi-box-arrow-up-right ms-1 text-primary small"></i>
                            </a>
                        </h5>
                        @if($doc->description)
                            <p class="text-muted small mb-0 leading-relaxed text-break-word">{{ strip_tags($doc->description) }}</p>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2 text-md-end text-start flex-wrap flex-shrink-0">
                    <a href="{{ asset('storage/' . $doc->file) }}" target="_blank" class="btn btn-primary rounded-pill btn-sm fw-bold px-3 py-2 shadow-sm">
                        <i class="bi bi-eye-fill me-1"></i> Buka / Lihat PDF
                    </a>
                    <a href="{{ asset('storage/' . $doc->file) }}" download class="btn btn-outline-secondary rounded-pill btn-sm fw-bold px-3 py-2">
                        <i class="bi bi-download me-1"></i> Unduh
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-5 bg-white rounded-3 border">
                <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                    <i class="bi bi-file-earmark-x fs-2 text-muted"></i>
                </div>
                <h6 class="fw-bold text-dark">Dokumen Tidak Ditemukan</h6>
                <p class="text-muted small mb-0">Tidak ada dokumen yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($documents->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $documents->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection
