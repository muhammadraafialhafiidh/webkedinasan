@extends('layouts.public')

@section('title', 'Hubungi Kami — ' . \App\Models\Setting::get('nama_website'))

@section('content')

@include('partials.breadcrumb')

<div class="container py-4 mb-5">
    <div class="text-center mb-5">
        <span class="text-uppercase text-primary fw-bold small" style="letter-spacing: 1.5px;">Layanan Informasi & Pengaduan</span>
        <h1 class="fw-extrabold text-primary-dark display-6 mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">Kontak Kami</h1>
        <p class="text-muted" style="max-width: 650px; margin: 0 auto;">Sampaikan pertanyaan, saran, maupun permohonan informasi kepada Dinas Perikanan</p>
    </div>

    <div class="row g-4 mb-5">
        <!-- Contact Info Cards (4 col) -->
        <div class="col-lg-4">
            <div class="card-custom p-4 h-100 d-flex flex-column gap-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-geo-alt-fill fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-primary-dark mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Alamat Kantor</h6>
                        <p class="text-muted small mb-0 leading-relaxed">{{ $info['alamat'] }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-telephone-fill fs-4 text-info"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-primary-dark mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Telepon & Fax</h6>
                        <p class="text-muted small mb-0 leading-relaxed">{{ $info['telepon'] }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-envelope-fill fs-4 text-success"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-primary-dark mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Email Resmi</h6>
                        <p class="text-muted small mb-0 leading-relaxed">{{ $info['email'] }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-clock-fill fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-primary-dark mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Jam Pelayanan</h6>
                        <p class="text-muted small mb-0 leading-relaxed">{{ $info['jam'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form (8 col) -->
        <div class="col-lg-8">
            <div class="bg-white p-4 p-md-5 rounded-4 border shadow-sm">
                <h4 class="fw-bold text-primary-dark mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;"><i class="bi bi-chat-left-dots-fill me-2 text-ocean"></i>Kirim Pesan Masuk</h4>
                <p class="text-muted small mb-4">Isi formulir di bawah ini. Tim pengelola layanan kami akan menindaklanjuti pesan Anda.</p>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" id="contactForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold small text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control py-2.5 rounded-2" value="{{ old('name') }}" placeholder="Masukkan nama Anda" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold small text-dark">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control py-2.5 rounded-2" value="{{ old('email') }}" placeholder="nama@email.com" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-bold small text-dark">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" id="phone" class="form-control py-2.5 rounded-2" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label for="subject" class="form-label fw-bold small text-dark">Subjek Pesan <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="subject" class="form-control py-2.5 rounded-2" value="{{ old('subject') }}" placeholder="Contoh: Pertanyaan Syarat Perizinan" required>
                        </div>
                        <div class="col-12">
                            <label for="message" class="form-label fw-bold small text-dark">Isi Pesan <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="5" class="form-control rounded-2 py-2.5" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." required>{{ old('message') }}</textarea>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary-custom rounded-pill px-5 py-2.5 shadow-sm">
                                <i class="bi bi-send-fill me-2"></i> Kirim Pesan Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Embed Google Maps -->
    <div class="card-custom overflow-hidden border shadow-sm">
        <div class="ratio ratio-21x9" style="min-height: 360px;">
            <iframe src="{{ $info['map'] }}" allowfullscreen loading="lazy" class="w-100 h-100 border-0"></iframe>
        </div>
    </div>
</div>

@endsection
