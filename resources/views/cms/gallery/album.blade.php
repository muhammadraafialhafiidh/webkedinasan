@extends('layouts.cms')

@section('title', 'Galeri Album — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.galeri.album.index') }}" class="text-decoration-none text-muted">Galeri</a></li>
    <li class="breadcrumb-item active" aria-current="page">Album Foto</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Album Foto Galeri</h3>
        <p class="text-muted small mb-0">Kelola album foto kegiatan dan dokumentasi dinas</p>
    </div>
    <button class="btn btn-primary font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addAlbumModal">
        <i class="bi bi-folder-plus me-1"></i> Buat Album Baru
    </button>
</div>

<div class="row g-4">
    @forelse($albums as $album)
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $album->cover) }}" alt="{{ $album->name }}" class="w-100" style="height: 180px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80'">
                    <span class="badge bg-dark bg-opacity-75 position-absolute bottom-0 end-0 m-2 px-3 py-1 rounded-pill">
                        <i class="bi bi-images me-1"></i>{{ $album->photos_count }} Foto
                    </span>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <h5 class="fw-bold text-primary mb-2">{{ $album->name }}</h5>
                    <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit(strip_tags($album->description), 90) }}</p>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                        <a href="{{ route('cms.galeri.foto.index', $album->id) }}" class="btn btn-sm btn-primary rounded-pill fw-bold">
                            <i class="bi bi-folder-symlink me-1"></i> Buka Foto ({{ $album->photos_count }})
                        </a>
                        <div class="btn-action-group">
                            <button type="button" class="btn btn-action btn-action-edit" onclick="editAlbum({{ $album->id }}, '{{ addslashes(strip_tags($album->name)) }}', '{{ addslashes(strip_tags($album->description)) }}')" data-bs-toggle="tooltip" data-bs-title="Edit Data"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('cms.galeri.album.destroy', $album->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus album ini beserta semua foto di dalamnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">Belum ada album foto.</div>
    @endforelse
</div>

<!-- Modal Buat Album -->
<div class="modal fade" id="addAlbumModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cms.galeri.album.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Buat Album Foto Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Album <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Kegiatan Budidaya 2025" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Album</label>
                    <textarea name="description" id="description" rows="3" class="form-control" data-no-ckeditor placeholder="Penjelasan singkat mengenai dokumentasi dalam album ini..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="cover" class="form-label">Foto Cover Album</label>
                    <input type="file" name="cover" id="cover" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Buat Album</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Album -->
<div class="modal fade" id="editAlbumModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editAlbumForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Edit Album Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Nama Album <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="edit_description" class="form-label">Deskripsi Album</label>
                    <textarea name="description" id="edit_description" rows="3" class="form-control" data-no-ckeditor></textarea>
                </div>
                <div class="mb-3">
                    <label for="edit_cover" class="form-label">Ganti Foto Cover</label>
                    <input type="file" name="cover" id="edit_cover" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Perbarui Album</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function editAlbum(id, name, desc) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = desc;
        window.setEditorData('edit_description', desc);
        document.getElementById('editAlbumForm').action = "{{ url('/cms/galeri/album') }}/" + id;
        const modal = new bootstrap.Modal(document.getElementById('editAlbumModal'));
        modal.show();
    }
</script>
@endpush
