@extends('layouts.cms')

@section('title', 'Edit Berita — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.berita.index') }}" class="text-decoration-none text-muted">Berita</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit #{{ $news->id }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Edit Berita</h3>
        <p class="text-muted small mb-0">Ubah informasi dan konten berita #{{ $news->id }}</p>
    </div>
    <a href="{{ route('cms.berita.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<form action="{{ route('cms.berita.update', $news->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Form Column (8 col) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control form-control-lg" value="{{ old('title', $news->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Konten HTML Berita <span class="text-danger">*</span></label>
                    <textarea name="content" id="content" rows="12" class="form-control text-editor">{{ old('content', $news->content) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Options Sidebar Column (4 col) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3">Pengaturan Publikasi</h5>

                <div class="mb-3">
                    <label for="news_category_id" class="form-label">Kategori Berita <span class="text-danger">*</span></label>
                    <select name="news_category_id" id="news_category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('news_category_id', $news->news_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status Publikasi <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="published" {{ old('status', $news->status) === 'published' ? 'selected' : '' }}>Published (Terbit)</option>
                        <option value="draft" {{ old('status', $news->status) === 'draft' ? 'selected' : '' }}>Draft (Simpan Sementara)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="thumbnail" class="form-label">Ganti Gambar Thumbnail</label>
                    <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>

                    <div class="mt-3 text-center">
                        <img id="imgPreview" src="{{ asset('storage/' . $news->thumbnail) }}" alt="preview" class="img-fluid rounded border" style="max-height: 180px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1534951009808-766178b47a4f?auto=format&fit=crop&w=400&q=80'">
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary font-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Perbarui Berita
                    </button>
                    <a href="{{ route('cms.berita.index') }}" class="btn btn-light border">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('imgPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
