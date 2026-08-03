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
                                <button type="button" class="btn btn-action btn-action-edit" onclick="editVideo({{ $video->id }}, '{{ addslashes(strip_tags($video->title)) }}', '{{ $video->source_type ?? 'youtube' }}', '{{ addslashes($video->url ?? '') }}', '{{ addslashes(strip_tags($video->description ?? '')) }}')" data-bs-toggle="tooltip" data-bs-title="Edit Data">
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
                    <input type="text" name="title" id="add_title" class="form-control" placeholder="Contoh: Dokumentasi Panen Raya Perikanan" required>
                </div>

                <div class="mb-3">
                    <label for="add_source_type" class="form-label fw-semibold">Tipe Sumber Video <span class="text-danger">*</span></label>
                    <select name="source_type" id="add_source_type" class="form-select" onchange="toggleSourceType('add', this.value)" required>
                        <option value="youtube">YouTube (URL Video)</option>
                        <option value="instagram">Instagram (URL Reel / Post)</option>
                        <option value="file">Upload File Video Sendiri (MP4 / WebM / MOV)</option>
                    </select>
                </div>

                <!-- Input URL YouTube -->
                <div class="mb-3" id="add_url_youtube_wrapper">
                    <label for="add_url_youtube" class="form-label fw-semibold">URL Video YouTube <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="add_url_youtube" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                    <div class="form-text">Contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ</div>
                </div>

                <!-- Input URL Instagram -->
                <div class="mb-3 d-none" id="add_url_instagram_wrapper">
                    <label for="add_url_instagram" class="form-label fw-semibold">URL Instagram Reel / Post <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="add_url_instagram" class="form-control" placeholder="https://www.instagram.com/reel/...">
                    <div class="form-text">Contoh: https://www.instagram.com/reel/C123456789/ atau https://www.instagram.com/p/C123456789/</div>
                </div>

                <!-- Input File Video Upload -->
                <div class="mb-3 d-none" id="add_file_wrapper">
                    <label for="add_video_file" class="form-label fw-semibold">File Video <span class="text-danger">*</span></label>
                    <input type="file" name="video_file" id="add_video_file" class="form-control" accept="video/mp4,video/webm,video/ogg,video/quicktime">
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
                    <textarea name="description" id="add_description" rows="3" class="form-control" data-no-ckeditor></textarea>
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
                        <option value="instagram">Instagram (URL Reel / Post)</option>
                        <option value="file">Upload File Video Sendiri (MP4 / WebM / MOV)</option>
                    </select>
                </div>

                <!-- Input URL YouTube -->
                <div class="mb-3" id="edit_url_youtube_wrapper">
                    <label for="edit_url_youtube" class="form-label fw-semibold">URL Video YouTube <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="edit_url_youtube" class="form-control">
                </div>

                <!-- Input URL Instagram -->
                <div class="mb-3 d-none" id="edit_url_instagram_wrapper">
                    <label for="edit_url_instagram" class="form-label fw-semibold">URL Instagram Reel / Post <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="edit_url_instagram" class="form-control">
                </div>

                <!-- Input File Video Upload -->
                <div class="mb-3 d-none" id="edit_file_wrapper">
                    <label for="edit_video_file" class="form-label fw-semibold">Ganti File Video (Opsional)</label>
                    <input type="file" name="video_file" id="edit_video_file" class="form-control" accept="video/mp4,video/webm,video/ogg,video/quicktime">
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
        const igWrapper = document.getElementById(prefix + '_url_instagram_wrapper');
        const fileWrapper = document.getElementById(prefix + '_file_wrapper');

        const ytInput = document.getElementById(prefix + '_url_youtube');
        const igInput = document.getElementById(prefix + '_url_instagram');
        const fileInput = document.getElementById(prefix + '_video_file');

        // Hide all
        ytWrapper.classList.add('d-none');
        igWrapper.classList.add('d-none');
        fileWrapper.classList.add('d-none');

        // Disable required inputs initially
        if (ytInput) ytInput.removeAttribute('required');
        if (igInput) igInput.removeAttribute('required');
        if (fileInput) fileInput.removeAttribute('required');

        if (type === 'youtube') {
            ytWrapper.classList.remove('d-none');
            if (ytInput) ytInput.setAttribute('required', 'required');
        } else if (type === 'instagram') {
            igWrapper.classList.remove('d-none');
            if (igInput) igInput.setAttribute('required', 'required');
        } else if (type === 'file') {
            fileWrapper.classList.remove('d-none');
            if (prefix === 'add' && fileInput) {
                fileInput.setAttribute('required', 'required');
            }
        }
    }

    function editVideo(id, title, sourceType, url, desc) {
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_source_type').value = sourceType;
        
        toggleSourceType('edit', sourceType);

        if (sourceType === 'youtube') {
            document.getElementById('edit_url_youtube').value = url;
        } else if (sourceType === 'instagram') {
            document.getElementById('edit_url_instagram').value = url;
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
        toggleSourceType('add', 'youtube');
    });
</script>
@endpush
