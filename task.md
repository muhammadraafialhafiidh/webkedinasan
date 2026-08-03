# Task Plan — CMS Portal Informasi Dinas Perikanan

## Informasi Proyek

| Atribut | Detail |
|---|---|
| Nama Proyek | CMS Portal Informasi Dinas Perikanan |
| Database | `cms_portal2` |
| Framework | Laravel 11 |
| Total Fase | 6 Fase |
| Total Halaman | ~28 halaman |

---

## Legenda Status

| Simbol | Arti |
|---|---|
| `[ ]` | Belum dikerjakan |
| `[x]` | Selesai |
| `[~]` | Sedang dikerjakan |

---

## Phase 1 — Project Setup & Konfigurasi

### 1.1 Inisialisasi Laravel

- [ ] Install Laravel 11 via Composer
- [ ] Konfigurasi `.env`: DB, APP_NAME, APP_URL, MAIL
- [ ] Buat database `cms_portal2` di MySQL
- [ ] Konfigurasi `config/filesystems.php` untuk public disk
- [ ] Jalankan `php artisan storage:link`
- [ ] Install package: `spatie/laravel-activitylog`
- [ ] Install package: `intervention/image` (resize thumbnail)
- [ ] Konfigurasi timezone ke `Asia/Jakarta` di `config/app.php`

### 1.2 Struktur Folder

- [ ] Buat folder `app/Http/Controllers/Cms/` untuk controller CMS
- [ ] Buat folder `app/Http/Controllers/Public/` untuk controller publik
- [ ] Buat folder `app/Http/Middleware/` untuk middleware role
- [ ] Buat folder `resources/views/public/` untuk view publik
- [ ] Buat folder `resources/views/cms/` untuk view CMS
- [ ] Buat folder `resources/views/layouts/` untuk layout Blade
- [ ] Buat folder `public/assets/css|js|images/` untuk aset statis

### 1.3 Aset Frontend

- [ ] Semua CSS ditulis **inline** di dalam tag `<style>` pada masing-masing file Blade (layout publik, layout CMS, halaman auth, dll)
- [ ] Semua JavaScript ditulis **inline** di dalam tag `<script>` pada masing-masing file Blade
- [ ] CDN yang diload via tag `<link>` dan `<script>` di layout: Bootstrap 5.3, Bootstrap Icons, Swiper.js, GLightbox, Google Fonts (Plus Jakarta Sans + Inter), TinyMCE
- [ ] Tidak ada folder `public/assets/css/` atau `public/assets/js/` — tidak perlu dibuat

---

## Phase 2 — Database & Model

### 2.1 Migration

- [ ] Migration: `users` (tambah kolom role, is_active, last_login_at)
- [ ] Migration: `settings`
- [ ] Migration: `activity_logs`
- [ ] Migration: `news_categories`
- [ ] Migration: `news`
- [ ] Migration: `service_categories`
- [ ] Migration: `services` (kolom `service_category_id` FK ke `service_categories`)
- [ ] Migration: `gallery_albums`
- [ ] Migration: `gallery_photos`
- [ ] Migration: `gallery_videos`
- [ ] Migration: `documents`
- [ ] Migration: `banners`
- [ ] Migration: `profile_contents`
- [ ] Migration: `organization_members`
- [ ] Migration: `contacts`
- [ ] Jalankan `php artisan migrate`

### 2.2 Model & Relasi

- [ ] Model `User` — tambah relasi ke `ActivityLog`, enum role
- [ ] Model `NewsCategory` — hasMany News
- [ ] Model `News` — belongsTo NewsCategory, belongsTo User
- [ ] Model `ServiceCategory` — hasMany Service
- [ ] Model `Service` — belongsTo ServiceCategory
- [ ] Model `GalleryAlbum` — hasMany GalleryPhoto
- [ ] Model `GalleryPhoto` — belongsTo GalleryAlbum
- [ ] Model `GalleryVideo`
- [ ] Model `Document`
- [ ] Model `Banner`
- [ ] Model `ProfileContent`
- [ ] Model `OrganizationMember`
- [ ] Model `Contact`
- [ ] Model `Setting` — dengan method statis `get($key)`
- [ ] Model `ActivityLog` — belongsTo User

