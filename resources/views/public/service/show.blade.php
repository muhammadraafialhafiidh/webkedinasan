@extends('layouts.public')

@section('title', $service->title . ' — Standar Pelayanan')

@push('styles')
<style>
    .btn-wa-custom {
        background-color: #25D366;
        color: #FFFFFF !important;
        border: none;
        padding: 10px 18px;
        transition: all 0.25s ease-in-out;
    }
    .btn-wa-custom:hover {
        background-color: #1EBE57;
        color: #FFFFFF !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(37, 211, 102, 0.4) !important;
    }
</style>
@endpush

@section('content')

@include('partials.breadcrumb')

<div class="container py-4 mb-5">
    <div class="row g-4">
        <!-- Main Service Detail Column (8 col) -->
        <div class="col-lg-8">
            <div class="bg-white p-4 p-md-5 rounded-4 border shadow-sm">
                <div class="mb-3">
                    <span class="badge-ocean fs-6 px-3 py-2">{{ $service->serviceCategory->name ?? 'Bidang Layanan' }}</span>
                </div>

                <h1 class="fw-bold text-primary-dark mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2rem; letter-spacing: -0.02em;">
                    {{ $service->title }}
                </h1>

                <p class="lead text-muted mb-4" style="font-size: 1.1rem; line-height: 1.8;">
                    {{ strip_tags($service->description) }}
                </p>

                <!-- Key Info Box -->
                <div class="row g-3 mb-4 p-3.5 bg-light rounded-4 border">
                    <div class="col-md-4">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.725rem; letter-spacing: 0.05em;">Jangka Waktu</small>
                        <span class="fw-bold text-primary"><i class="bi bi-clock me-1 text-warning"></i>{{ $service->duration ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.725rem; letter-spacing: 0.05em;">Biaya / Tarif</small>
                        <span class="fw-bold text-success"><i class="bi bi-cash-stack me-1 text-success"></i>{{ $service->cost ?? '-' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.725rem; letter-spacing: 0.05em;">Produk Layanan</small>
                        <span class="fw-bold text-dark"><i class="bi bi-file-earmark-check me-1 text-info"></i>{{ $service->product ?? '-' }}</span>
                    </div>
                </div>

                <!-- Persyaratan -->
                <div class="mb-5">
                    <h4 class="fw-bold text-primary-dark mb-3 border-bottom pb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        <i class="bi bi-list-check me-2 text-ocean"></i>Persyaratan Pelayanan
                    </h4>
                    <div class="service-html-content leading-relaxed" style="font-size: 1.025rem; line-height: 1.85;">
                        {!! $service->requirements ?? '<p class="text-muted">Tidak ada persyaratan khusus.</p>' !!}
                    </div>
                </div>

                <!-- Prosedur & Mekanisme -->
                <div class="mb-4">
                    <h4 class="fw-bold text-primary-dark mb-3 border-bottom pb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        <i class="bi bi-diagram-3 me-2 text-ocean"></i>Prosedur & Mekanisme
                    </h4>
                    <div class="service-html-content leading-relaxed" style="font-size: 1.025rem; line-height: 1.85;">
                        {!! $service->procedure ?? '<p class="text-muted">Prosedur mengikuti petunjuk teknis di kantor pelayanan.</p>' !!}
                    </div>
                </div>

                <hr class="my-4.5 opacity-25">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="{{ route('service.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Layanan
                    </a>
                    <a href="{{ route('contact.index') }}" class="btn btn-primary rounded-pill fw-bold px-4 py-2">
                        <i class="bi bi-question-circle me-1"></i> Konsultasi Layanan
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar Column (4 col) -->
        <div class="col-lg-4">
            <!-- 1. Layanan Lain dalam Bidang Ini -->
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3">Layanan Lain dalam Bidang Ini</h5>
                <div class="list-group list-group-flush">
                    @forelse($otherServices as $other)
                        <a href="{{ route('service.show', $other->slug) }}" class="list-group-item list-group-item-action py-3 px-2 rounded-2 border-0 mb-1">
                            <div class="fw-semibold text-dark small mb-1">{{ $other->title }}</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;"><i class="bi bi-clock me-1 text-warning"></i>{{ $other->duration }}</small>
                        </a>
                    @empty
                        <div class="text-muted small py-2">Tidak ada layanan lain dalam kategori ini.</div>
                    @endforelse
                </div>
            </div>

            <!-- 2. Card Penanggung Jawab (Multi-Officer Support) -->
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3">
                    <i class="bi bi-person-fill-gear me-2"></i>Penanggung Jawab
                </h5>
                @php
                    $officers = $service->penanggungJawabList;
                    if ($officers->isEmpty() && $service->penanggungJawab) {
                        $officers = collect([$service->penanggungJawab]);
                    }
                @endphp

                @forelse($officers as $index => $pj)
                    <div class="{{ !$loop->last ? 'mb-4 pb-3 border-bottom' : '' }}">
                        @if($pj->pivot && $pj->pivot->keterangan)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-2 fw-semibold px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;">
                                <i class="bi bi-tag-fill me-1"></i>{{ $pj->pivot->keterangan }}
                            </span>
                        @endif

                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-person-badge fs-5 text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Nama Petugas</small>
                                <span class="fw-bold text-dark fs-6">{{ $pj->nama }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Nomor HP / WhatsApp</small>
                            <span class="fw-bold text-dark font-monospace small"><i class="bi bi-telephone-fill me-1 text-success"></i>{{ $pj->nomor_hp }}</span>
                        </div>

                        @if($pj->wa_link)
                            <a href="{{ $pj->wa_link }}" target="_blank" class="btn btn-wa-custom w-100 rounded-pill fw-bold btn-sm d-flex align-items-center justify-content-center gap-2 shadow-sm py-2">
                                <i class="bi bi-whatsapp fs-6"></i>
                                <span>Hubungi via WhatsApp</span>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="text-muted small py-2">
                        <i class="bi bi-info-circle me-1"></i>Petugas penanggung jawab khusus belum ditetapkan.
                    </div>
                @endforelse
            </div>

            <!-- 3. Card Butuh Bantuan -->
            <div class="card-custom p-4 text-center bg-white border">
                <div class="bg-warning bg-opacity-20 text-warning rounded-circle d-inline-flex p-3 mb-2">
                    <i class="bi bi-headset fs-2 text-warning"></i>
                </div>
                <h5 class="fw-bold text-dark">Butuh Bantuan?</h5>
                <p class="small text-muted mb-3 leading-relaxed">Jika Anda memerlukan klarifikasi atau bantuan pengajuan layanan, tim petugas kami siap membantu.</p>
                <a href="{{ route('contact.index') }}" class="btn btn-gold btn-sm rounded-pill w-100 fw-bold py-2">
                    Hubungi Petugas Loket
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
