@extends('layouts.cms')

@section('title', 'Manajemen Dokumen — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Manajemen Dokumen</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Manajemen Dokumen & Download</h3>
        <p class="text-muted small mb-0">Kelola berkas resmi, formulir permohonan, dan laporan publik</p>
    </div>
    <button class="btn btn-primary font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addDocModal">
        <i class="bi bi-file-earmark-plus me-1"></i> Unggah Dokumen Baru
    </button>
</div>

<!-- Search Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('cms.dokumen.index') }}" method="GET" class="row g-2">
            <div class="col-md-9">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama dokumen atau deskripsi..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="{{ route('cms.dokumen.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Judul Dokumen</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Upload</th>
                    <th class="text-center" style="width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $index => $doc)
                    <tr>
                        <td>{{ $documents->firstItem() + $index }}</td>
                        <td class="fw-bold text-primary">
                            <a href="{{ asset('storage/' . $doc->file) }}" target="_blank" class="text-primary text-decoration-none">
                                <i class="bi bi-file-earmark-pdf-fill me-1 text-danger"></i>{{ $doc->title }} <i class="bi bi-box-arrow-up-right ms-1 text-muted small"></i>
                            </a>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $doc->category }}</span></td>
                        <td class="small text-muted" style="max-width: 250px;">{{ Str::limit(strip_tags($doc->description), 80) }}</td>
                        <td class="small text-muted">{{ $doc->created_at ? $doc->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            <div class="btn-action-group">
                                <a href="{{ asset('storage/' . $doc->file) }}" target="_blank" class="btn btn-action btn-action-info" data-bs-toggle="tooltip" data-bs-title="Pratinjau Berkas"><i class="bi bi-eye-fill"></i></a>
                                <a href="{{ asset('storage/' . $doc->file) }}" download class="btn btn-action btn-action-success" data-bs-toggle="tooltip" data-bs-title="Unduh Berkas"><i class="bi bi-download"></i></a>
                                <button type="button" class="btn btn-action btn-action-edit" onclick="editDoc({{ $doc->id }}, '{{ addslashes(strip_tags($doc->title)) }}', '{{ addslashes(strip_tags($doc->category)) }}', '{{ addslashes(strip_tags($doc->description)) }}')" data-bs-toggle="tooltip" data-bs-title="Edit Data"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('cms.dokumen.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada dokumen diunggah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-center mb-0">
            {{ $documents->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addDocModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cms.dokumen.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Unggah Dokumen Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: Laporan Kinerja 2024" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Kategori Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="category" id="category" class="form-control" list="catList" placeholder="Contoh: Laporan, Formulir, Panduan" required>
                    <datalist id="catList">
                        <option value="Laporan">
                        <option value="Formulir">
                        <option value="Panduan">
                        <option value="Brosur">
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" id="description" rows="2" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">Berkas File (PDF / Word / Excel) <span class="text-danger">*</span></label>
                    <input type="file" name="file" id="file" class="form-control" accept=".pdf,.docx,.xlsx,.doc,.xls" required>
                    <small class="form-text text-muted">Maksimal ukuran file 10MB.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Unggah Dokumen</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editDocModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editDocForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Edit Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_title" class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="edit_title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="edit_category" class="form-label">Kategori Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="category" id="edit_category" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="edit_desc" class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" id="edit_desc" rows="2" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="edit_file" class="form-label">Ganti Berkas File (Opsional)</label>
                    <input type="file" name="file" id="edit_file" class="form-control" accept=".pdf,.docx,.xlsx,.doc,.xls">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengunggah file baru.</small>
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
    function editDoc(id, title, category, desc) {
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_category').value = category;
        window.setEditorData('edit_desc', desc);
        document.getElementById('editDocForm').action = "{{ url('/cms/dokumen') }}/" + id;
        const modal = new bootstrap.Modal(document.getElementById('editDocModal'));
        modal.show();
    }
</script>
@endpush