### 2.3 Seeder

- [ ] `UserSeeder` — 2 akun: Super Admin (`superadmin@perikanan.go.id`) & Admin (`admin@perikanan.go.id`), password `Password123` (bcrypt)
- [ ] `SettingSeeder` — 18 key pengaturan: nama_website, tagline, deskripsi, email, telepon, fax, alamat, jam_operasional, logo, favicon, facebook_url, instagram_url, youtube_url, twitter_url, teks_footer, google_maps_embed, statistik_nelayan, statistik_produksi, statistik_pokdakan, statistik_layanan
- [ ] `ProfileContentSeeder` — 4 key konten: sejarah, visi, misi, tupoksi (konten HTML placeholder)
- [ ] `OrganizationMemberSeeder` — 6 anggota struktur organisasi (Kepala Dinas s/d Kasubbag) dengan foto placeholder default
- [ ] `NewsCategorySeeder` — 5 kategori: Informasi, Kegiatan, Pengumuman, Program, Berita Dinas
- [ ] `NewsSeeder` — 7 berita (6 published + 1 draft), thumbnail placeholder, konten lorem ipsum HTML
- [ ] `ServiceCategorySeeder` — 5 kategori bidang: (1) Penganekaragaman & Keamanan Pangan, (2) Ketersediaan & Stabilisasi Pangan, (3) Penanganan Kerawanan Pangan, (4) Perikanan, (5) UPTD PBAT — lengkap dengan slug dan deskripsi
- [ ] `ServiceSeeder` — 12 layanan aktif sesuai Standar Pelayanan DKPP Banyumas, masing-masing dengan `service_category_id`, persyaratan HTML, prosedur HTML, durasi, biaya, dan produk layanan yang lengkap
- [ ] `GalleryAlbumSeeder` — 4 album (Kegiatan Budidaya 2024, Pelatihan & Sosialisasi, Kunjungan Lapangan, Panen Raya)
- [ ] `GalleryPhotoSeeder` — 12 foto placeholder (3 foto per album × 4 album)
- [ ] `GalleryVideoSeeder` — 3 video (URL YouTube placeholder, thumbnail auto dari YouTube)
- [ ] `DocumentSeeder` — 8 dokumen (2 Laporan, 2 Formulir, 2 Panduan, 1 Brosur, 1 Data Statistik) dengan file dummy
- [ ] `BannerSeeder` — 3 banner aktif dengan gambar placeholder 1920×600px, urutan 1–3
- [ ] `ContactSeeder` — 4 pesan masuk dummy (2 belum dibaca, 2 sudah dibaca)
- [ ] Update `DatabaseSeeder.php` — panggil semua seeder dalam urutan yang benar (sesuai dependensi relasi)
- [ ] Jalankan `php artisan db:seed` dan verifikasi semua data masuk dengan benar

---

## Phase 3 — Autentikasi & Middleware

### 3.1 Autentikasi Custom

- [ ] Buat `AuthController` dengan method: showLogin, login, logout
- [ ] Buat `ForgotPasswordController`
- [ ] Form login CMS (`resources/views/auth/login.blade.php`)
- [ ] Form lupa password (`resources/views/auth/forgot-password.blade.php`)
- [ ] Form reset password (`resources/views/auth/reset-password.blade.php`)
- [ ] Validasi: cek is_active sebelum login diizinkan
- [ ] Update `last_login_at` pada login sukses
- [ ] Catat log: aksi `login` dan `logout`

### 3.2 Middleware

