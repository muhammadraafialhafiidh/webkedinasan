@extends('layouts.cms')

@section('title', 'Detail Pesan — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.pesan.index') }}" class="text-decoration-none text-muted">Pesan Masuk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail #{{ $message->id }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Detail Pesan Masuk #{{ $message->id }}</h3>
        <p class="text-muted small mb-0">Diterima pada {{ $message->created_at ? $message->created_at->format('d F Y \j\a\m H:i') : '' }}</p>
    </div>
    <a href="{{ route('cms.pesan.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Inbox
    </a>
</div>

<div class="row g-4">
    <!-- Message Content (8 col) -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <div class="border-bottom pb-3 mb-3">
                <span class="badge bg-light text-primary border mb-2 fs-6">Subjek: {{ $message->subject }}</span>
                <h4 class="fw-bold text-dark mb-0">{{ $message->subject }}</h4>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-muted small">Isi Pesan / Pertanyaan:</label>
                <div class="p-3 bg-light rounded-3 border leading-relaxed text-dark" style="font-size: 1rem; line-height: 1.7;">
                    {!! nl2br(e($message->message)) !!}
                </div>
            </div>

            @if($message->reply)
                <div class="alert alert-success border-0 p-4 rounded-3 mb-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-reply-fill me-1"></i>Balasan Telah Dikirim ({{ $message->replied_at ? $message->replied_at->format('d/m/Y H:i') : '' }}):</h6>
                    <div class="text-dark">{!! nl2br(e($message->reply)) !!}</div>
                </div>
            @endif

            <!-- Reply Form -->
            <form action="{{ route('cms.pesan.reply', $message->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="reply" class="form-label fw-bold text-primary">Tulis Balasan Pesan</label>
                    <textarea name="reply" id="reply" rows="5" class="form-control" placeholder="Tuliskan pesan balasan resmi untuk pengirim..." required>{{ old('reply', $message->reply) }}</textarea>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary font-bold px-4 shadow-sm">
                        <i class="bi bi-send-fill me-1"></i> Simpan & Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sender Details Sidebar (4 col) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-person-lines-fill me-2"></i>Informasi Pengirim</h5>
            <div class="d-flex flex-column gap-3 small">
                <div>
                    <label class="text-muted d-block fw-semibold">Nama Lengkap</label>
                    <div class="fw-bold text-dark fs-6">{{ $message->name }}</div>
                </div>
                <div>
                    <label class="text-muted d-block fw-semibold">Alamat Email</label>
                    <a href="mailto:{{ $message->email }}" class="text-primary fw-semibold">{{ $message->email }}</a>
                </div>
                <div>
                    <label class="text-muted d-block fw-semibold">Nomor Telepon</label>
                    <div class="fw-semibold text-dark">{{ $message->phone ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-muted d-block fw-semibold">Status Pesan</label>
                    @if($message->is_read)
                        <span class="badge bg-success">Sudah Dibaca</span>
                    @else
                        <span class="badge bg-danger">Belum Dibaca</span>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <form action="{{ route('cms.pesan.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-bold">
                    <i class="bi bi-trash me-1"></i> Hapus Pesan Ini
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
