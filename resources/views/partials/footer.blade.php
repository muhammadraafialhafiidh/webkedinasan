<footer style="background-color: var(--near-black); color: rgba(255,255,255,0.8); font-size: 0.925rem;" class="pt-5 pb-4">
    <div class="container">
        <div class="row g-4 pb-4 border-bottom border-secondary border-opacity-25">
            <!-- Kolom 1: Info Dinas -->
            <div class="col-lg-4 col-md-6 ps-lg-2">
                <div class="d-flex align-items-center mb-3" style="gap: 12px;">
                    @php
                        $logo = \App\Models\Setting::get('logo');
                    @endphp
                    @if($logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo))
                        <img src="{{ asset('storage/' . $logo) }}" alt="Logo Instansi" style="height: 42px; max-width: 150px; object-fit: contain;">
                    @else
                        <div class="bg-warning bg-opacity-20 text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="bi bi-water fs-5 text-warning"></i>
                        </div>
                    @endif
                    <h5 class="text-white fw-bold mb-0 min-w-0 text-break-word" style="font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.01em;">
                        {!! trim(strip_tags(\App\Models\Setting::get('nama_website', 'Portal Dinas Perikanan'))) !!}
                    </h5>
                </div>
                <p class="small text-white-50 leading-relaxed mb-4 text-break-word" style="max-width: 340px;">
                    {!! trim(strip_tags(\App\Models\Setting::get('deskripsi', 'Website resmi Dinas Perikanan yang menyediakan informasi layanan, program, dan kegiatan dinas.'))) !!}
                </p>
                <div class="d-flex gap-2">
                    @if($fb = \App\Models\Setting::get('facebook_url'))
                        <a href="{{ $fb }}" target="_blank" class="btn btn-sm btn-outline-light rounded-circle transition-all" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;" title="Facebook"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if($ig = \App\Models\Setting::get('instagram_url'))
                        <a href="{{ $ig }}" target="_blank" class="btn btn-sm btn-outline-light rounded-circle transition-all" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;" title="Instagram"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if($yt = \App\Models\Setting::get('youtube_url'))
                        <a href="{{ $yt }}" target="_blank" class="btn btn-sm btn-outline-light rounded-circle transition-all" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;" title="YouTube"><i class="bi bi-youtube"></i></a>
                    @endif
                    @if($tw = \App\Models\Setting::get('twitter_url'))
                        <a href="{{ $tw }}" target="_blank" class="btn btn-sm btn-outline-light rounded-circle transition-all" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;" title="Twitter / X"><i class="bi bi-twitter-x"></i></a>
                    @endif
                </div>
            </div>

            <!-- Kolom 2: Navigasi Cepat -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif;">Tautan Cepat</h6>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                    <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none hover-white transition-all">Beranda</a></li>
                    <li><a href="{{ route('profile') }}" class="text-white-50 text-decoration-none hover-white transition-all">Profil Dinas</a></li>
                    <li><a href="{{ route('news.index') }}" class="text-white-50 text-decoration-none hover-white transition-all">Berita Terbaru</a></li>
                    <li><a href="{{ route('service.index') }}" class="text-white-50 text-decoration-none hover-white transition-all">Daftar Layanan</a></li>
                    <li><a href="{{ route('gallery.photo') }}" class="text-white-50 text-decoration-none hover-white transition-all">Galeri Foto</a></li>
                    <li><a href="{{ route('document.index') }}" class="text-white-50 text-decoration-none hover-white transition-all">Dokumen & Download</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Layanan Unggulan -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif;">Kategori Bidang</h6>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                    @foreach(\App\Models\ServiceCategory::orderBy('order')->get() as $cat)
                        <li>
                            <a href="{{ route('service.byCategory', $cat->slug) }}" class="text-white-50 text-decoration-none hover-white transition-all">
                                <i class="bi bi-chevron-right me-1 text-teal small"></i>{{ Str::limit($cat->name, 32) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Kolom 4: Info Kontak -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif;">Kontak Kantor</h6>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small text-white-50">
                    <li class="d-flex align-items-start" style="gap: 10px;">
                        <i class="bi bi-geo-alt-fill text-warning mt-1" style="min-width: 20px; text-align: center; font-size: 1rem;"></i>
                        <span class="min-w-0 text-break-word flex-grow-1">{!! trim(strip_tags(\App\Models\Setting::get('alamat', 'Jl. Merdeka No. 1, Purwokerto'))) !!}</span>
                    </li>
                    <li class="d-flex align-items-center" style="gap: 10px;">
                        <i class="bi bi-telephone-fill text-warning" style="min-width: 20px; text-align: center; font-size: 1rem;"></i>
                        <span class="min-w-0 text-break-word flex-grow-1">{!! trim(strip_tags(\App\Models\Setting::get('telepon', '(0281) 123456'))) !!}</span>
                    </li>
                    <li class="d-flex align-items-center" style="gap: 10px;">
                        <i class="bi bi-envelope-fill text-warning" style="min-width: 20px; text-align: center; font-size: 1rem;"></i>
                        <span class="min-w-0 text-break-word flex-grow-1">{!! trim(strip_tags(\App\Models\Setting::get('email', 'info@perikanan.go.id'))) !!}</span>
                    </li>
                    <li class="d-flex align-items-center" style="gap: 10px;">
                        <i class="bi bi-clock-fill text-warning" style="min-width: 20px; text-align: center; font-size: 1rem;"></i>
                        <span class="min-w-0 text-break-word flex-grow-1">{!! trim(strip_tags(\App\Models\Setting::get('jam_operasional', 'Senin–Jumat: 08.00–16.00 WIB'))) !!}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-4 text-center small text-white-50 text-break-word">
            {!! trim(strip_tags(\App\Models\Setting::get('teks_footer', '© 2025 Dinas Perikanan. Seluruh hak cipta dilindungi undang-undang.'))) !!}
        </div>
    </div>
</footer>
