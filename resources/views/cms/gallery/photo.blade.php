@extends('layouts.cms')

@section('title', 'Foto Album: ' . $album->name . ' — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.galeri.album.index') }}" class="text-decoration-none text-muted">Album</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($album->name, 25) }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-primary mb-1"><i class="bi bi-images me-2"></i>{{ $album->name }}</h3>
        <p class="text-muted small mb-0">{{ strip_tags($album->description) ?: 'Manajemen foto dalam album ini' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('cms.galeri.album.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Album
        </a>
        <button class="btn btn-primary font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
            <i class="bi bi-upload me-1"></i> Unggah Foto Baru
        </button>
    </div>
</div>

<div class="row g-3">
    @forelse($album->photos as $photo)
        <div class="col-lg-3 col-md-4 col-6">
            <div class="card border-0 shadow-sm overflow-hidden h-100 position-relative">
                <img src="{{ asset('storage/' . $photo->image) }}" alt="{{ $photo->title }}" class="w-100" style="aspect-ratio: 4/3; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80'">
                <div class="p-3 bg-white">
                    <h6 class="fw-bold text-dark mb-1 small text-truncate">{{ $photo->title ?? 'Foto Dokumentasi' }}</h6>
                    <small class="text-muted d-block mb-2" style="font-size: 0.7rem;">{{ $photo->created_at ? $photo->created_at->format('d/m/Y H:i') : '' }}</small>

                    <form action="{{ route('cms.galeri.foto.destroy', $photo->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini dari album?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i> Hapus Foto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 bg-white rounded-3 shadow-sm text-muted">
            <i class="bi bi-image fs-1 text-muted"></i>
            <p class="mt-2">Belum ada foto dalam album ini. Klik tombol di atas untuk mengunggah foto.</p>
        </div>
    @endforelse
</div>

<!-- Modal Upload Foto -->
<div class="modal fade" id="uploadPhotoModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cms.galeri.foto.store', $album->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Unggah Foto ke Album: {{ $album->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Foto / Deskripsi Singkat</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: Foto Penyerahan Bantuan Benih">
                </div>
                <div class="mb-3">
                    <label for="photos" class="form-label">Pilih Berkas Foto <span class="text-danger">*</span></label>
                    <input type="file" name="photos[]" id="photos" class="form-control" accept="image/*" multiple required>
                    <small class="form-text text-muted">Bisa memilih beberapa foto sekaligus (Multiple upload). Max 10MB per foto.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Unggah Sekarang</button>
            </div>
        </form>
    </div>
</div>

@endsection
