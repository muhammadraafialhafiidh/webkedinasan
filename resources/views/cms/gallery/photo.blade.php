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
            <i class="bi bi-plus-circle me-1"></i> Tambah Foto Baru
        </button>
    </div>
</div>

<div class="row g-3">
    @forelse($album->photos as $photo)
        <div class="col-lg-3 col-md-4 col-6">
            <div class="card border-0 shadow-sm overflow-hidden h-100 position-relative">
                @if(($photo->source_type ?? 'upload') === 'instagram')
                    <div class="d-flex flex-column align-items-center justify-content-center text-center p-3 position-relative" 
                         style="aspect-ratio: 4/3; background: linear-gradient(135deg, #405DE6 0%, #5851DB 25%, #833AB4 50%, #C13584 75%, #E1306C 100%);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 shadow-sm" 
                             style="width: 44px; height: 44px; background: rgba(255,255,255,0.22); backdrop-filter: blur(6px); border: 2px solid rgba(255,255,255,0.4);">
                            <i class="bi bi-instagram fs-4 text-white"></i>
                        </div>
                        <span class="badge bg-white text-dark px-2.5 py-1 rounded-pill small fw-bold mb-2 shadow-sm" style="font-size: 0.68rem;">
                            <i class="bi bi-instagram me-1 text-danger"></i>Postingan Instagram
                        </span>
                        <a href="{{ $photo->external_url }}" target="_blank" class="btn btn-sm btn-light rounded-pill py-0.5 px-3 shadow-sm text-truncate" style="font-size: 0.72rem; max-width: 90%;">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Buka Link Post
                        </a>
                    </div>
                @else
                    <img src="{{ asset('storage/' . $photo->image) }}" alt="{{ $photo->title }}" class="w-100" style="aspect-ratio: 4/3; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80'">
                @endif
                <div class="p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <h6 class="fw-bold text-dark mb-0 small text-truncate">{{ $photo->title ?? 'Foto Dokumentasi' }}</h6>
                        @if(($photo->source_type ?? 'upload') === 'instagram')
                            <span class="badge text-white px-2 py-0.5 rounded-pill" style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); font-size: 0.65rem;">IG</span>
                        @else
                            <span class="badge bg-primary px-2 py-0.5 rounded-pill" style="font-size: 0.65rem;">Upload</span>
                        @endif
                    </div>
                    <small class="text-muted d-block mb-2" style="font-size: 0.7rem;">{{ $photo->created_at ? $photo->created_at->format('d/m/Y H:i') : '' }}</small>

                    <div class="d-flex gap-1 pt-1">
                        <button type="button" class="btn btn-sm btn-outline-primary flex-grow-1 js-btn-edit-photo" 
                                data-id="{{ $photo->id }}"
                                data-title="{{ $photo->title ?? '' }}"
                                data-source-type="{{ $photo->source_type ?? 'upload' }}"
                                data-external-url="{{ $photo->external_url ?? '' }}"
                                data-image-url="{{ $photo->image ? asset('storage/' . $photo->image) : '' }}"
                                data-desc="{{ $photo->description ?? '' }}"
                                title="Edit Foto">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </button>
                        <form action="{{ route('cms.galeri.foto.destroy', $photo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus foto ini dari album?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Foto">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 bg-white rounded-3 shadow-sm text-muted">
            <i class="bi bi-image fs-1 text-muted"></i>
            <p class="mt-2">Belum ada foto dalam album ini. Klik tombol di atas untuk menambahkan foto.</p>
        </div>
    @endforelse
</div>

