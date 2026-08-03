@extends('layouts.cms')

@section('title', 'Pesan Masuk (Inbox) — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Pesan Masuk</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Pesan Masuk (Kontak & Aspirasi)</h3>
        <p class="text-muted small mb-0">Kelola pesan dan pertanyaan dari masyarakat umum</p>
    </div>
</div>

<!-- Filter Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('cms.pesan.index') }}" method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama pengirim, email, atau subjek..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter me-1"></i> Filter</button>
                <a href="{{ route('cms.pesan.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">Status</th>
                    <th>Nama Pengirim</th>
                    <th>Subjek Pesan</th>
                    <th>Kontak Email / Telp</th>
                    <th>Waktu Masuk</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr class="{{ !$msg->is_read ? 'fw-bold table-light' : '' }}">
                        <td class="text-center">
                            @if(!$msg->is_read)
                                <span class="badge bg-danger rounded-pill p-2" title="Belum Dibaca"><i class="bi bi-envelope-fill"></i></span>
                            @else
                                <span class="badge bg-secondary rounded-pill p-2" title="Sudah Dibaca"><i class="bi bi-envelope-open-fill"></i></span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('cms.pesan.show', $msg->id) }}" class="text-primary text-decoration-none fw-bold">
                                {{ $msg->name }}
                            </a>
                        </td>
                        <td>{{ Str::limit($msg->subject, 45) }}</td>
                        <td class="small">
                            <div><i class="bi bi-envelope me-1"></i>{{ $msg->email }}</div>
                            @if($msg->phone)
                                <div class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $msg->phone }}</div>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $msg->created_at ? $msg->created_at->format('d/m/Y H:i') : '' }}</td>
                        <td class="text-center">
                            <div class="btn-action-group">
                                <a href="{{ route('cms.pesan.show', $msg->id) }}" class="btn btn-action btn-action-info" data-bs-toggle="tooltip" data-bs-title="Detail Pesan"><i class="bi bi-eye"></i></a>
                                <form action="{{ route('cms.pesan.destroy', $msg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pesan dari {{ addslashes($msg->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada pesan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-center mb-0">
            {{ $messages->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
