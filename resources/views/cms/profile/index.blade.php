@extends('layouts.cms')

@section('title', 'Kelola Profil Dinas — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Profil Dinas</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Pengaturan Profil Dinas</h3>
        <p class="text-muted small mb-0">Kelola Sejarah, Visi, Misi, Tugas Pokok & Fungsi (Tupoksi), serta Struktur Pejabat Dinas</p>
    </div>
</div>

<form action="{{ route('cms.profil.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Form (8 col) -->
        <div class="col-lg-8">
            <!-- Sejarah -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-journal-text me-2"></i>Sejarah Singkat</h5>
                <textarea name="sejarah" id="sejarah" rows="6" class="form-control text-editor">{{ old('sejarah', $contents['sejarah'] ?? '') }}</textarea>
            </div>

            <!-- Visi -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-eye me-2"></i>Visi Instansi</h5>
                <textarea name="visi" id="visi" rows="4" class="form-control text-editor">{{ old('visi', $contents['visi'] ?? '') }}</textarea>
            </div>

            <!-- Misi -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-list-check me-2"></i>Misi Instansi</h5>
                <textarea name="misi" id="misi" rows="6" class="form-control text-editor">{{ old('misi', $contents['misi'] ?? '') }}</textarea>
            </div>

            <!-- Tupoksi -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-briefcase me-2"></i>Tugas Pokok & Fungsi (Tupoksi)</h5>
                <textarea name="tupoksi" id="tupoksi" rows="8" class="form-control text-editor">{{ old('tupoksi', $contents['tupoksi'] ?? '') }}</textarea>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-primary btn-lg font-bold shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Profil Dinas
                </button>
            </div>
        </div>

        <!-- Sidebar Pejabat Organisasi (4 col) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 80px;">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold text-primary mb-0"><i class="bi bi-people me-2"></i>Struktur Pejabat</h5>
                    <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah
                    </button>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse($organizationMembers as $member)
                        <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="rounded-circle border" style="width: 48px; height: 48px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=003F88&color=ffffff'">
                                <div>
                                    <div class="fw-bold small text-dark mb-0">{{ $member->name }}</div>
                                    <span class="badge bg-warning text-dark font-monospace" style="font-size: 0.7rem;">{{ $member->position }}</span>
                                    <small class="d-block text-muted" style="font-size: 0.65rem;">Urutan: {{ $member->order }}</small>
                                </div>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" onclick="editMember({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ addslashes($member->position) }}', {{ $member->order }})"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('cms.organisasi.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pejabat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">Belum ada pejabat terdaftar.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Tambah Pejabat -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cms.organisasi.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Tambah Pejabat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Pejabat <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Drs. H. Ahmad Sudirman, M.Si" required>
                </div>
                <div class="mb-3">
                    <label for="position" class="form-label">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="position" id="position" class="form-control" placeholder="Contoh: Kepala Dinas" required>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Foto Pejabat</label>
                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="order" class="form-label">Urutan Tampil</label>
                    <input type="number" name="order" id="order" class="form-control" value="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Pejabat -->
<div class="modal fade" id="editMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editMemberForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Edit Pejabat Organisasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Nama Pejabat <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="edit_position" class="form-label">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="position" id="edit_position" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="edit_photo" class="form-label">Ganti Foto (Opsional)</label>
                    <input type="file" name="photo" id="edit_photo" class="form-control" accept="image/*">
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
    function editMember(id, name, position, order) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_position').value = position;
        document.getElementById('edit_order').value = order;
        document.getElementById('editMemberForm').action = "{{ url('/cms/organisasi') }}/" + id;
        const modal = new bootstrap.Modal(document.getElementById('editMemberModal'));
        modal.show();
    }
</script>
@endpush
