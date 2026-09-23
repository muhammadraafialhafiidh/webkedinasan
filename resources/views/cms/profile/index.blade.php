@extends('layouts.cms')

@section('title', 'Kelola Profil Dinas — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Profil Dinas</li>
@endsection

@section('content')

<style>
    .drag-handle {
        cursor: grab;
        user-select: none;
        touch-action: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: color 0.15s ease, background-color 0.15s ease;
    }
    .drag-handle:hover {
        color: var(--primary, #003F88) !important;
        background-color: rgba(0, 63, 136, 0.08);
    }
    .sortable-ghost {
        opacity: 0.35;
        background-color: #e0f2fe !important;
        border: 2px dashed #0284c7 !important;
    }
    .sortable-chosen {
        background-color: #ffffff !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
    }
    .sortable-drag {
        opacity: 0.95;
        cursor: grabbing !important;
    }
    .sortable-drag .drag-handle {
        cursor: grabbing !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Pengaturan Profil Dinas</h3>
        <p class="text-muted small mb-0">Kelola Sejarah, Visi, Misi, Tugas Pokok & Fungsi (Tupoksi), serta Struktur Pejabat Dinas</p>
    </div>
</div>

<div class="row g-4">
    <!-- Main Form (8 col) -->
    <div class="col-lg-8">
        <form action="{{ route('cms.profil.update') }}" method="POST">
            @csrf
            @method('PUT')

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
        </form>
    </div>

    <!-- Sidebar Pejabat Organisasi (4 col) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 80px;">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold text-primary mb-0"><i class="bi bi-people me-2"></i>Struktur Pejabat</h5>
                    <small class="text-muted" style="font-size: 0.72rem;"><i class="bi bi-arrows-move me-1"></i>Tarik & geser ikon untuk atur posisi</small>
                </div>
                <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="bi bi-plus-lg me-1"></i> Tambah
                </button>
            </div>

            <div class="d-flex flex-column gap-2" id="sortableMemberList">
                @forelse($organizationMembers as $member)
                    <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between sortable-member-item" data-id="{{ $member->id }}" style="transition: transform 0.15s ease, box-shadow 0.15s ease;">
                        <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                            <div class="drag-handle text-muted px-1 py-2 me-1" role="button" title="Geser untuk mengatur urutan">
                                <i class="bi bi-grip-vertical fs-5"></i>
                            </div>
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="rounded-circle border flex-shrink-0" style="width: 44px; height: 44px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=003F88&color=ffffff'">
                            <div class="overflow-hidden">
                                <div class="fw-bold small text-dark text-truncate mb-0" title="{{ $member->name }}">{{ $member->name }}</div>
                                <span class="badge bg-warning text-dark font-monospace text-truncate d-inline-block max-w-100" style="font-size: 0.7rem;">{{ $member->position }}</span>
                            </div>
                        </div>
                        <div class="btn-action-group d-flex align-items-center gap-1 flex-shrink-0">
                            {{-- Edit Button --}}
                            <button type="button" class="btn btn-action btn-action-edit" onclick="editMember({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ addslashes($member->position) }}')" data-bs-toggle="tooltip" data-bs-title="Edit Data">
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{-- Delete Button --}}
                            <form action="{{ route('cms.organisasi.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pejabat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data">
                                    <i class="bi bi-trash"></i>
                                </button>
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    function editMember(id, name, position) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_position').value = position;
        document.getElementById('editMemberForm').action = "{{ url('/cms/organisasi') }}/" + id;
        const modal = new bootstrap.Modal(document.getElementById('editMemberModal'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const listEl = document.getElementById('sortableMemberList');
        if (!listEl) return;

        let previousOrder = Array.from(listEl.querySelectorAll('.sortable-member-item')).map(el => el.dataset.id);

        new Sortable(listEl, {
            handle: '.drag-handle',
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onStart: function () {
                previousOrder = Array.from(listEl.querySelectorAll('.sortable-member-item')).map(el => el.dataset.id);
            },
            onEnd: function (evt) {
                if (evt.oldIndex === evt.newIndex) return;

                const currentOrder = Array.from(listEl.querySelectorAll('.sortable-member-item')).map(el => parseInt(el.dataset.id));

                fetch("{{ route('cms.organisasi.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order: currentOrder })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        if (window.showCmsToast) {
                            window.showCmsToast('success', data.message || 'Urutan struktur pejabat berhasil diperbarui.');
                        }
                        previousOrder = currentOrder.map(String);
                    } else {
                        throw new Error(data.message || 'Gagal menyimpan urutan.');
                    }
                })
                .catch(err => {
                    console.error('Reorder error:', err);
                    if (window.showCmsToast) {
                        window.showCmsToast('danger', 'Urutan gagal diperbarui. Silakan coba lagi.');
                    }
                    // Restore previous DOM order
                    previousOrder.forEach(id => {
                        const item = listEl.querySelector(`.sortable-member-item[data-id="${id}"]`);
                        if (item) listEl.appendChild(item);
                    });
                });
            }
        });
    });
</script>
@endpush
