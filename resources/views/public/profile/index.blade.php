@extends('layouts.public')

@section('title', 'Profil Dinas — ' . \App\Models\Setting::get('nama_website'))

@section('content')

@include('partials.breadcrumb')

<div class="container py-4 mb-5">
    <div class="text-center mb-4">
        <span class="text-uppercase text-primary fw-bold small" style="letter-spacing: 1.5px;">Mengenal Lebih Dekat</span>
        <h1 class="fw-extrabold text-primary-dark display-6 mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">Profil Dinas Ketahanan Pangan dan Perikanan</h1>
        <p class="text-muted" style="max-width: 650px; margin: 0 auto;">Informasi Sejarah, Visi Misi, Tugas Pokok, dan Struktur Organisasi Dinas</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="overflow-x-auto pb-2 mb-4">
        <ul class="nav nav-pills nav-justified border p-2 bg-white rounded-pill shadow-sm flex-nowrap flex-md-wrap gap-1 min-w-max" id="profileTabs" role="tablist" style="min-width: max-content;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill fw-bold py-2.5 px-3 whitespace-nowrap" id="sejarah-tab" data-bs-toggle="pill" data-bs-target="#sejarah" type="button" role="tab">
                    <i class="bi bi-hourglass-split me-1.5 text-warning"></i> Sejarah Dinas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill fw-bold py-2.5 px-3 whitespace-nowrap" id="visimisi-tab" data-bs-toggle="pill" data-bs-target="#visimisi" type="button" role="tab">
                    <i class="bi bi-compass me-1.5 text-ocean"></i> Visi & Misi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill fw-bold py-2.5 px-3 whitespace-nowrap" id="tupoksi-tab" data-bs-toggle="pill" data-bs-target="#tupoksi" type="button" role="tab">
                    <i class="bi bi-file-earmark-text me-1.5 text-info"></i> Tupoksi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill fw-bold py-2.5 px-3 whitespace-nowrap" id="struktur-tab" data-bs-toggle="pill" data-bs-target="#struktur" type="button" role="tab">
                    <i class="bi bi-diagram-3 me-1.5 text-success"></i> Struktur Organisasi
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content bg-white p-4 p-md-5 rounded-4 shadow-sm border" id="profileTabsContent">
        <!-- Sejarah -->
        <div class="tab-pane fade show active" id="sejarah" role="tabpanel">
            <h3 class="fw-bold text-primary-dark mb-4 pb-2 border-bottom" style="font-family: 'Plus Jakarta Sans', sans-serif;"><i class="bi bi-journal-bookmark me-2 text-ocean"></i>Sejarah Singkat</h3>
            <div class="content-body text-dark" style="font-size: 1.05rem; line-height: 1.85;">
                {!! $sejarah !!}
            </div>
        </div>

        <!-- Visi & Misi -->
        <div class="tab-pane fade" id="visimisi" role="tabpanel">
            <div class="mb-5">
                <h3 class="fw-bold text-primary-dark mb-3 pb-2 border-bottom" style="font-family: 'Plus Jakarta Sans', sans-serif;"><i class="bi bi-eye me-2 text-ocean"></i>Visi Dinas</h3>
                <div class="p-4 rounded-4 border-start border-4 border-warning" style="background-color: var(--light-blue);">
                    <p class="fst-italic fs-5 fw-bold text-dark mb-0 text-break-word" style="line-height: 1.6;">
                        "{{ strip_tags($visi) }}"
                    </p>
                </div>
            </div>

            <div>
                <h3 class="fw-bold text-primary-dark mb-3 pb-2 border-bottom" style="font-family: 'Plus Jakarta Sans', sans-serif;"><i class="bi bi-list-check me-2 text-ocean"></i>Misi Dinas</h3>
                <div class="content-body text-dark" style="font-size: 1.05rem; line-height: 1.85;">
                    {!! $misi !!}
                </div>
            </div>
        </div>

        <!-- Tupoksi -->
        <div class="tab-pane fade" id="tupoksi" role="tabpanel">
            <h3 class="fw-bold text-primary-dark mb-4 pb-2 border-bottom" style="font-family: 'Plus Jakarta Sans', sans-serif;"><i class="bi bi-file-text me-2 text-ocean"></i>Tugas Pokok & Fungsi</h3>
            <div class="content-body text-dark" style="font-size: 1.05rem; line-height: 1.85;">
                {!! $tupoksi !!}
            </div>
        </div>

        <!-- Struktur Organisasi -->
        <div class="tab-pane fade" id="struktur" role="tabpanel">
            <h3 class="fw-bold text-primary-dark mb-4 pb-2 border-bottom" style="font-family: 'Plus Jakarta Sans', sans-serif;"><i class="bi bi-people me-2 text-ocean"></i>Struktur Organisasi Dinas</h3>
            <div class="row g-4 mt-2">
                @forelse($organizationMembers as $member)
                    <div class="col-lg-4 col-md-6">
                        <div class="card-custom text-center p-4 h-100">
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="rounded-circle shadow-sm border p-1" style="width: 120px; height: 120px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=003F88&color=ffffff'">
                            </div>
                            <h5 class="fw-bold mb-1 text-primary-dark fs-6 text-break-word">{{ $member->name }}</h5>
                            <span class="badge bg-warning bg-opacity-20 text-dark fw-bold px-3 py-1.5 rounded-pill mb-2 text-break-word" style="font-size: 0.8rem; border: 1px solid rgba(244, 161, 0, 0.4); max-width: 100%;">
                                {{ $member->position }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">Data struktur organisasi belum tersedia.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
