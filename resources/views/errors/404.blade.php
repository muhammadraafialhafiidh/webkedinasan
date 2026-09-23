@extends('layouts.public')

@section('title', '404 - Halaman Tidak Ditemukan — ' . \App\Models\Setting::get('nama_website'))

@section('content')
<div class="container py-5 my-5 text-center">
    <div class="row justify-content-center py-4">
        <div class="col-lg-6 col-md-8">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-4 mb-4 shadow-sm">
                <i class="bi bi-compass-fill display-3 text-primary"></i>
            </div>
            <h1 class="display-4 fw-extrabold text-primary-dark mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">404</h1>
            <h3 class="fw-bold text-dark mb-3">Halaman Tidak Ditemukan</h3>
            <p class="text-muted leading-relaxed mb-4">
                Mohon maaf, halaman yang Anda cari tidak dapat ditemukan, telah dihapus, atau alamat URL yang Anda masukkan salah.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('home') }}" class="btn btn-primary-custom rounded-pill fw-bold px-4 py-2.5 shadow-sm">
                    <i class="bi bi-house-door-fill me-1.5"></i> Kembali ke Beranda
                </a>
                <a href="{{ route('service.index') }}" class="btn btn-gold rounded-pill fw-bold px-4 py-2.5 shadow-sm">
                    <i class="bi bi-grid-fill me-1.5"></i> Lihat Layanan Publik
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
