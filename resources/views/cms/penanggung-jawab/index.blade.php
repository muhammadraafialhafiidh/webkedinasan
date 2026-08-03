@extends('layouts.cms')

@section('title', 'Penanggung Jawab Layanan — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.layanan.index') }}" class="text-decoration-none text-muted">Layanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Penanggung Jawab</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-primary mb-1">Master Penanggung Jawab Layanan</h3>
        <p class="text-muted small mb-0">Kelola data petugas penanggung jawab operasional Standar Pelayanan Publik</p>
    </div>
    <button class="btn btn-primary font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addOfficerModal">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Penanggung Jawab
    </button>
</div>

<!-- Search Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('cms.penanggung-jawab.index') }}" method="GET" class="row g-2">
            <div class="col-md-9">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama atau nomor HP penanggung jawab..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="{{ route('cms.penanggung-jawab.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                    <th>Nama Penanggung Jawab</th>
                    <th>Nomor HP / WhatsApp</th>
                    <th>Jumlah Layanan</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($officers as $index => $officer)
                    <tr>
                        <td>{{ $officers->firstItem() + $index }}</td>
                        <td class="fw-bold text-primary">{{ $officer->nama }}</td>
                        <td>
                            <span class="font-monospace small text-dark"><i class="bi bi-telephone me-1 text-success"></i>{{ $officer->nomor_hp }}</span>
                            @if($officer->wa_link)
                                <a href="{{ $officer->wa_link }}" target="_blank" class="badge bg-success text-decoration-none ms-1" title="Tes Chat WhatsApp">
                                    <i class="bi bi-whatsapp me-1"></i>WA
                                </a>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $officer->services_count }} Layanan</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-action-group">
                                <button type="button" class="btn btn-action btn-action-edit" onclick="editOfficer({{ $officer->id }}, '{{ addslashes($officer->nama) }}', '{{ addslashes($officer->nomor_hp) }}')" data-bs-toggle="tooltip" data-bs-title="Edit Data">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('cms.penanggung-jawab.destroy', $officer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penanggung jawab ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data penanggung jawab.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-center mb-0">
            {{ $officers->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addOfficerModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cms.penanggung-jawab.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Tambah Penanggung Jawab Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap beserta Gelar <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: Hengky Prabowo, S.Pi" required>
                </div>
                <div class="mb-3">
                    <label for="nomor_hp" class="form-label">Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                    <input type="text" name="nomor_hp" id="nomor_hp" class="form-control" placeholder="Contoh: 081234567890" required>
                    <small class="form-text text-muted">Format contoh: 081234567890 atau +6281234567890.</small>
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
<div class="modal fade" id="editOfficerModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editOfficerForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Edit Penanggung Jawab</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="edit_nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="edit_nomor_hp" class="form-label">Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                    <input type="text" name="nomor_hp" id="edit_nomor_hp" class="form-control" required>
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
    function editOfficer(id, nama, nomorHp) {
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_nomor_hp').value = nomorHp;
        document.getElementById('editOfficerForm').action = "{{ url('/cms/penanggung-jawab') }}/" + id;
        const modal = new bootstrap.Modal(document.getElementById('editOfficerModal'));
        modal.show();
    }
</script>
@endpush
