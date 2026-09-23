@extends('layouts.cms')

@section('title', 'Manajemen Banner / Slider — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Banner / Slider</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Manajemen Banner Hero / Slider</h3>
        <p class="text-muted small mb-0">Kelola gambar spanduk slider beranda website utama secara fleksibel</p>
    </div>
    <button class="btn btn-primary font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addBannerModal">
        <i class="bi bi-plus-circle me-1"></i> Tambah Banner Baru
    </button>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
        <div class="d-flex gap-2 align-items-start">
            <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
            <div>
                <strong class="d-block mb-1">Terjadi kesalahan input:</strong>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 140px;">Gambar Preview</th>
                    <th>Judul Banner</th>
                    <th>Tautan Tujuan</th>
                    <th>Sumber Gambar</th>
                    <th>Status Aktif</th>
                    <th class="text-center" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>
                            <img src="{{ $banner->image_url }}" alt="preview" class="rounded border shadow-xs" style="width: 120px; height: 50px; object-fit: cover;" onerror="this.onerror=null; this.src='{!! \App\Models\Banner::DEFAULT_PLACEHOLDER !!}';">
                        </td>
                        <td>
                            <span class="fw-bold text-primary d-block">{{ $banner->title ?? 'Tanpa Judul' }}</span>
                        </td>
                        <td>
                            @if($banner->link_type === 'berita')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-newspaper"></i> Berita: {{ Str::limit($banner->news->title ?? 'Berita Terkait', 30) }}
                                </span>
                                @if($banner->news && $banner->news->slug)
                                    <small class="d-block text-muted font-monospace mt-1" style="font-size: 0.75rem;">/berita/{{ $banner->news->slug }}</small>
                                @endif
                            @elseif($banner->link_type === 'pelayanan')
                                <span class="badge bg-info-subtle text-info border border-info-subtle d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-grid-fill"></i> Layanan: {{ Str::limit($banner->service->title ?? 'Layanan Terkait', 30) }}
                                </span>
                                @if($banner->service && $banner->service->slug)
                                    <small class="d-block text-muted font-monospace mt-1" style="font-size: 0.75rem;">/layanan/{{ $banner->service->slug }}</small>
                                @endif
                            @elseif($banner->link_type === 'external')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle d-inline-flex align-items-center gap-1 mb-1">
                                    <i class="bi bi-box-arrow-up-right"></i> Link Eksternal
                                </span>
                                <small class="d-block text-muted text-truncate" style="max-width: 200px; font-size: 0.75rem;">
                                    <a href="{{ $banner->external_url ?: $banner->link }}" target="_blank" rel="noopener noreferrer" class="text-muted text-decoration-none">
                                        {{ $banner->external_url ?: $banner->link }}
                                    </a>
                                </small>
                            @else
                                <span class="text-muted small"><i class="bi bi-dash-circle me-1"></i>Tidak ada tautan</span>
                            @endif
                        </td>
                        <td>
                            @if($banner->image_source === 'content')
                                <span class="badge bg-light text-primary border border-primary-subtle">
                                    <i class="bi bi-collection me-1"></i>Gambar Konten ({{ ucfirst($banner->link_type) }})
                                </span>
                            @else
                                <span class="badge bg-light text-muted border">
                                    <i class="bi bi-image me-1"></i>Gambar Khusus
                                </span>
                            @endif
                        </td>
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
                                <button type="button" class="btn btn-action btn-action-edit" onclick="editBanner({{ json_encode([
                                    'id' => $banner->id,
                                    'title' => $banner->title ?? '',
                                    'link_type' => $banner->link_type ?? 'none',
                                    'news_id' => $banner->news_id,
                                    'service_id' => $banner->service_id,
                                    'external_url' => $banner->external_url ?: $banner->link ?: '',
                                    'image_source' => $banner->image_source ?? 'custom',
                                    'image_url' => $banner->image_url,
                                    'has_custom_image' => !empty($banner->image),
                                    'is_active' => $banner->is_active ? 1 : 0
                                ]) }})" data-bs-toggle="tooltip" data-bs-title="Edit Data">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('cms.banner.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus banner slider ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-images fs-3 d-block text-muted mb-2"></i>
                            Belum ada banner slider yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Banner -->