<!-- Modal Tambah / Unggah Foto -->
<div class="modal fade" id="uploadPhotoModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cms.galeri.foto.store', $album->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Tambah Foto ke Album: {{ $album->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="add_source_type" class="form-label fw-semibold">Sumber Foto <span class="text-danger">*</span></label>
                    <select name="source_type" id="add_source_type" class="form-select" onchange="togglePhotoSource('add', this.value)" required>
                        <option value="upload" {{ old('source_type', 'upload') === 'upload' ? 'selected' : '' }}>Upload File Foto (Bisa Banyak / Multiple)</option>
                        <option value="instagram" {{ old('source_type') === 'instagram' ? 'selected' : '' }}>Instagram (URL Postingan / Reel)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="add_title" class="form-label">Judul Foto / Keterangan Singkat</label>
                    <input type="text" name="title" id="add_title" class="form-control" placeholder="Contoh: Foto Penyerahan Bantuan Benih" value="{{ old('title') }}">
                </div>

                <!-- Input Upload Berkas Foto (Existing) -->
                <div class="mb-3" id="add_photo_upload_wrapper">
                    <label for="photos" class="form-label">Pilih Berkas Foto <span class="text-danger">*</span></label>
                    <input type="file" name="photos[]" id="photos" class="form-control" accept="image/*" multiple required>
                    <small class="form-text text-muted">Bisa memilih beberapa foto sekaligus (Multiple upload). Max 10MB per foto.</small>
                </div>

                <!-- Input URL Instagram -->
                <div class="mb-3 d-none" id="add_photo_instagram_wrapper">
                    <label for="add_instagram_url" class="form-label fw-semibold">URL Postingan Instagram <span class="text-danger">*</span></label>
                    <input type="url" name="instagram_url" id="add_instagram_url" class="form-control" placeholder="https://www.instagram.com/p/XXXXXXXX/" value="{{ old('instagram_url') }}" disabled>
                    <small class="form-text text-muted">Contoh: https://www.instagram.com/p/C123456789/ atau https://www.instagram.com/reel/C123456789/</small>
                </div>

                <div class="mb-3">
                    <label for="add_description" class="form-label">Deskripsi Lengkap (Opsional)</label>
                    <textarea name="description" id="add_description" rows="3" class="form-control" data-no-ckeditor>{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Simpan Foto</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Foto -->
<div class="modal fade" id="editPhotoModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editPhotoForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Edit Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_source_type" class="form-label fw-semibold">Sumber Foto <span class="text-danger">*</span></label>
                    <select name="source_type" id="edit_source_type" class="form-select" onchange="togglePhotoSource('edit', this.value)" required>
                        <option value="upload">Upload File Foto</option>
                        <option value="instagram">Instagram (URL Postingan / Reel)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="edit_photo_title" class="form-label">Judul Foto</label>
                    <input type="text" name="title" id="edit_photo_title" class="form-control">
                </div>

                <!-- Wrapper Upload Foto pada Edit -->
                <div class="mb-3" id="edit_photo_upload_wrapper">
                    <div id="edit_current_preview_box" class="mb-2 d-none">
                        <label class="form-label small text-muted d-block">Foto Saat Ini:</label>
                        <img id="edit_photo_img_preview" src="" alt="Preview" class="rounded border" style="max-height: 120px; object-fit: cover;">
                    </div>
                    <label for="edit_file_photo" class="form-label">Ganti Berkas Foto</label>
                    <input type="file" name="photo" id="edit_file_photo" class="form-control" accept="image/*">
                    <small class="form-text text-muted" id="edit_photo_help_text">Biarkan kosong jika tidak ingin mengganti file foto.</small>
                </div>

                <!-- Wrapper Instagram pada Edit -->
                <div class="mb-3 d-none" id="edit_photo_instagram_wrapper">
                    <label for="edit_instagram_url" class="form-label fw-semibold">URL Postingan Instagram <span class="text-danger">*</span></label>
                    <input type="url" name="instagram_url" id="edit_instagram_url" class="form-control" placeholder="https://www.instagram.com/p/XXXXXXXX/" disabled>
                    <small class="form-text text-muted">Contoh: https://www.instagram.com/p/C123456789/</small>
                </div>

                <div class="mb-3">
                    <label for="edit_photo_desc" class="form-label">Deskripsi Lengkap (Opsional)</label>
                    <textarea name="description" id="edit_photo_desc" rows="3" class="form-control" data-no-ckeditor></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Perbarui Foto</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function togglePhotoSource(prefix, type) {
        const uploadWrapper = document.getElementById(prefix + '_photo_upload_wrapper');
        const igWrapper = document.getElementById(prefix + '_photo_instagram_wrapper');
        const uploadInput = document.getElementById(prefix === 'add' ? 'photos' : 'edit_file_photo');
        const igInput = document.getElementById(prefix + '_instagram_url');

        if (type === 'upload') {
            if (uploadWrapper) uploadWrapper.classList.remove('d-none');
            if (igWrapper) igWrapper.classList.add('d-none');
            if (uploadInput) {
                uploadInput.disabled = false;
                if (prefix === 'add') {
                    uploadInput.setAttribute('required', 'required');
                }
            }
            if (igInput) {
                igInput.disabled = true;
                igInput.removeAttribute('required');
            }
        } else if (type === 'instagram') {
            if (uploadWrapper) uploadWrapper.classList.add('d-none');
            if (igWrapper) igWrapper.classList.remove('d-none');
            if (uploadInput) {
                uploadInput.disabled = true;
                uploadInput.removeAttribute('required');
            }
            if (igInput) {
                igInput.disabled = false;
                igInput.setAttribute('required', 'required');
            }
        }
    }

    function editPhoto(id, title, sourceType, externalUrl, imageUrl, desc) {
        document.getElementById('edit_photo_title').value = title;
        document.getElementById('edit_photo_desc').value = desc;

        const editSelect = document.getElementById('edit_source_type');
        if (editSelect) {
            editSelect.value = sourceType === 'instagram' ? 'instagram' : 'upload';
        }

        const previewBox = document.getElementById('edit_current_preview_box');
        const imgPreview = document.getElementById('edit_photo_img_preview');
        const helpText = document.getElementById('edit_photo_help_text');
        const fileInput = document.getElementById('edit_file_photo');

        if (sourceType === 'instagram') {
            document.getElementById('edit_instagram_url').value = externalUrl;
            togglePhotoSource('edit', 'instagram');
            previewBox.classList.add('d-none');
            if (fileInput) fileInput.removeAttribute('required');
        } else {
            togglePhotoSource('edit', 'upload');
            if (imageUrl) {
                imgPreview.src = imageUrl;
                previewBox.classList.remove('d-none');
                helpText.textContent = 'Biarkan kosong jika tidak ingin mengganti file foto.';
                if (fileInput) fileInput.removeAttribute('required');
            } else {
                previewBox.classList.add('d-none');
            }
        }

        document.getElementById('editPhotoForm').action = "{{ url('/cms/galeri/foto') }}/" + id;
        const modal = new bootstrap.Modal(document.getElementById('editPhotoModal'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addSourceSelect = document.getElementById('add_source_type');
        if (addSourceSelect) {
            togglePhotoSource('add', addSourceSelect.value);
        }

        document.querySelectorAll('.js-btn-edit-photo').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const title = this.dataset.title || '';
                const sourceType = this.dataset.sourceType || 'upload';
                const externalUrl = this.dataset.externalUrl || '';
                const imageUrl = this.dataset.imageUrl || '';
                const desc = this.dataset.desc || '';
                editPhoto(id, title, sourceType, externalUrl, imageUrl, desc);
            });
        });
    });
</script>
@endpush
