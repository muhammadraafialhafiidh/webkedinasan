@extends('layouts.cms')

@section('title', 'Manajemen Banner / Slider — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Banner / Slider</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Manajemen Banner Hero / Slider</h3>
        <p class="text-muted small mb-0">Kelola gambar spanduk slider beranda website utama</p>
    </div>
    <button class="btn btn-primary font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addBannerModal">
        <i class="bi bi-image me-1"></i> Tambah Banner Baru
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">Urutan</th>
                    <th style="width: 140px;">Gambar Preview</th>
                    <th>Judul Banner</th>
                    <th>Tautan (Link)</th>
                    <th>Status Aktif</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td class="fw-bold text-center">{{ $banner->order }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="preview" class="rounded border" style="width: 120px; height: 50px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1534951009808-766178b47a4f?auto=format&fit=crop&w=300&q=80'">
                        </td>
                        <td class="fw-bold text-primary">{{ $banner->title ?? 'Tanpa Judul' }}</td>
                        <td class="small text-muted">{{ $banner->link ?? '-' }}</td>
                        <td>
                            @if($banner->is_active)
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-action-group">
                                <form action="{{ route('cms.banner.toggle', $banner->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-action btn-action-warning" data-bs-toggle="tooltip" data-bs-title="{{ $banner->is_active ? 'Nonaktifkan Banner' : 'Aktifkan Banner' }}">
                                        <i class="bi {{ $banner->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-action btn-action-edit" onclick="editBanner({{ $banner->id }}, '{{ addslashes($banner->title) }}', '{{ addslashes($banner->link) }}', {{ $banner->order }}, {{ $banner->is_active ? 1 : 0 }})" data-bs-toggle="tooltip" data-bs-title="Edit Data">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('cms.banner.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus banner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada banner slider.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Banner -->
<div class="modal fade" id="addBannerModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cms.banner.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Tambah Banner Slider Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Banner</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: Selamat Datang di Portal Perikanan">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Banner <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Rekomendasi rasio 1920x600px. Max 10MB.</small>
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link Tujuan Klik (Opsional)</label>
                    <input type="url" name="link" id="link" class="form-control" placeholder="https://...">
                </div>
                <div class="mb-3">
                    <label for="order" class="form-label">Urutan Tampil</label>
                    <input type="number" name="order" id="order" class="form-control" value="0">
                </div>
                <div class="mb-3">
                    <label for="is_active" class="form-label">Status Aktif <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-select" required>
                        <option value="1">Aktif (Tampil di Hero Slider)</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Simpan Banner</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Banner -->
<div class="modal fade" id="editBannerModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editBannerForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Edit Banner Slider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_title" class="form-label">Judul Banner</label>
                    <input type="text" name="title" id="edit_title" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="edit_image" class="form-label">Ganti Gambar (Opsional)</label>
                    <input type="file" name="image" id="edit_image" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="edit_link" class="form-label">Link Tujuan Klik</label>
                    <input type="url" name="link" id="edit_link" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="edit_order" class="form-label">Urutan Tampil</label>
                    <input type="number" name="order" id="edit_order" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="edit_is_active" class="form-label">Status Aktif <span class="text-danger">*</span></label>
                    <select name="is_active" id="edit_is_active" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
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
    function editBanner(id, title, link, order, isActive) {
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_link').value = link;
        document.getElementById('edit_order').value = order;
        document.getElementById('edit_is_active').value = isActive;
        document.getElementById('editBannerForm').action = "{{ url('/cms/banner') }}/" + id;
        const modal = new bootstrap.Modal(document.getElementById('editBannerModal'));
        modal.show();
    }
</script>
@endpush