<div class="modal fade" id="addBannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('cms.banner.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary"><i class="bi bi-image-fill me-2"></i>Tambah Banner Slider Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="add_title" class="form-label fw-semibold">Judul Banner (Opsional)</label>
                    <input type="text" name="title" id="add_title" class="form-control" placeholder="Contoh: Selamat Datang di Portal Dinas Perikanan">
                    <small class="form-text text-muted">Judul akan ditampilkan pada teks hero slider beranda.</small>
                </div>

                <!-- 1. Tautan Tujuan Dropdown -->
                <div class="mb-3">
                    <label for="add_link_type" class="form-label fw-semibold">Tautan Tujuan (Opsional)</label>
                    <select name="link_type" id="add_link_type" class="form-select">
                        <option value="none">Tidak ada tautan</option>
                        <option value="berita">Berita</option>
                        <option value="pelayanan">Pelayanan</option>
                        <option value="external">Link Eksternal</option>
                    </select>
                </div>

                <!-- 2. Container Pilih Berita -->
                <div class="mb-3 d-none" id="add_berita_container">
                    <label for="add_news_id" class="form-label fw-semibold">Pilih Berita <span class="text-danger">*</span></label>
                    <select name="news_id" id="add_news_id" class="form-select">
                        <option value="">-- Pilih Berita dari Database --</option>
                        @foreach($newsList as $news)
                            @php
                                $hasImage = !empty($news->thumbnail);
                                $imgUrl = '';
                                if ($hasImage) {
                                    if (str_starts_with($news->thumbnail, 'http') || str_starts_with($news->thumbnail, 'data:image/')) {
                                        $imgUrl = $news->thumbnail;
                                    } elseif (str_starts_with($news->thumbnail, 'assets/')) {
                                        $imgUrl = file_exists(public_path($news->thumbnail)) ? asset($news->thumbnail) : \App\Models\Banner::DEFAULT_PLACEHOLDER;
                                    } else {
                                        $imgUrl = asset('storage/' . $news->thumbnail);
                                    }
                                }
                            @endphp
                            <option value="{{ $news->id }}" data-has-image="{{ $hasImage ? '1' : '0' }}" data-image="{{ $imgUrl }}">
                                {{ $news->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Container Pilih Pelayanan -->
                <div class="mb-3 d-none" id="add_pelayanan_container">
                    <label for="add_service_id" class="form-label fw-semibold">Pilih Pelayanan <span class="text-danger">*</span></label>
                    <select name="service_id" id="add_service_id" class="form-select">
                        <option value="">-- Pilih Pelayanan dari Database --</option>
                        @foreach($serviceList as $srv)
                            @php
                                $hasImage = !empty($srv->icon);
                                $imgUrl = '';
                                if ($hasImage) {
                                    if (str_starts_with($srv->icon, 'http') || str_starts_with($srv->icon, 'data:image/')) {
                                        $imgUrl = $srv->icon;
                                    } elseif (str_starts_with($srv->icon, 'assets/')) {
                                        $imgUrl = file_exists(public_path($srv->icon)) ? asset($srv->icon) : \App\Models\Banner::DEFAULT_PLACEHOLDER;
                                    } else {
                                        $imgUrl = asset('storage/' . $srv->icon);
                                    }
                                }
                            @endphp
                            <option value="{{ $srv->id }}" data-has-image="{{ $hasImage ? '1' : '0' }}" data-image="{{ $imgUrl }}">
                                {{ $srv->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 4. Container Link Eksternal -->
                <div class="mb-3 d-none" id="add_external_container">
                    <label for="add_external_url" class="form-label fw-semibold">URL Eksternal <span class="text-danger">*</span></label>
                    <input type="url" name="external_url" id="add_external_url" class="form-control" placeholder="https://example.com">
                    <small class="form-text text-muted">Wajib menyertakan https:// atau http://</small>
                </div>

                <!-- 5. Container Sumber Gambar (Berita / Pelayanan) -->
                <div class="mb-3 d-none" id="add_image_source_container">
                    <label class="form-label fw-semibold d-block">Sumber Gambar Banner <span class="text-danger">*</span></label>
                    <div class="p-3 border rounded bg-light">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="image_source" id="add_source_content" value="content" checked>
                            <label class="form-check-label fw-semibold" for="add_source_content" id="add_source_content_label">
                                Gunakan gambar dari Berita
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="image_source" id="add_source_custom" value="custom">
                            <label class="form-check-label fw-semibold" for="add_source_custom">
                                Upload gambar banner khusus
                            </label>
                        </div>
                        <div class="alert alert-warning py-2 px-3 small mt-2 mb-0 d-none" id="add_no_image_warning">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Konten yang dipilih tidak memiliki gambar/thumbnail. Harap pilih <strong>"Upload gambar banner khusus"</strong>.
                        </div>
                    </div>
                </div>

                <!-- 6. Container Upload File Gambar -->
                <div class="mb-3" id="add_custom_image_container">
                    <label for="add_image" class="form-label fw-semibold" id="add_image_label">Gambar Banner <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="add_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                    <small class="form-text text-muted">Rekomendasi rasio 1920x600px (format: JPG, PNG, WEBP, Maksimal 10MB).</small>
                </div>

                <!-- 7. Live Preview Card -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Preview Gambar Banner</label>
                    <div class="border rounded p-2 bg-light text-center position-relative" style="min-height: 140px; display: flex; align-items: center; justify-content: center;">
                        <img id="add_preview_img" src="{!! \App\Models\Banner::DEFAULT_PLACEHOLDER !!}" alt="Preview" class="rounded img-fluid" style="max-height: 180px; width: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{!! \App\Models\Banner::DEFAULT_PLACEHOLDER !!}';">
                        <div id="add_preview_badge" class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-3 px-2 py-1 small">
                            <i class="bi bi-eye me-1"></i> Preview Realtime
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="add_is_active" class="form-label fw-semibold">Status Aktif <span class="text-danger">*</span></label>
                    <select name="is_active" id="add_is_active" class="form-select" required>
                        <option value="1">Aktif (Tampil di Hero Slider)</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold px-4">
                    <i class="bi bi-save me-1"></i> Simpan Banner
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Banner -->
<div class="modal fade" id="editBannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="editBannerForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Banner Slider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_title" class="form-label fw-semibold">Judul Banner (Opsional)</label>
                    <input type="text" name="title" id="edit_title" class="form-control">
                </div>

                <!-- 1. Tautan Tujuan Dropdown -->
                <div class="mb-3">
                    <label for="edit_link_type" class="form-label fw-semibold">Tautan Tujuan (Opsional)</label>
                    <select name="link_type" id="edit_link_type" class="form-select">
                        <option value="none">Tidak ada tautan</option>
                        <option value="berita">Berita</option>
                        <option value="pelayanan">Pelayanan</option>
                        <option value="external">Link Eksternal</option>
                    </select>
                </div>

                <!-- 2. Container Pilih Berita -->
                <div class="mb-3 d-none" id="edit_berita_container">
                    <label for="edit_news_id" class="form-label fw-semibold">Pilih Berita <span class="text-danger">*</span></label>
                    <select name="news_id" id="edit_news_id" class="form-select">
                        <option value="">-- Pilih Berita dari Database --</option>
                        @foreach($newsList as $news)
                            @php
                                $hasImage = !empty($news->thumbnail);
                                $imgUrl = '';
                                if ($hasImage) {
                                    if (str_starts_with($news->thumbnail, 'http') || str_starts_with($news->thumbnail, 'data:image/')) {
                                        $imgUrl = $news->thumbnail;
                                    } elseif (str_starts_with($news->thumbnail, 'assets/')) {
                                        $imgUrl = file_exists(public_path($news->thumbnail)) ? asset($news->thumbnail) : \App\Models\Banner::DEFAULT_PLACEHOLDER;
                                    } else {
                                        $imgUrl = asset('storage/' . $news->thumbnail);
                                    }
                                }
                            @endphp
                            <option value="{{ $news->id }}" data-has-image="{{ $hasImage ? '1' : '0' }}" data-image="{{ $imgUrl }}">
                                {{ $news->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Container Pilih Pelayanan -->
                <div class="mb-3 d-none" id="edit_pelayanan_container">
                    <label for="edit_service_id" class="form-label fw-semibold">Pilih Pelayanan <span class="text-danger">*</span></label>
                    <select name="service_id" id="edit_service_id" class="form-select">
                        <option value="">-- Pilih Pelayanan dari Database --</option>
                        @foreach($serviceList as $srv)
                            @php
                                $hasImage = !empty($srv->icon);
                                $imgUrl = '';
                                if ($hasImage) {
                                    if (str_starts_with($srv->icon, 'http') || str_starts_with($srv->icon, 'data:image/')) {
                                        $imgUrl = $srv->icon;
                                    } elseif (str_starts_with($srv->icon, 'assets/')) {
                                        $imgUrl = file_exists(public_path($srv->icon)) ? asset($srv->icon) : \App\Models\Banner::DEFAULT_PLACEHOLDER;
                                    } else {
                                        $imgUrl = asset('storage/' . $srv->icon);
                                    }
                                }
                            @endphp
                            <option value="{{ $srv->id }}" data-has-image="{{ $hasImage ? '1' : '0' }}" data-image="{{ $imgUrl }}">
                                {{ $srv->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 4. Container Link Eksternal -->
                <div class="mb-3 d-none" id="edit_external_container">
                    <label for="edit_external_url" class="form-label fw-semibold">URL Eksternal <span class="text-danger">*</span></label>
                    <input type="url" name="external_url" id="edit_external_url" class="form-control" placeholder="https://example.com">
                </div>

                <!-- 5. Container Sumber Gambar (Berita / Pelayanan) -->
                <div class="mb-3 d-none" id="edit_image_source_container">
                    <label class="form-label fw-semibold d-block">Sumber Gambar Banner <span class="text-danger">*</span></label>
                    <div class="p-3 border rounded bg-light">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="image_source" id="edit_source_content" value="content">
                            <label class="form-check-label fw-semibold" for="edit_source_content" id="edit_source_content_label">
                                Gunakan gambar dari Berita
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="image_source" id="edit_source_custom" value="custom">
                            <label class="form-check-label fw-semibold" for="edit_source_custom">
                                Upload gambar banner khusus
                            </label>
                        </div>
                        <div class="alert alert-warning py-2 px-3 small mt-2 mb-0 d-none" id="edit_no_image_warning">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Konten yang dipilih tidak memiliki gambar/thumbnail. Harap pilih <strong>"Upload gambar banner khusus"</strong>.
                        </div>
                    </div>
                </div>

                <!-- 6. Container Upload File Gambar -->
                <div class="mb-3" id="edit_custom_image_container">
                    <label for="edit_image" class="form-label fw-semibold" id="edit_image_label">Ganti Gambar Khusus (Opsional)</label>
                    <input type="file" name="image" id="edit_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                    <small class="form-text text-muted">Rekomendasi rasio 1920x600px (format: JPG, PNG, WEBP, Maksimal 10MB).</small>
                </div>

                <!-- 7. Live Preview Card -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Preview Gambar Banner</label>
                    <div class="border rounded p-2 bg-light text-center position-relative" style="min-height: 140px; display: flex; align-items: center; justify-content: center;">
                        <img id="edit_preview_img" src="{!! \App\Models\Banner::DEFAULT_PLACEHOLDER !!}" alt="Preview" class="rounded img-fluid" style="max-height: 180px; width: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{!! \App\Models\Banner::DEFAULT_PLACEHOLDER !!}';">
                        <div id="edit_preview_badge" class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-3 px-2 py-1 small">
                            <i class="bi bi-eye me-1"></i> Preview Realtime
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="edit_is_active" class="form-label fw-semibold">Status Aktif <span class="text-danger">*</span></label>
                    <select name="is_active" id="edit_is_active" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold px-4">
                    <i class="bi bi-check2-circle me-1"></i> Perbarui Banner
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Setup Form Dynamic Logic for Add Modal
        setupBannerForm('add');

        // Setup Form Dynamic Logic for Edit Modal
        setupBannerForm('edit');
    });

    /**
     * Mengatur logika form dinamis untuk modal Tambah maupun Edit
     */
    function setupBannerForm(prefix) {
        const linkTypeEl = document.getElementById(prefix + '_link_type');
        const beritaContainer = document.getElementById(prefix + '_berita_container');
        const pelayananContainer = document.getElementById(prefix + '_pelayanan_container');
        const externalContainer = document.getElementById(prefix + '_external_container');
        const imageSourceContainer = document.getElementById(prefix + '_image_source_container');
        const customImageContainer = document.getElementById(prefix + '_custom_image_container');
        const sourceContentRadio = document.getElementById(prefix + '_source_content');
        const sourceCustomRadio = document.getElementById(prefix + '_source_custom');
        const sourceContentLabel = document.getElementById(prefix + '_source_content_label');
        const noImageWarning = document.getElementById(prefix + '_no_image_warning');
        const newsSelect = document.getElementById(prefix + '_news_id');
        const serviceSelect = document.getElementById(prefix + '_service_id');
        const fileInput = document.getElementById(prefix + '_image');
        const previewImg = document.getElementById(prefix + '_preview_img');
        const defaultPlaceholder = "{!! \App\Models\Banner::DEFAULT_PLACEHOLDER !!}";

        function updateFormUI(isInitial = false) {
            const linkType = linkTypeEl.value;

            // Reset containers visibility
            beritaContainer.classList.add('d-none');
            pelayananContainer.classList.add('d-none');
            externalContainer.classList.add('d-none');
            imageSourceContainer.classList.add('d-none');
            noImageWarning.classList.add('d-none');

            if (linkType === 'berita') {
                beritaContainer.classList.remove('d-none');
                imageSourceContainer.classList.remove('d-none');
                sourceContentLabel.textContent = 'Gunakan gambar dari Berita';
                
                if (!isInitial) {
                    sourceContentRadio.checked = true;
                }
                evaluateContentImage(newsSelect);

            } else if (linkType === 'pelayanan') {
                pelayananContainer.classList.remove('d-none');
                imageSourceContainer.classList.remove('d-none');
                sourceContentLabel.textContent = 'Gunakan gambar dari Pelayanan';
                
                if (!isInitial) {
                    sourceContentRadio.checked = true;
                }
                evaluateContentImage(serviceSelect);

            } else if (linkType === 'external') {
                externalContainer.classList.remove('d-none');
                sourceCustomRadio.checked = true;
                customImageContainer.classList.remove('d-none');
                updateFilePreview();

            } else { // none
                sourceCustomRadio.checked = true;
                customImageContainer.classList.remove('d-none');
                updateFilePreview();
            }

            updateCustomImageVisibility();
        }

        function evaluateContentImage(selectEl) {
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            const hasImage = selectedOpt ? selectedOpt.getAttribute('data-has-image') === '1' : false;
            const imageUrl = selectedOpt ? selectedOpt.getAttribute('data-image') : '';

            if (selectEl.value && !hasImage) {
                // Konten tidak punya gambar
                sourceContentRadio.disabled = true;
                sourceCustomRadio.checked = true;
                noImageWarning.classList.remove('d-none');
            } else {
                sourceContentRadio.disabled = false;
                noImageWarning.classList.add('d-none');
            }

            if (sourceContentRadio.checked && hasImage && imageUrl) {
                previewImg.src = imageUrl;
            } else {
                updateFilePreview();
            }

            updateCustomImageVisibility();
        }

        function updateCustomImageVisibility() {
            const isCustom = sourceCustomRadio.checked;
            if (isCustom) {
                customImageContainer.classList.remove('d-none');
            } else {
                customImageContainer.classList.add('d-none');
            }
        }

        function updateFilePreview() {
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else if (prefix === 'edit' && window.currentEditingBannerImage) {
                previewImg.src = window.currentEditingBannerImage;
            } else {
                previewImg.src = defaultPlaceholder;
            }
        }

        // Attach event listeners
        linkTypeEl.addEventListener('change', function () {
            updateFormUI(false);
        });

        newsSelect.addEventListener('change', function () {
            evaluateContentImage(newsSelect);
        });

        serviceSelect.addEventListener('change', function () {
            evaluateContentImage(serviceSelect);
        });

        sourceContentRadio.addEventListener('change', function () {
            updateCustomImageVisibility();
            if (linkTypeEl.value === 'berita') evaluateContentImage(newsSelect);
            if (linkTypeEl.value === 'pelayanan') evaluateContentImage(serviceSelect);
        });

        sourceCustomRadio.addEventListener('change', function () {
            updateCustomImageVisibility();
            updateFilePreview();
        });

        fileInput.addEventListener('change', function () {
            updateFilePreview();
        });
    }

    /**
     * Membuka dan mengisi data modal Edit Banner
     */
    function editBanner(data) {
        document.getElementById('edit_title').value = data.title || '';
        document.getElementById('edit_link_type').value = data.link_type || 'none';
        document.getElementById('edit_news_id').value = data.news_id || '';
        document.getElementById('edit_service_id').value = data.service_id || '';
        document.getElementById('edit_external_url').value = data.external_url || '';
        document.getElementById('edit_is_active').value = data.is_active;

        window.currentEditingBannerImage = data.image_url || "{!! \App\Models\Banner::DEFAULT_PLACEHOLDER !!}";

        // Set radio button source
        if (data.image_source === 'content') {
            document.getElementById('edit_source_content').checked = true;
        } else {
            document.getElementById('edit_source_custom').checked = true;
        }

        // Trigger perubahan UI form
        const linkTypeEl = document.getElementById('edit_link_type');
        linkTypeEl.dispatchEvent(new Event('change'));

        // Pastikan radio yang tepat tetap terpilih setelah dispatch change
        if (data.image_source === 'content') {
            document.getElementById('edit_source_content').checked = true;
        } else {
            document.getElementById('edit_source_custom').checked = true;
        }
        document.getElementById('edit_source_custom').dispatchEvent(new Event('change'));

        // Atur preview awal
        document.getElementById('edit_preview_img').src = data.image_url || "{!! \App\Models\Banner::DEFAULT_PLACEHOLDER !!}";

        document.getElementById('editBannerForm').action = "{{ url('/cms/banner') }}/" + data.id;
        const modal = new bootstrap.Modal(document.getElementById('editBannerModal'));
        modal.show();
    }
</script>
@endpush
