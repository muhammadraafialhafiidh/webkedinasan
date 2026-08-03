@extends('layouts.cms')

@section('title', 'Manajemen Berita — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Manajemen Berita</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-primary mb-1">Manajemen Berita</h3>
        <p class="text-muted small mb-0">Kelola publikasi berita, pengumuman, dan berita dinas</p>
    </div>
    <a href="{{ route('cms.berita.create') }}" class="btn btn-primary font-bold shadow-sm">
        <i class="bi bi-plus-circle me-1"></i> Tambah Berita
    </a>
</div>

<!-- Filter Box -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('cms.berita.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari judul atau isi berita..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter me-1"></i> Filter</button>
                <a href="{{ route('cms.berita.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                    <th style="width: 70px;">Thumbnail</th>
                    <th>Judul Berita</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Tanggal Publish</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($newsList as $news)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="thumb" class="rounded" style="width: 50px; height: 38px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1534951009808-766178b47a4f?auto=format&fit=crop&w=100&q=80'">
                        </td>
                        <td>
                            <a href="{{ route('cms.berita.edit', $news->id) }}" class="fw-bold text-dark text-decoration-none">
                                {{ Str::limit($news->title, 50) }}
                            </a>
                            @if($news->slug)
                                <small class="d-block text-muted font-monospace" style="font-size: 0.75rem;">/berita/{{ $news->slug }}</small>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-primary border">{{ $news->newsCategory->name ?? '-' }}</span></td>
                        <td class="small">{{ $news->author->name ?? 'Admin' }}</td>
                        <td>
                            @if($news->status === 'published')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Draft</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $news->published_at ? $news->published_at->format('d/m/Y H:i') : '-' }}</td>
                        <td class="text-center">
                            <div class="btn-action-grid-2x2">
                                <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="btn btn-action btn-action-info" data-bs-toggle="tooltip" data-bs-title="Preview Publik"><i class="bi bi-eye"></i></a>
                                <form action="{{ route('cms.berita.toggle', $news->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-action btn-action-warning" data-bs-toggle="tooltip" data-bs-title="{{ $news->status === 'published' ? 'Nonaktifkan Berita' : 'Aktifkan Berita' }}">
                                        <i class="bi {{ $news->status === 'published' ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                    </button>
                                </form>
                                <a href="{{ route('cms.berita.edit', $news->id) }}" class="btn btn-action btn-action-edit" data-bs-toggle="tooltip" data-bs-title="Edit Data"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('cms.berita.destroy', $news->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada berita ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-center mb-0">
            {{ $newsList->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