- [ ] Buat middleware `CmsAuthMiddleware` — cek user sudah login
- [ ] Buat middleware `RoleMiddleware` — cek role Super Admin
- [ ] Daftarkan middleware di `bootstrap/app.php`
- [ ] Terapkan middleware ke route CMS (`cms/*`)
- [ ] Terapkan `RoleMiddleware:super_admin` ke route khusus Super Admin

---

## Phase 4 — Halaman Publik

### 4.1 Layout Publik

- [ ] Buat layout `layouts/public.blade.php` (include navbar + footer)
- [ ] Buat partial `partials/navbar.blade.php`
- [ ] Buat partial `partials/footer.blade.php`
- [ ] Buat partial `partials/breadcrumb.blade.php`

### 4.2 Beranda

- [ ] `HomeController@index` — query banner aktif, 6 berita terbaru, layanan aktif, 4 galeri foto terbaru
- [ ] View `public/home/index.blade.php`
  - [ ] Section hero slider (Swiper.js)
  - [ ] Section statistik (data dari settings)
  - [ ] Section berita terbaru (grid 3 kolom)
  - [ ] Section layanan unggulan (grid 4 kolom)
  - [ ] Section galeri foto terbaru
- [ ] Pastikan responsif di semua breakpoint

### 4.3 Profil Dinas

- [ ] `ProfileController@index` — query profile_contents + organization_members
- [ ] View `public/profile/index.blade.php`
  - [ ] Tab: Sejarah | Visi & Misi | Tupoksi | Struktur Organisasi
  - [ ] Render konten HTML dari database
  - [ ] Grid kartu struktur organisasi

### 4.4 Berita

- [ ] `NewsController@index` — query berita published, filter kategori, pencarian, paginate 10
- [ ] `NewsController@show` — query 1 berita by slug, 4 berita terkait
- [ ] View `public/news/index.blade.php` (daftar + sidebar)
- [ ] View `public/news/show.blade.php` (detail + sidebar + berita terkait)

### 4.5 Layanan

- [ ] `ServiceController@index` — query semua layanan aktif beserta kategorinya, support filter `?kategori={slug}` via query param
- [ ] `ServiceController@byCategory` — query layanan berdasarkan slug kategori (`/layanan/kategori/{slug}`)
- [ ] `ServiceController@show` — query 1 layanan by slug + eager load `serviceCategory` + layanan lain dalam kategori yang sama
- [ ] View `public/service/index.blade.php`
  - [ ] Tab/chip filter kategori bidang di atas (query semua `service_categories`)
  - [ ] Grid layanan dengan badge nama kategori/bidang di tiap kartu
  - [ ] Filter aktif ditandai dengan warna berbeda
  - [ ] Filter kategori dengan AJAX atau reload halaman (query param)
- [ ] View `public/service/show.blade.php`
  - [ ] Breadcrumb: Beranda > Layanan > [Nama Kategori] > [Nama Layanan]
  - [ ] Badge nama bidang/kategori di bawah judul
  - [ ] Detail lengkap: deskripsi, persyaratan (HTML), prosedur (HTML), durasi, biaya, produk layanan
  - [ ] Sidebar: daftar layanan lain dalam kategori/bidang yang sama

### 4.6 Galeri

- [ ] `GalleryPhotoController@index` — query semua album + foto
- [ ] `GalleryVideoController@index` — query semua video
- [ ] View `public/gallery/photo.blade.php` (grid + lightbox)
- [ ] View `public/gallery/video.blade.php`
- [ ] Integrasi Lightbox untuk foto (GLightbox atau simplelightbox)
- [ ] Embed YouTube untuk video

### 4.7 Dokumen & Download

- [ ] `DocumentController@index` — query semua dokumen, filter berdasarkan kategori
- [ ] View `public/document/index.blade.php`
- [ ] Tombol unduh file (serve dari storage)

### 4.8 Kontak

