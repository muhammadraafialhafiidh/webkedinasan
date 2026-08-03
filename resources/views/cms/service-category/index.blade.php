@extends('layouts.cms')

@section('title', 'Kategori Layanan — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.layanan.index') }}" class="text-decoration-none text-muted">Layanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kategori & Bidang</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Manajemen Bidang / Kategori Layanan</h3>
        <p class="text-muted small mb-0">Kelola 5 bidang/kategori pelayanan publik di Dinas Ketahanan Pangan dan Perikanan</p>
    </div>
    <button class="btn btn-primary font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle me-1"></i> Tambah Bidang Layanan
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">Urutan</th>
                    <th>Nama Bidang / Kategori</th>
                    <th>Deskripsi Singkat</th>
                    <th>Jumlah Layanan</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr>
                        <td class="fw-bold text-center">{{ $cat->order }}</td>
                        <td>
                            <div class="fw-bold text-primary">{{ $cat->name }}</div>
                            <small class="text-muted font-monospace" style="font-size: 0.75rem;">/layanan?kategori={{ $cat->slug }}</small>
                        </td>
                        <td class="small text-muted" style="max-width: 350px;">{{ Str::limit($cat->description, 100) }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $cat->services_count }} Layanan</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-action-group">
                                <button type="button" class="btn btn-action btn-action-edit" onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description) }}', {{ $cat->order }})" data-bs-toggle="tooltip" data-bs-title="Edit Data">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('cms.kategori-layanan.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus bidang layanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" {{ $cat->services_count > 0 ? 'disabled' : '' }} data-bs-toggle="tooltip" data-bs-title="{{ $cat->services_count > 0 ? 'Tidak bisa dihapus karena masih ada layanan' : 'Hapus Data' }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada kategori layanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cms.kategori-layanan.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Tambah Bidang Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="add_name" class="form-label">Nama Bidang / Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="add_name" class="form-control" placeholder="Contoh: Bidang Perikanan" required>
                </div>
                <div class="mb-3">
                    <label for="add_desc" class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" id="add_desc" rows="3" class="form-control" placeholder="Penjelasan bidang layanan..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="add_order" class="form-label">Urutan Tampil</label>
                    <input type="number" name="order" id="add_order" class="form-control" value="0">
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
                <h5 class="modal-title fw-bold text-primary">Edit Bidang Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Nama Bidang / Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="edit_desc" class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" id="edit_desc" rows="3" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="edit_order" class="form-label">Urutan Tampil</label>
                    <input type="number" name="order" id="edit_order" class="form-control">
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
    function editCategory(id, name, desc, order) {
        document.getElementById('edit_name').value = name;
        window.setEditorData('edit_desc', desc);
        document.getElementById('edit_order').value = order;
        document.getElementById('editForm').action = "{{ url('/cms/kategori-layanan') }}/" + id;
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }
</script>
@endpush
