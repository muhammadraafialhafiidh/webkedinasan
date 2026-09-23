@extends('layouts.cms')

@section('title', 'Galeri Video — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.galeri.album.index') }}" class="text-decoration-none text-muted">Galeri</a></li>
    <li class="breadcrumb-item active" aria-current="page">Galeri Video</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Manajemen Galeri Video</h3>
        <p class="text-muted small mb-0">Kelola video dari YouTube, Instagram, atau unggah file video langsung</p>
    </div>
    <button class="btn btn-primary font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addVideoModal">
        <i class="bi bi-plus-circle me-1"></i> Tambah Video Baru
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Judul Video</th>
                    <th>Tipe Sumber</th>
                    <th>URL / File Video</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Input</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($videos as $index => $video)
                    <tr>
                        <td>{{ $videos->firstItem() + $index }}</td>
                        <td class="fw-bold text-primary">{{ $video->title }}</td>
                        <td>
                            @if(($video->source_type ?? 'youtube') === 'youtube')
                                <span class="badge bg-danger"><i class="bi bi-youtube me-1"></i>YouTube</span>
                            @elseif($video->source_type === 'google_drive')
                                <span class="badge bg-success"><i class="bi bi-google me-1"></i>Google Drive</span>
                            @elseif($video->source_type === 'instagram')
                                <span class="badge text-white" style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);"><i class="bi bi-instagram me-1"></i>Instagram</span>
                            @else
                                <span class="badge bg-primary"><i class="bi bi-file-earmark-play me-1"></i>Upload Sendiri</span>
                            @endif
                        </td>
                        <td>
                            @if($video->source_type === 'file' && $video->video_file)
                                <a href="{{ asset('storage/' . $video->video_file) }}" target="_blank" class="text-decoration-none small text-truncate d-inline-block" style="max-width: 250px;">
                                    <i class="bi bi-play-circle me-1"></i>Lihat Video Lokal
                                </a>
                            @elseif($video->url)
                                <a href="{{ $video->url }}" target="_blank" class="text-decoration-none small text-truncate d-inline-block" style="max-width: 250px;">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>{{ $video->url }}
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="small text-muted" style="max-width: 250px;">{{ Str::limit(strip_tags($video->description), 80) }}</td>
                        <td class="small text-muted">{{ $video->created_at ? $video->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            <div class="btn-action-group">
                                <button type="button" class="btn btn-action btn-action-edit js-btn-edit-video" 
                                        data-id="{{ $video->id }}"
                                        data-title="{{ $video->title }}"
                                        data-source-type="{{ $video->source_type ?? 'youtube' }}"
                                        data-url="{{ $video->url ?? '' }}"
                                        data-desc="{{ $video->description ?? '' }}"
                                        data-bs-toggle="tooltip" data-bs-title="Edit Data">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('cms.galeri.video.destroy', $video->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus video ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data video.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-center mb-0">
            {{ $videos->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addVideoModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cms.galeri.video.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Tambah Video Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="add_title" class="form-label fw-semibold">Judul Video <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="add_title" class="form-control" placeholder="Contoh: Dokumentasi Panen Raya Perikanan" value="{{ old('title') }}" required>
                </div>

                <div class="mb-3">
                    <label for="add_source_type" class="form-label fw-semibold">Tipe Sumber Video <span class="text-danger">*</span></label>
                    <select name="source_type" id="add_source_type" class="form-select" onchange="toggleSourceType('add', this.value)" required>
                        <option value="youtube" {{ old('source_type', 'youtube') === 'youtube' ? 'selected' : '' }}>YouTube (URL Video)</option>
                        <option value="google_drive" {{ old('source_type') === 'google_drive' ? 'selected' : '' }}>Google Drive (Link Berbagi Video)</option>
                        <option value="instagram" {{ old('source_type') === 'instagram' ? 'selected' : '' }}>Instagram (URL Reel / Post)</option>
                        <option value="file" {{ old('source_type') === 'file' ? 'selected' : '' }}>Upload File Video Sendiri (MP4 / WebM / MOV)</option>
                    </select>
                </div>

                <!-- Input URL YouTube -->
                <div class="mb-3" id="add_url_youtube_wrapper">
                    <label for="add_url_youtube" class="form-label fw-semibold">URL Video YouTube <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="add_url_youtube" class="form-control" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('source_type', 'youtube') === 'youtube' ? old('url') : '' }}">
                    <div class="form-text">Contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ</div>
                </div>

                <!-- Input URL Google Drive -->
                <div class="mb-3 d-none" id="add_url_gdrive_wrapper">
                    <label for="add_url_gdrive" class="form-label fw-semibold">Link Google Drive <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="add_url_gdrive" class="form-control" placeholder="https://drive.google.com/file/d/FILE_ID/view" value="{{ old('source_type') === 'google_drive' ? old('url') : '' }}" disabled>
                    <div class="form-text">Contoh: https://drive.google.com/file/d/1ABCXYZ_123/view</div>
                    <div class="alert alert-info py-2 px-3 mt-2 mb-0 small">
                        <i class="bi bi-info-circle me-1"></i> <strong>Penting:</strong> Pastikan file Google Drive memiliki akses <em>"Siapa saja yang memiliki link"</em> (Anyone with the link can view). Jika file bersifat private, video tidak dapat ditampilkan pada halaman publik.
                    </div>
                </div>

                <!-- Input URL Instagram -->
                <div class="mb-3 d-none" id="add_url_instagram_wrapper">
                    <label for="add_url_instagram" class="form-label fw-semibold">URL Instagram Reel / Post <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="add_url_instagram" class="form-control" placeholder="https://www.instagram.com/reel/..." value="{{ old('source_type') === 'instagram' ? old('url') : '' }}" disabled>
                    <div class="form-text">Contoh: https://www.instagram.com/reel/C123456789/ atau https://www.instagram.com/p/C123456789/</div>
                </div>

                <!-- Input File Video Upload -->
                <div class="mb-3 d-none" id="add_file_wrapper">
                    <label for="add_video_file" class="form-label fw-semibold">File Video <span class="text-danger">*</span></label>
                    <input type="file" name="video_file" id="add_video_file" class="form-control" accept="video/mp4,video/webm,video/ogg,video/quicktime" disabled>
                    <div class="form-text">Format didukung: MP4, WebM, MOV, OGG. Maksimal ukuran file: 100MB.</div>
                </div>

                <!-- Input Thumbnail opsional -->
                <div class="mb-3">
                    <label for="add_thumbnail" class="form-label fw-semibold">Custom Cover / Thumbnail (Opsional)</label>
                    <input type="file" name="thumbnail" id="add_thumbnail" class="form-control" accept="image/*">
                    <div class="form-text">Gambar sampul khusus jika tidak menggunakan thumbnail otomatis.</div>
                </div>

                <div class="mb-3">
                    <label for="add_description" class="form-label fw-semibold">Deskripsi Singkat</label>
                    <textarea name="description" id="add_description" rows="3" class="form-control" data-no-ckeditor>{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Simpan Video</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editVideoModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editVideoForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Edit Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_title" class="form-label fw-semibold">Judul Video <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="edit_title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="edit_source_type" class="form-label fw-semibold">Tipe Sumber Video <span class="text-danger">*</span></label>
                    <select name="source_type" id="edit_source_type" class="form-select" onchange="toggleSourceType('edit', this.value)" required>
                        <option value="youtube">YouTube (URL Video)</option>
                        <option value="google_drive">Google Drive (Link Berbagi Video)</option>
                        <option value="instagram">Instagram (URL Reel / Post)</option>
                        <option value="file">Upload File Video Sendiri (MP4 / WebM / MOV)</option>
                    </select>
                </div>

                <!-- Input URL YouTube -->
                <div class="mb-3" id="edit_url_youtube_wrapper">
                    <label for="edit_url_youtube" class="form-label fw-semibold">URL Video YouTube <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="edit_url_youtube" class="form-control">
                </div>

                <!-- Input URL Google Drive -->
                <div class="mb-3 d-none" id="edit_url_gdrive_wrapper">
                    <label for="edit_url_gdrive" class="form-label fw-semibold">Link Google Drive <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="edit_url_gdrive" class="form-control" placeholder="https://drive.google.com/file/d/FILE_ID/view" disabled>
                    <div class="alert alert-info py-2 px-3 mt-2 mb-0 small">
                        <i class="bi bi-info-circle me-1"></i> <strong>Penting:</strong> Pastikan file Google Drive memiliki akses publik yang memungkinkan pengguna melihat file.
                    </div>
                </div>

                <!-- Input URL Instagram -->
                <div class="mb-3 d-none" id="edit_url_instagram_wrapper">
                    <label for="edit_url_instagram" class="form-label fw-semibold">URL Instagram Reel / Post <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="edit_url_instagram" class="form-control" disabled>
                </div>

                <!-- Input File Video Upload -->
                <div class="mb-3 d-none" id="edit_file_wrapper">
                    <label for="edit_video_file" class="form-label fw-semibold">Ganti File Video (Opsional)</label>
                    <input type="file" name="video_file" id="edit_video_file" class="form-control" accept="video/mp4,video/webm,video/ogg,video/quicktime" disabled>
                    <div class="form-text">Biarkan kosong jika tidak ingin mengganti file video yang ada.</div>
                </div>

                <!-- Input Thumbnail opsional -->
                <div class="mb-3">
                    <label for="edit_thumbnail" class="form-label fw-semibold">Ganti Thumbnail (Opsional)</label>
                    <input type="file" name="thumbnail" id="edit_thumbnail" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="edit_desc" class="form-label fw-semibold">Deskripsi Singkat</label>
                    <textarea name="description" id="edit_desc" rows="3" class="form-control" data-no-ckeditor></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Perbarui Video</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleSourceType(prefix, type) {
        const ytWrapper = document.getElementById(prefix + '_url_youtube_wrapper');
        const gdriveWrapper = document.getElementById(prefix + '_url_gdrive_wrapper');
        const igWrapper = document.getElementById(prefix + '_url_instagram_wrapper');
        const fileWrapper = document.getElementById(prefix + '_file_wrapper');

        const ytInput = document.getElementById(prefix + '_url_youtube');
        const gdriveInput = document.getElementById(prefix + '_url_gdrive');
        const igInput = document.getElementById(prefix + '_url_instagram');
        const fileInput = document.getElementById(prefix + '_video_file');

        // Hide all
        if (ytWrapper) ytWrapper.classList.add('d-none');
        if (gdriveWrapper) gdriveWrapper.classList.add('d-none');
        if (igWrapper) igWrapper.classList.add('d-none');
        if (fileWrapper) fileWrapper.classList.add('d-none');

        // Disable all inactive inputs and remove required so unselected inputs are NOT submitted
        if (ytInput) {
            ytInput.disabled = true;
            ytInput.removeAttribute('required');
        }
        if (gdriveInput) {
            gdriveInput.disabled = true;
            gdriveInput.removeAttribute('required');
        }
        if (igInput) {
            igInput.disabled = true;
            igInput.removeAttribute('required');
        }
        if (fileInput) {
            fileInput.disabled = true;
            fileInput.removeAttribute('required');
        }

        // Enable and show ONLY selected source input
        if (type === 'youtube') {
            if (ytWrapper) ytWrapper.classList.remove('d-none');
            if (ytInput) {
                ytInput.disabled = false;
                ytInput.setAttribute('required', 'required');
            }
        } else if (type === 'google_drive') {
            if (gdriveWrapper) gdriveWrapper.classList.remove('d-none');
            if (gdriveInput) {
                gdriveInput.disabled = false;
                gdriveInput.setAttribute('required', 'required');
            }
        } else if (type === 'instagram') {
            if (igWrapper) igWrapper.classList.remove('d-none');
            if (igInput) {
                igInput.disabled = false;
                igInput.setAttribute('required', 'required');
            }
        } else if (type === 'file') {
            if (fileWrapper) fileWrapper.classList.remove('d-none');
            if (fileInput) {
                fileInput.disabled = false;
                if (prefix === 'add') {
                    fileInput.setAttribute('required', 'required');
                }
            }
        }
    }

    function editVideo(id, title, sourceType, url, desc) {
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_source_type').value = sourceType;
        
        toggleSourceType('edit', sourceType);

        if (sourceType === 'youtube') {
            const ytInput = document.getElementById('edit_url_youtube');
            if (ytInput) ytInput.value = url;
        } else if (sourceType === 'google_drive') {
            const gdriveInput = document.getElementById('edit_url_gdrive');
            if (gdriveInput) gdriveInput.value = url;
        } else if (sourceType === 'instagram') {
            const igInput = document.getElementById('edit_url_instagram');
            if (igInput) igInput.value = url;
        }

        document.getElementById('edit_desc').value = desc;
        if (window.setEditorData) {
            window.setEditorData('edit_desc', desc);
        }
        
        document.getElementById('editVideoForm').action = "{{ url('/cms/galeri/video') }}/" + id;
        const modal = new bootstrap.Modal(document.getElementById('editVideoModal'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addSourceSelect = document.getElementById('add_source_type');
        const initialType = addSourceSelect ? addSourceSelect.value : 'youtube';
        toggleSourceType('add', initialType);

        document.querySelectorAll('.js-btn-edit-video').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const title = this.dataset.title || '';
                const sourceType = this.dataset.sourceType || 'youtube';
                const url = this.dataset.url || '';
                const desc = this.dataset.desc || '';
                editVideo(id, title, sourceType, url, desc);
            });
        });
    });
</script>
@endpush