- [ ] `ContactController@index` — tampilkan halaman kontak
- [ ] `ContactController@store` — validasi & simpan pesan, tampilkan pesan sukses
- [ ] View `public/contact/index.blade.php`
  - [ ] Info kontak
  - [ ] Embed Google Maps (dari setting)
  - [ ] Form pesan + validasi JS

---

## Phase 5 — CMS Admin & Super Admin

### 5.1 Layout CMS

- [ ] Buat layout `layouts/cms.blade.php` (sidebar + topbar + content area)
- [ ] Buat partial `cms/partials/sidebar.blade.php`
- [ ] Buat partial `cms/partials/topbar.blade.php`
- [ ] Sidebar collapse toggle (JS)
- [ ] Highlight menu aktif di sidebar
- [ ] Badge jumlah pesan belum dibaca di sidebar

### 5.2 Dashboard CMS

- [ ] `DashboardController@index` — hitung statistik, query berita & pesan terbaru, query 5 log terbaru
- [ ] View `cms/dashboard.blade.php`
  - [ ] 4 kartu statistik
  - [ ] Tabel 5 berita terbaru
  - [ ] Tabel 5 pesan terbaru
  - [ ] Tabel 5 log aktivitas terakhir

### 5.3 Manajemen Berita

- [ ] `Cms/NewsController@index` — list, filter, search, paginate
- [ ] `Cms/NewsController@create` — form tambah
- [ ] `Cms/NewsController@store` — validasi, upload thumbnail, simpan, catat log
- [ ] `Cms/NewsController@edit` — form edit
- [ ] `Cms/NewsController@update` — validasi, update, catat log
- [ ] `Cms/NewsController@destroy` — hapus + file, catat log
- [ ] `Cms/NewsController@toggleStatus` — toggle publish/draft via AJAX
- [ ] Auto-generate slug dari judul
- [ ] Resize thumbnail ke max 800×450px sebelum simpan
- [ ] Integrasi TinyMCE atau Summernote untuk konten
- [ ] View: `cms/news/index.blade.php`, `create.blade.php`, `edit.blade.php`

### 5.4 Manajemen Kategori Berita

- [ ] `Cms/NewsCategoryController` (CRUD)
- [ ] Modal tambah/edit kategori (tanpa halaman baru)
- [ ] Cek: tidak bisa hapus kategori yang masih dipakai berita
- [ ] View: `cms/news-category/index.blade.php`

### 5.5 Manajemen Kategori Layanan

- [ ] `Cms/ServiceCategoryController` (CRUD)
- [ ] List kategori: tampilkan nama, slug, deskripsi, jumlah layanan per kategori, urutan, aksi
- [ ] Tambah/edit via modal (tanpa halaman baru) — field: nama, deskripsi, ikon, urutan
- [ ] Slug auto-generate dari nama kategori
- [ ] Cek sebelum hapus: tidak bisa hapus kategori yang masih memiliki layanan aktif — tampilkan pesan error informatif
- [ ] View: `cms/service-category/index.blade.php`

### 5.6 Manajemen Layanan

- [ ] `Cms/ServiceController@index` — list layanan, filter berdasarkan `service_category_id`, paginate
- [ ] `Cms/ServiceController@create` — form tambah, dropdown kategori dari `service_categories`
- [ ] `Cms/ServiceController@store` — validasi, upload ikon, simpan, catat log
- [ ] `Cms/ServiceController@edit` — form edit, pre-fill semua field
- [ ] `Cms/ServiceController@update` — validasi, update, catat log
- [ ] `Cms/ServiceController@destroy` — hapus + file ikon, catat log
- [ ] `Cms/ServiceController@toggleActive` — toggle aktif/nonaktif via AJAX
- [ ] Form layanan: kategori (dropdown), nama, slug (auto), deskripsi, persyaratan (rich text), prosedur (rich text), durasi, biaya, produk layanan, ikon/gambar, urutan, status aktif
- [ ] Kolom "Kategori/Bidang" tampil sebagai badge warna di tabel list
- [ ] Filter dropdown kategori di atas tabel list
- [ ] View: `cms/service/index.blade.php`, `create.blade.php`, `edit.blade.php`

