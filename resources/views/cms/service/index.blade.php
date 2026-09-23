@extends('layouts.cms')

@section('title', 'Manajemen Layanan — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Manajemen Layanan</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-primary mb-1">Manajemen Layanan Publik</h3>
        <p class="text-muted small mb-0">Kelola 12 Standar Pelayanan Publik Dinas Ketahanan Pangan dan Perikanan</p>
    </div>
    <a href="{{ route('cms.layanan.create') }}" class="btn btn-primary font-bold shadow-sm">
        <i class="bi bi-plus-circle me-1"></i> Tambah Layanan Baru
    </a>
</div>

<!-- Filter Box -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('cms.layanan.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama layanan atau deskripsi..." value="{{ request('q') }}">
            </div>
            <div class="col-md-4">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">-- Semua Bidang / Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter me-1"></i> Filter</button>
                <a href="{{ route('cms.layanan.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Data Table Card -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th>Nama Layanan</th>
                    <th>Bidang / Kategori</th>
                    <th>Jangka Waktu</th>
                    <th>Biaya</th>
                    <th>Status Aktif</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $srv)
                    <tr>
                        <td>
                            <a href="{{ route('cms.layanan.edit', $srv->id) }}" class="fw-bold text-dark text-decoration-none">
                                {{ $srv->title }}
                            </a>
                            <small class="d-block text-muted" style="font-size: 0.75rem;">Produk: {{ $srv->product ?? '-' }}</small>
                        </td>
                        <td><span class="badge bg-light text-primary border">{{ $srv->serviceCategory->name ?? '-' }}</span></td>
                        <td class="small">{{ $srv->duration ?? '-' }}</td>
                        <td class="small fw-semibold text-success">{{ $srv->cost ?? '-' }}</td>
                        <td>
                            @if($srv->is_active)
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-action-grid-2x2">
                                <a href="{{ route('service.show', $srv->slug) }}" target="_blank" class="btn btn-action btn-action-info" data-bs-toggle="tooltip" data-bs-title="Preview Publik"><i class="bi bi-eye"></i></a>
                                <form action="{{ route('cms.layanan.toggle', $srv->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-action btn-action-warning" data-bs-toggle="tooltip" data-bs-title="{{ $srv->is_active ? 'Nonaktifkan Layanan' : 'Aktifkan Layanan' }}">
                                        <i class="bi {{ $srv->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                    </button>
                                </form>
                                <a href="{{ route('cms.layanan.edit', $srv->id) }}" class="btn btn-action btn-action-edit" data-bs-toggle="tooltip" data-bs-title="Edit Data"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('cms.layanan.destroy', $srv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada layanan ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-center mb-0">
            {{ $services->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
