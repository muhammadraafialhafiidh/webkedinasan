@extends('layouts.cms')

@section('title', 'Kategori Berita — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.berita.index') }}" class="text-decoration-none text-muted">Berita</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kategori Berita</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Manajemen Kategori Berita</h3>
        <p class="text-muted small mb-0">Kelola daftar pengelompokan berita dan artikel</p>
    </div>
    <button class="btn btn-primary font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Slug URL</th>
                    <th>Jumlah Berita</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $cat)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold text-primary">{{ $cat->name }}</td>
                        <td class="font-monospace small text-muted">{{ $cat->slug }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $cat->news_count }} Berita</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-action-group">
                                <button type="button" class="btn btn-action btn-action-edit" onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}')" data-bs-toggle="tooltip" data-bs-title="Edit Data">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('cms.kategori-berita.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" {{ $cat->news_count > 0 ? 'disabled' : '' }} data-bs-toggle="tooltip" data-bs-title="{{ $cat->news_count > 0 ? 'Tidak bisa dihapus karena ada berita terikat' : 'Hapus Data' }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada kategori berita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cms.kategori-berita.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Tambah Kategori Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="add_name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="add_name" class="form-control" placeholder="Contoh: Pengumuman" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Edit Kategori Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function editCategory(id, name) {
        document.getElementById('edit_name').value = name;
        document.getElementById('editForm').action = "{{ url('/cms/kategori-berita') }}/" + id;
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }
</script>
@endpush