### 5.6 Manajemen Galeri Foto

- [ ] `Cms/GalleryAlbumController` (CRUD album)
- [ ] `Cms/GalleryPhotoController` — multiple upload foto ke album
- [ ] Preview foto yang diupload sebelum simpan
- [ ] View: `cms/gallery/album.blade.php`, `cms/gallery/photo.blade.php`

### 5.7 Manajemen Galeri Video

- [ ] `Cms/GalleryVideoController` (CRUD)
- [ ] Auto-generate thumbnail dari YouTube URL (embed thumbnail API)
- [ ] View: `cms/gallery/video-index.blade.php`, `create.blade.php`, `edit.blade.php`

### 5.8 Manajemen Dokumen

- [ ] `Cms/DocumentController` (CRUD)
- [ ] Upload file (PDF, docx, xlsx), validasi
- [ ] View: `cms/document/index.blade.php`, `create.blade.php`, `edit.blade.php`

### 5.9 Manajemen Banner

- [ ] `Cms/BannerController` (CRUD + toggle aktif + atur urutan)
- [ ] Upload gambar banner, resize ke 1920×600px
- [ ] View: `cms/banner/index.blade.php`, `create.blade.php`, `edit.blade.php`

### 5.10 Manajemen Profil Dinas

- [ ] `Cms/ProfileController@index` — tampilkan form edit konten
- [ ] `Cms/ProfileController@update` — update profile_contents
- [ ] `Cms/OrganizationController` — CRUD anggota struktur organisasi
- [ ] Upload foto anggota, atur urutan tampil
- [ ] View: `cms/profile/index.blade.php`

### 5.11 Pesan Masuk

- [ ] `Cms/ContactController@index` — list pesan, filter belum/sudah dibaca
- [ ] `Cms/ContactController@show` — detail pesan, otomatis tandai dibaca
- [ ] `Cms/ContactController@destroy` — hapus pesan
- [ ] View: `cms/contact/index.blade.php`, `show.blade.php`

### 5.13 Manajemen User (Super Admin)

- [ ] `Cms/UserController@index` — list semua user kecuali akun sendiri
- [ ] `Cms/UserController@create` + `store` — tambah user baru
- [ ] `Cms/UserController@edit` + `update` — edit user (tanpa ubah password di form utama)
- [ ] `Cms/UserController@toggleActive` — aktif/nonaktifkan user
- [ ] `Cms/UserController@destroy` — hapus user (tidak bisa hapus diri sendiri)
- [ ] Form terpisah reset password user
- [ ] Catat log setiap aksi pada user
- [ ] View: `cms/user/index.blade.php`, `create.blade.php`, `edit.blade.php`

### 5.14 Manajemen Role (Super Admin)

- [ ] `Cms/RoleController@index` — tampilkan tabel role dan deskripsi hak akses
- [ ] View: `cms/role/index.blade.php` (read-only + penjelasan permission)

### 5.15 Pengaturan Website (Super Admin)

- [ ] `Cms/SettingController@index` — query semua setting
- [ ] `Cms/SettingController@update` — update batch semua setting
- [ ] Upload logo + favicon, simpan path ke settings
- [ ] View: `cms/setting/index.blade.php` (form panjang dengan section)

### 5.16 Log Aktivitas (Super Admin)

- [ ] `Cms/ActivityLogController@index` — list log, filter, search, paginate
- [ ] `Cms/ActivityLogController@export` — export ke Excel (gunakan `maatwebsite/excel` atau CSV manual)
- [ ] View: `cms/activity-log/index.blade.php`
- [ ] Pastikan log otomatis tercatat di semua Controller CMS (pakai Observer atau helper method)

---

## Phase 6 — Testing, Polish & Deployment

### 6.1 Testing Fungsional

