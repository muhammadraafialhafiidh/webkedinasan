@extends('layouts.cms')

@section('title', 'Pengaturan Website — Super Admin')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Pengaturan Website</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Pengaturan Website Global</h3>
        <p class="text-muted small mb-0">Kelola identitas instansi, logo, kontak, link media sosial, dan data statistik beranda</p>
    </div>
</div>

<form action="{{ route('cms.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Settings Column (8 col) -->
        <div class="col-lg-8">
            <!-- Section 1: Identitas -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-globe me-2"></i>Identitas Website & Instansi</h5>

                <div class="mb-3">
                    <label for="nama_website" class="form-label">Nama Website / Instansi <span class="text-danger">*</span></label>
                    <input type="text" name="nama_website" id="nama_website" class="form-control" value="{{ old('nama_website', $settings['nama_website']) }}" required>
                </div>

                <div class="mb-3">
                    <label for="tagline" class="form-label">Tagline Slogan</label>
                    <input type="text" name="tagline" id="tagline" class="form-control" value="{{ old('tagline', $settings['tagline']) }}">
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Singkat Instansi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $settings['deskripsi']) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="teks_footer" class="form-label">Teks Hak Cipta (Footer)</label>
                    <input type="text" name="teks_footer" id="teks_footer" class="form-control" value="{{ old('teks_footer', $settings['teks_footer']) }}">
                </div>
            </div>

            <!-- Section 2: Kontak & Operasional -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-geo-alt-fill me-2"></i>Kontak & Jam Operasional</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Resmi <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $settings['email']) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="telepon" id="telepon" class="form-control" value="{{ old('telepon', $settings['telepon']) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="fax" class="form-label">Nomor Fax</label>
                        <input type="text" name="fax" id="fax" class="form-control" value="{{ old('fax', $settings['fax']) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="jam_operasional" class="form-label">Jam Operasional</label>
                        <input type="text" name="jam_operasional" id="jam_operasional" class="form-control" value="{{ old('jam_operasional', $settings['jam_operasional']) }}">
                    </div>

                    <div class="col-12">
                        <label for="alamat" class="form-label">Alamat Lengkap Kantor <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="alamat" rows="2" class="form-control" data-no-ckeditor="true" required>{{ old('alamat', strip_tags($settings['alamat'])) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label for="google_maps_embed" class="form-label">URL / Kode Embed Google Maps</label>
                        <small class="form-text text-muted d-block mb-1">Anda bisa memasukkan URL langsung (https://www.google.com/maps/embed?pb=...) atau menempelkan seluruh kode HTML &lt;iframe src="..."&gt;&lt;/iframe&gt; dari Google Maps Share/Embed.</small>
                        <input type="text" name="google_maps_embed" id="google_maps_embed" class="form-control font-monospace small" value="{{ old('google_maps_embed', $settings['google_maps_embed']) }}" placeholder="Tempel URL atau kode <iframe> dari Google Maps...">
                    </div>
                </div>
            </div>

            <!-- Section 3: Data Statistik Beranda -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-bar-chart-fill me-2"></i>Data Statistik Beranda</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="statistik_nelayan" class="form-label">Statistik Pembudidaya & Nelayan</label>
                        <input type="text" name="statistik_nelayan" id="statistik_nelayan" class="form-control" value="{{ old('statistik_nelayan', $settings['statistik_nelayan']) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="statistik_produksi" class="form-label">Statistik Produksi Perikanan</label>
                        <input type="text" name="statistik_produksi" id="statistik_produksi" class="form-control" value="{{ old('statistik_produksi', $settings['statistik_produksi']) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="statistik_pokdakan" class="form-label">Statistik Kelompok Pokdakan</label>
                        <input type="text" name="statistik_pokdakan" id="statistik_pokdakan" class="form-control" value="{{ old('statistik_pokdakan', $settings['statistik_pokdakan']) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="statistik_layanan" class="form-label">Statistik Standar Layanan</label>
                        <input type="text" name="statistik_layanan" id="statistik_layanan" class="form-control" value="{{ old('statistik_layanan', $settings['statistik_layanan']) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options (4 col) -->
        <div class="col-lg-4">
            <!-- Media Sosial -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-share-fill me-2"></i>Link Media Sosial</h5>

                <div class="mb-3">
                    <label for="facebook_url" class="form-label">Facebook URL</label>
                    <input type="url" name="facebook_url" id="facebook_url" class="form-control" value="{{ old('facebook_url', $settings['facebook_url']) }}">
                </div>

                <div class="mb-3">
                    <label for="instagram_url" class="form-label">Instagram URL</label>
                    <input type="url" name="instagram_url" id="instagram_url" class="form-control" value="{{ old('instagram_url', $settings['instagram_url']) }}">
                </div>

                <div class="mb-3">
                    <label for="youtube_url" class="form-label">YouTube URL</label>
                    <input type="url" name="youtube_url" id="youtube_url" class="form-control" value="{{ old('youtube_url', $settings['youtube_url']) }}">
                </div>

                <div class="mb-3">
                    <label for="twitter_url" class="form-label">Twitter / X URL</label>
                    <input type="url" name="twitter_url" id="twitter_url" class="form-control" value="{{ old('twitter_url', $settings['twitter_url']) }}">
                </div>
            </div>

            <!-- Logos -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-image me-2"></i>Logo & Favicon</h5>

                <div class="mb-4">
                    <label for="logo" class="form-label">Logo Instansi</label>
                    @if(!empty($settings['logo']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['logo']))
                        <div class="p-3 bg-light rounded border mb-2 d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Current Logo" style="height: 50px; max-width: 180px; object-fit: contain;" class="bg-white p-1 rounded border">
                            <div>
                                <span class="badge bg-success mb-1">Logo Aktif</span>
                                <small class="d-block text-muted text-truncate" style="max-width: 140px;">{{ basename($settings['logo']) }}</small>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Format PNG/SVG recommended (maks 10MB).</small>
                </div>

                <div class="mb-4">
                    <label for="favicon" class="form-label fw-semibold">Favicon Website</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        @if(!empty($settings['favicon']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['favicon']))
                            <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon Website" style="height: 32px; width: 32px; object-fit: contain;" class="border p-1 bg-light rounded">
                        @else
                            <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted" style="width: 32px; height: 32px; font-size: 0.75rem;">ICO</div>
                        @endif
                    </div>
                    <input type="file" name="favicon" id="favicon" class="form-control">
                    <small class="form-text text-muted">Format .ico / .png recommended (maks 10MB).</small>
                </div>
            </div>

            <div class="d-grid gap-2 sticky-top" style="top: 80px;">
                <button type="submit" class="btn btn-primary btn-lg font-bold shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Semua Pengaturan
                </button>
            </div>
        </div>
    </div>
</form>

@endsection
