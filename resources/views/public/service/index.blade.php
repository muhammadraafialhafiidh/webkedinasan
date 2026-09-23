@extends('layouts.public')

@section('title', 'Standar Pelayanan — ' . \App\Models\Setting::get('nama_website'))

@section('content')

@include('partials.breadcrumb')

<div class="container py-4 mb-5">
    <div class="text-center mb-4">
        <span class="text-uppercase text-primary fw-bold small" style="letter-spacing: 1.5px;">Informasi Publik</span>
        <h1 class="fw-extrabold text-primary-dark display-6 mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">Standar Pelayanan Publik</h1>
        <p class="text-muted" style="max-width: 650px; margin: 0 auto;">Daftar lengkap Standar Pelayanan Dinas Ketahanan Pangan dan Perikanan Kabupaten Banyumas</p>
    </div>

    <!-- Category Filter Chips -->
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-5">
        <a href="{{ route('service.index') }}" class="btn btn-sm rounded-pill px-3.5 py-2 fw-bold transition-all {{ !$selectedCategorySlug ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }}">
            <i class="bi bi-grid-fill me-1.5"></i> Semua Bidang ({{ \App\Models\Service::active()->count() }})
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('service.index', ['kategori' => $cat->slug]) }}" class="btn btn-sm rounded-pill px-3.5 py-2 fw-bold transition-all {{ $selectedCategorySlug === $cat->slug ? 'btn-primary shadow-sm' : 'btn-outline-primary' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    @if($selectedCategory)
        <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 p-4" style="background-color: var(--light-blue); color: var(--primary-dark);">
            <h5 class="fw-bold mb-1"><i class="bi bi-info-circle-fill me-2 text-ocean"></i>{{ $selectedCategory->name }}</h5>
            <p class="mb-0 small text-dark leading-relaxed">{{ strip_tags($selectedCategory->description) }}</p>
        </div>
    @endif

    <!-- Services Grid -->
    <div class="row g-4">
        @forelse($services as $service)
            <div class="col-lg-4 col-md-6">
                <div class="card-custom h-100 p-4 d-flex flex-column position-relative">
                    <div class="mb-3">
                        <span class="badge-ocean">{{ $service->serviceCategory->name ?? 'Bidang' }}</span>
                    </div>

                    <h5 class="fw-bold mb-2 text-primary-dark fs-5 text-break-word" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        <a href="{{ route('service.show', $service->slug) }}" class="text-dark text-decoration-none hover-primary stretched-link">
                            {{ $service->title }}
                        </a>
                    </h5>

                    <p class="text-muted small mb-4 flex-grow-1 text-break-word" style="line-height: 1.65;">
                        {{ Str::limit(strip_tags($service->description), 110) }}
                    </p>

                    <div class="pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2 mt-auto">
                        <span class="small fw-semibold text-muted min-w-0 text-break-word">
                            <i class="bi bi-tag-fill me-1 text-warning"></i>{{ $service->cost ?? 'Gratis' }}
                        </span>
                        <span class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3 flex-shrink-0">
                            Lihat Detail <i class="bi bi-chevron-right ms-1"></i>
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-white rounded-3 border">
                <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                    <i class="bi bi-clipboard-x fs-2 text-muted"></i>
                </div>
                <h6 class="fw-bold text-dark">Layanan Tidak Ditemukan</h6>
                <p class="text-muted small mb-0">Tidak ditemukan layanan dalam bidang ini.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