- [ ] Test semua route publik dapat diakses tanpa login
- [ ] Test login dengan akun Super Admin → redirect dashboard, menu Super Admin muncul
- [ ] Test login dengan akun Admin → redirect dashboard, menu Super Admin tidak muncul
- [ ] Test CRUD semua modul oleh Admin
- [ ] Test bahwa Admin tidak bisa akses `/cms/user`, `/cms/pengaturan`, `/cms/log-aktivitas`
- [ ] Test upload file: gambar, PDF, dokumen
- [ ] Test form kontak publik
- [ ] Test form login dengan kredensial salah → pesan error
- [ ] Test responsive di mobile (Chrome DevTools)
- [ ] Test pagination berfungsi

### 6.2 Keamanan

- [ ] Pastikan semua form punya `@csrf`
- [ ] Pastikan validasi input ada di semua Controller (request validation)
- [ ] Pastikan file upload hanya izinkan ekstensi tertentu
- [ ] Pastikan route CMS tidak bisa diakses tanpa login
- [ ] Pastikan route Super Admin tidak bisa diakses oleh Admin
- [ ] Tidak ada query mentah (raw SQL) tanpa binding parameter

### 6.3 Polish UI

- [ ] Cek konsistensi warna, font, spacing di seluruh halaman
- [ ] Pastikan semua halaman punya breadcrumb
- [ ] Tambah empty state (ilustrasi/teks) saat data kosong
- [ ] Tambah loading spinner saat submit form
- [ ] Pastikan semua alert/notifikasi sukses & error tampil dengan benar
- [ ] Cek halaman 404 custom terpasang
- [ ] Pastikan favicon dan meta title dinamis dari settings

### 6.4 Optimasi

- [ ] Compress semua gambar aset (logo, ilustrasi)
- [ ] Tambahkan eager loading (with()) pada query yang ada relasi
- [ ] Tambahkan index pada kolom yang sering di-query (slug, status, published_at)
- [ ] Aktifkan query cache untuk data settings (jarang berubah)

### 6.5 Deployment Checklist

- [ ] Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`
- [ ] Jalankan `php artisan config:cache`
- [ ] Jalankan `php artisan route:cache`
- [ ] Jalankan `php artisan view:cache`
- [ ] Jalankan `php artisan migrate --force` di server
- [ ] Jalankan `php artisan db:seed --force` di server
- [ ] Jalankan `php artisan storage:link` di server
- [ ] Set permission folder `storage/` dan `bootstrap/cache/` ke 775
- [ ] Konfigurasi virtual host / Nginx untuk `public/` sebagai document root
- [ ] Test login dan semua halaman di server production
- [ ] Backup database awal setelah deploy

---

## Ringkasan Task per Fase

| Fase | Deskripsi | Estimasi |
|---|---|---|
| Phase 1 | Setup & Konfigurasi | ~1 hari |
| Phase 2 | Database, Model, Seeder | ~1 hari |
| Phase 3 | Auth & Middleware | ~1 hari |
| Phase 4 | Halaman Publik (11 halaman) | ~3 hari |
| Phase 5 | CMS Admin & Super Admin (16 modul) | ~5 hari |
| Phase 6 | Testing, Polish, Deployment | ~1–2 hari |
| **Total** | | **~12–13 hari** |

---

## Prioritas Fitur

### Must Have (wajib ada)
- Autentikasi CMS (login, logout, middleware role)
- Beranda publik
- Berita (publik + CMS)
- Dashboard CMS
- Manajemen User (Super Admin)
- Pengaturan Website

### Should Have (penting)
- Layanan (publik + CMS)
- Galeri Foto & Video
- Dokumen & Download
- Profil Dinas
- Pesan Masuk
- Log Aktivitas

### Nice to Have (tambahan)
- Export Log ke Excel
- Auto-thumbnail YouTube
- Share berita ke sosmed
- Lightbox galeri foto animasi
