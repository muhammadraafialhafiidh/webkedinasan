# Requirements — CMS Portal Informasi Dinas Perikanan

## 1. Gambaran Umum Proyek

| Atribut | Detail |
|---|---|
| Nama Sistem | CMS Portal Informasi Dinas Perikanan |
| Nama Database | `cms_portal2` |
| Tujuan | Menyediakan portal informasi publik dan sistem manajemen konten untuk Dinas Perikanan |
| Target Pengguna | Masyarakat umum, Admin Dinas, Super Admin |

### 1.1 Tujuan Sistem
- Menyediakan informasi resmi dinas kepada masyarakat secara digital
- Memudahkan pengelolaan konten website oleh staff dinas
- Menyediakan akses layanan informasi publik secara transparan
- Mencatat seluruh aktivitas pengelola konten sebagai bentuk akuntabilitas

---

## 2. Tech Stack

| Layer | Teknologi |
|---|---|
| Backend Framework | Laravel 11 |
| Frontend | HTML5, CSS3, JavaScript (Vanilla + Alpine.js) |
| CSS Framework | Bootstrap 5.3 |
| Database | MySQL 8.x |
| Template Engine | Blade (Laravel) |
| Authentication | Laravel Breeze / Custom Auth |
| Storage | Laravel Storage (local/public disk) |
| Rich Text Editor | TinyMCE atau Summernote |
| Package Audit Log | Spatie Laravel Activity Log |
| Server | Apache / Nginx + PHP 8.2+ |

---

## 3. Hak Akses & Role

### 3.1 Daftar Role

| Role | Slug | Deskripsi |
|---|---|---|
| Super Admin | `super_admin` | Akses penuh ke semua fitur termasuk manajemen user, role, pengaturan, dan log aktivitas |
| Admin | `admin` | Akses ke manajemen konten (berita, layanan, galeri, dokumen, dll), tidak bisa kelola user & pengaturan sistem |
| Publik | — | Guest, hanya bisa akses halaman publik |

### 3.2 Matriks Hak Akses

| Fitur | Super Admin | Admin | Publik |
|---|---|---|---|
| Lihat halaman publik | ✅ | ✅ | ✅ |
| Dashboard CMS | ✅ | ✅ | ❌ |
| Manajemen Berita | ✅ | ✅ | ❌ |
| Manajemen Kategori Berita | ✅ | ✅ | ❌ |
| Manajemen Layanan | ✅ | ✅ | ❌ |
| Manajemen Galeri Foto | ✅ | ✅ | ❌ |
| Manajemen Galeri Video | ✅ | ✅ | ❌ |
| Manajemen Dokumen | ✅ | ✅ | ❌ |
| Manajemen Banner | ✅ | ✅ | ❌ |
| Manajemen Profil Dinas | ✅ | ✅ | ❌ |
| Pesan Masuk (Kontak) | ✅ | ✅ | ❌ |
| Manajemen User | ✅ | ❌ | ❌ |
| Manajemen Role & Akses | ✅ | ❌ | ❌ |
| Pengaturan Website | ✅ | ❌ | ❌ |
| Log Aktivitas | ✅ | ❌ | ❌ |

---

## 4. Kebutuhan Fungsional

### 4.1 Halaman Publik

#### F-PUB-01: Beranda
- Menampilkan hero banner/slider dengan gambar dan teks
- Menampilkan 6 berita terbaru dengan thumbnail, judul, dan tanggal
- Menampilkan statistik singkat dinas (jumlah nelayan, produksi, dll) — data statis dari pengaturan
- Menampilkan daftar layanan unggulan
- Menampilkan galeri foto terbaru (4–6 foto)
- Menampilkan footer dengan info kontak dan tautan sosial media

#### F-PUB-02: Profil Dinas
- Menampilkan konten: Sejarah, Visi & Misi, Tupoksi, Struktur Organisasi
- Struktur Organisasi ditampilkan dalam bentuk card grid dengan foto dan jabatan
- Konten dapat diedit melalui CMS

#### F-PUB-03: Daftar Berita
- Menampilkan semua berita berstatus `published`
- Filter berdasarkan kategori berita
- Pencarian berdasarkan judul
- Pagination (10 berita per halaman)
- Setiap item menampilkan: thumbnail, kategori, judul, tanggal, ringkasan

#### F-PUB-04: Detail Berita
- Menampilkan konten lengkap berita
- Menampilkan: judul, kategori, tanggal publish, penulis, thumbnail, konten HTML
- Menampilkan 4 berita terkait (kategori sama)
- Tombol share ke media sosial

#### F-PUB-05: Layanan
- Menampilkan semua kategori layanan (bidang) sebagai tab atau filter chip di bagian atas halaman
- Default tampil semua layanan aktif, bisa difilter per kategori
- Setiap item menampilkan: ikon/gambar, badge nama kategori/bidang, nama layanan, ringkasan singkat, durasi, biaya
- Klik filter kategori → tampil hanya layanan dalam kategori tersebut (AJAX atau query param)
- Jika satu kategori diklik langsung dari sidebar/navigasi, URL berformat `/layanan?kategori={slug}`

#### F-PUB-06: Detail Layanan
- Menampilkan header: nama kategori/bidang (breadcrumb + badge)
- Menampilkan detail lengkap: nama layanan, deskripsi, persyaratan (HTML list), prosedur/mekanisme (HTML ordered list), jangka waktu, biaya/tarif, produk layanan yang dihasilkan
- Sidebar: daftar layanan lain dalam kategori/bidang yang sama
- Tombol "Kembali ke Daftar Layanan"

#### F-PUB-07: Galeri Foto
- Menampilkan foto dalam grid responsif
- Dikelompokkan berdasarkan album
- Lightbox untuk preview foto lebih besar

#### F-PUB-08: Galeri Video
- Menampilkan daftar video (embed YouTube/link)
- Setiap item menampilkan: thumbnail, judul, deskripsi singkat
- Klik untuk play embed video

#### F-PUB-09: Dokumen & Download
- Menampilkan daftar file publik (laporan, brosur, form, panduan, dll)
- Filter berdasarkan kategori dokumen
- Tombol unduh file

#### F-PUB-10: Kontak
- Menampilkan info kontak: alamat, telepon, email, jam operasional
- Embed Google Maps
- Form pesan: nama, email, no. telepon, subjek, isi pesan
- Submit form menyimpan pesan ke database

---

### 4.2 CMS — Autentikasi

#### F-AUTH-01: Login CMS
- Form login dengan email dan password
- Validasi kredensial, hanya user aktif bisa login
- Redirect ke dashboard setelah berhasil login
- Tampilkan pesan error jika gagal
- Remember me (opsional)

#### F-AUTH-02: Lupa Password
- Form input email
- Kirim link reset password ke email
- Halaman reset password baru

#### F-AUTH-03: Logout
- Hapus sesi, redirect ke halaman login

---

### 4.3 CMS — Dashboard

#### F-CMS-01: Dashboard
- Ringkasan statistik: total berita, layanan, galeri foto, pesan masuk belum dibaca
- Daftar 5 berita terbaru yang dibuat
- Daftar 5 pesan terbaru belum dibaca
- Daftar 5 aktivitas terakhir (log)
- Informasi user yang sedang login (nama, role)

---

### 4.4 CMS — Manajemen Konten

#### F-CMS-02: Manajemen Berita
- List berita dengan kolom: thumbnail, judul, kategori, status, tanggal, aksi
- Filter berdasarkan status (published/draft) dan kategori
- Pencarian berdasarkan judul
- Tambah berita: judul, slug (auto-generate), kategori, thumbnail, konten (rich text editor), status (draft/published), tanggal publish
- Edit berita
- Hapus berita (soft delete atau hard delete)
- Toggle publish/draft langsung dari list

#### F-CMS-03: Manajemen Kategori Berita
- List kategori: nama, slug, jumlah berita
- Tambah, edit, hapus kategori
- Slug auto-generate dari nama kategori

#### F-CMS-04: Manajemen Kategori Layanan
- List kategori: nama, slug, deskripsi, jumlah layanan, urutan, aksi
- Tambah kategori: nama, slug (auto-generate), deskripsi singkat, ikon, urutan
- Edit dan hapus kategori
- Tidak bisa hapus kategori yang masih memiliki layanan aktif
- Slug auto-generate dari nama kategori

#### F-CMS-05: Manajemen Layanan
- List layanan: nama, kategori/bidang (badge), status aktif, urutan, aksi
- Filter berdasarkan kategori layanan
- Tambah layanan: kategori (dropdown dari service_categories), nama, slug (auto-generate), deskripsi singkat, persyaratan (rich text), prosedur (rich text), jangka waktu, biaya/tarif, produk layanan, ikon/gambar, urutan, status aktif
- Edit dan hapus layanan
- Toggle aktif/nonaktif langsung dari list

#### F-CMS-06: Manajemen Galeri Foto
- Manajemen album: nama album, deskripsi, foto cover
- Upload foto ke album (multiple upload)
- Delete foto atau album
- Preview foto

#### F-CMS-07: Manajemen Galeri Video
- List video: thumbnail, judul, tanggal, aksi
- Tambah video: judul, URL video (YouTube), thumbnail (auto atau manual), deskripsi
- Edit dan hapus video

#### F-CMS-08: Manajemen Dokumen
- List dokumen: judul, kategori, tanggal upload, aksi
- Tambah: judul, kategori, deskripsi, file (PDF/docx/xlsx)
- Edit dan hapus

#### F-CMS-09: Manajemen Banner/Slider
- List banner: gambar preview, judul, urutan, status aktif
- Tambah: judul, gambar, link tujuan (opsional), urutan, status aktif
- Edit, hapus, toggle aktif/nonaktif

#### F-CMS-10: Manajemen Profil Dinas
- Form edit statis: nama dinas, sejarah (rich text), visi, misi, tupoksi (rich text)
- Manajemen struktur organisasi: tambah/edit/hapus anggota (nama, jabatan, foto, urutan tampil)

#### F-CMS-11: Pesan Masuk (Kontak)
- List pesan: nama pengirim, subjek, status (dibaca/belum), tanggal
- Lihat detail pesan
- Tandai sudah dibaca
- Balas pesan (opsional: kirim email balasan)
- Hapus pesan

---

### 4.5 CMS — Super Admin Only

#### F-SPA-01: Manajemen User
- List user: nama, email, role, status aktif, terakhir login
- Tambah user: nama, email, password, role, status
- Edit user: semua field kecuali password (ada form terpisah untuk reset password)
- Nonaktifkan user (tidak bisa login)
- Hapus user (tidak bisa hapus akun sendiri)

#### F-SPA-02: Manajemen Role & Hak Akses
- List role yang tersedia: Super Admin, Admin
- Tampilkan daftar permission/akses per role
- Edit keterangan role (tidak bisa hapus role sistem)

#### F-SPA-03: Pengaturan Website
- Form pengaturan global:
  - Nama website
  - Tagline
  - Logo (upload)
  - Favicon (upload)
  - Deskripsi singkat
  - Email dinas
  - Telepon dinas
  - Alamat dinas
  - Jam operasional
  - Link Facebook, Instagram, YouTube, Twitter
  - Teks footer

#### F-SPA-04: Log Aktivitas
- List log: user, aksi, modul, deskripsi, IP address, waktu
- Filter: berdasarkan user, modul, jenis aksi, rentang tanggal
- Pencarian keyword
- Read-only (tidak bisa diedit/dihapus)
- Export ke Excel (opsional)

---

## 5. Kebutuhan Non-Fungsional

| Kode | Kebutuhan | Detail |
|---|---|---|
| NF-01 | Performa | Halaman publik load < 3 detik pada koneksi normal |
| NF-02 | Keamanan | CSRF protection, SQL injection prevention, XSS filtering |
| NF-03 | Responsif | Tampilan optimal di desktop, tablet, dan mobile |
| NF-04 | Ketersediaan | Uptime minimal 99% pada jam kerja |
| NF-05 | Keamanan File | Validasi ekstensi dan ukuran file upload (max 5MB gambar, 10MB dokumen) |
| NF-06 | Session | Session timeout setelah 2 jam tidak aktif |
| NF-07 | Password | Minimal 8 karakter, kombinasi huruf dan angka |
| NF-08 | SEO | Meta title & meta description dinamis di tiap halaman publik |

---

## 6. Struktur Database

### Tabel: `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Primary key |
| name | VARCHAR(100) | Nama lengkap user |
| email | VARCHAR(100) UNIQUE | Email login |
| password | VARCHAR(255) | Hash password |
| role | ENUM('super_admin','admin') | Role user |
| is_active | TINYINT(1) DEFAULT 1 | Status aktif |
| last_login_at | TIMESTAMP NULL | Waktu login terakhir |
| remember_token | VARCHAR(100) NULL | Token remember me |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `settings`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| key | VARCHAR(100) UNIQUE | Kunci pengaturan |
| value | TEXT NULL | Nilai pengaturan |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `activity_logs`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| user_id | BIGINT UNSIGNED FK | Relasi ke users |
| user_name | VARCHAR(100) | Nama user (snapshot) |
| action | VARCHAR(50) | Jenis aksi: login, create, update, delete, dll |
| module | VARCHAR(100) | Nama modul: Berita, User, Layanan, dll |
| description | TEXT | Deskripsi detail aksi |
| ip_address | VARCHAR(45) | IP address user |
| created_at | TIMESTAMP | Waktu aksi dilakukan |

### Tabel: `news_categories`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| name | VARCHAR(100) | Nama kategori |
| slug | VARCHAR(120) UNIQUE | Slug URL |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `news`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| news_category_id | BIGINT UNSIGNED FK | Relasi ke news_categories |
| user_id | BIGINT UNSIGNED FK | Penulis (relasi ke users) |
| title | VARCHAR(255) | Judul berita |
| slug | VARCHAR(270) UNIQUE | Slug URL |
| thumbnail | VARCHAR(255) NULL | Path thumbnail |
| content | LONGTEXT | Konten HTML berita |
| status | ENUM('draft','published') | Status publikasi |
| published_at | TIMESTAMP NULL | Waktu dipublish |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `service_categories`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| name | VARCHAR(200) | Nama bidang/kategori layanan |
| slug | VARCHAR(220) UNIQUE | Slug URL |
| description | TEXT NULL | Deskripsi singkat bidang |
| icon | VARCHAR(255) NULL | Path ikon/gambar kategori |
| order | INT DEFAULT 0 | Urutan tampil |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `services`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| service_category_id | BIGINT UNSIGNED FK | Relasi ke service_categories |
| title | VARCHAR(200) | Nama layanan |
| slug | VARCHAR(220) UNIQUE | Slug URL |
| description | TEXT | Deskripsi singkat layanan |
| requirements | LONGTEXT NULL | Persyaratan (HTML list) |
| procedure | LONGTEXT NULL | Mekanisme & prosedur (HTML ordered list) |
| duration | VARCHAR(150) NULL | Jangka waktu penyelesaian |
| cost | VARCHAR(150) NULL | Biaya/tarif (teks) |
| product | VARCHAR(255) NULL | Produk/output yang dihasilkan dari layanan |
| icon | VARCHAR(255) NULL | Path ikon/gambar layanan |
| is_active | TINYINT(1) DEFAULT 1 | Status aktif |
| order | INT DEFAULT 0 | Urutan tampil |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `gallery_albums`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| name | VARCHAR(150) | Nama album |
| description | TEXT NULL | Deskripsi album |
| cover | VARCHAR(255) NULL | Path foto cover |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `gallery_photos`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| gallery_album_id | BIGINT UNSIGNED FK | Relasi ke gallery_albums |
| title | VARCHAR(200) NULL | Judul foto |
| image | VARCHAR(255) | Path foto |
| description | TEXT NULL | Deskripsi foto |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `gallery_videos`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| title | VARCHAR(200) | Judul video |
| url | VARCHAR(500) | URL YouTube |
| thumbnail | VARCHAR(255) NULL | Path thumbnail |
| description | TEXT NULL | Deskripsi video |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `documents`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| title | VARCHAR(255) | Judul dokumen |
| category | VARCHAR(100) | Kategori dokumen |
| description | TEXT NULL | Deskripsi singkat |
| file | VARCHAR(255) | Path file |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `banners`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| title | VARCHAR(200) NULL | Judul banner |
| image | VARCHAR(255) | Path gambar |
| link | VARCHAR(500) NULL | URL tujuan klik |
| order | INT DEFAULT 0 | Urutan tampil |
| is_active | TINYINT(1) DEFAULT 1 | Status aktif |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `profile_contents`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| key | VARCHAR(100) UNIQUE | Kunci konten: history, vision, mission, tupoksi |
| value | LONGTEXT | Konten HTML |
| updated_at | TIMESTAMP | |

### Tabel: `organization_members`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| name | VARCHAR(150) | Nama pejabat |
| position | VARCHAR(200) | Jabatan |
| photo | VARCHAR(255) NULL | Path foto |
| order | INT DEFAULT 0 | Urutan tampil |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel: `contacts`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| name | VARCHAR(150) | Nama pengirim |
| email | VARCHAR(150) | Email pengirim |
| phone | VARCHAR(20) NULL | No. telepon pengirim |
| subject | VARCHAR(255) | Subjek pesan |
| message | TEXT | Isi pesan |
| is_read | TINYINT(1) DEFAULT 0 | Status baca |
| reply | TEXT NULL | Isi balasan |
| replied_at | TIMESTAMP NULL | Waktu dibalas |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

---

## 7. Routing

### 7.1 Public Routes (web.php)

```
GET  /                          → HomeController@index
GET  /profil                    → ProfileController@index
GET  /berita                    → NewsController@index
GET  /berita/{slug}             → NewsController@show
GET  /layanan                   → ServiceController@index          (?kategori={slug} untuk filter)
GET  /layanan/kategori/{slug}   → ServiceController@byCategory      (halaman per kategori/bidang)
GET  /layanan/{slug}            → ServiceController@show
GET  /galeri/foto               → GalleryPhotoController@index
GET  /galeri/video              → GalleryVideoController@index
GET  /dokumen                   → DocumentController@index
GET  /kontak                    → ContactController@index
POST /kontak                    → ContactController@store
```

### 7.2 Auth Routes

```
GET  /cms/login                 → AuthController@showLogin
POST /cms/login                 → AuthController@login
GET  /cms/lupa-password         → AuthController@showForgotPassword
POST /cms/lupa-password         → AuthController@forgotPassword
GET  /cms/reset-password/{token}→ AuthController@showResetPassword
POST /cms/reset-password        → AuthController@resetPassword
POST /cms/logout                → AuthController@logout
```

### 7.3 CMS Routes — Admin & Super Admin (prefix: /cms)

```
GET  /cms/dashboard             → DashboardController@index

// Berita
GET  /cms/berita                → CmsNewsController@index
GET  /cms/berita/tambah         → CmsNewsController@create
POST /cms/berita                → CmsNewsController@store
GET  /cms/berita/{id}/edit      → CmsNewsController@edit
PUT  /cms/berita/{id}           → CmsNewsController@update
DEL  /cms/berita/{id}           → CmsNewsController@destroy
PUT  /cms/berita/{id}/toggle    → CmsNewsController@toggleStatus

// Kategori Berita
GET  /cms/kategori-berita       → CmsNewsCategoryController@index
POST /cms/kategori-berita       → CmsNewsCategoryController@store
PUT  /cms/kategori-berita/{id}  → CmsNewsCategoryController@update
DEL  /cms/kategori-berita/{id}  → CmsNewsCategoryController@destroy

// Kategori Layanan
GET  /cms/kategori-layanan              → CmsServiceCategoryController@index
POST /cms/kategori-layanan              → CmsServiceCategoryController@store
PUT  /cms/kategori-layanan/{id}         → CmsServiceCategoryController@update
DEL  /cms/kategori-layanan/{id}         → CmsServiceCategoryController@destroy

// Layanan
GET  /cms/layanan                       → CmsServiceController@index
GET  /cms/layanan/tambah                → CmsServiceController@create
POST /cms/layanan                       → CmsServiceController@store
GET  /cms/layanan/{id}/edit             → CmsServiceController@edit
PUT  /cms/layanan/{id}                  → CmsServiceController@update
DEL  /cms/layanan/{id}                  → CmsServiceController@destroy
PUT  /cms/layanan/{id}/toggle           → CmsServiceController@toggleActive

// Galeri Foto
GET|POST        /cms/galeri/album               → CmsAlbumController
GET|PUT|DEL     /cms/galeri/album/{id}          → CmsAlbumController
GET|POST        /cms/galeri/foto/{albumId}       → CmsPhotoController
DEL             /cms/galeri/foto/{id}            → CmsPhotoController@destroy

// Galeri Video
GET|POST        /cms/galeri/video       → CmsVideoController
GET|PUT|DEL     /cms/galeri/video/{id}  → CmsVideoController

// Dokumen
GET|POST        /cms/dokumen            → CmsDocumentController
GET|PUT|DEL     /cms/dokumen/{id}       → CmsDocumentController

// Banner
GET|POST        /cms/banner             → CmsBannerController
GET|PUT|DEL     /cms/banner/{id}        → CmsBannerController

// Profil Dinas
GET  /cms/profil                → CmsProfileController@index
PUT  /cms/profil                → CmsProfileController@update
POST /cms/profil/organisasi     → CmsOrganizationController@store
PUT  /cms/profil/organisasi/{id}→ CmsOrganizationController@update
DEL  /cms/profil/organisasi/{id}→ CmsOrganizationController@destroy

// Pesan Masuk
GET  /cms/pesan                 → CmsContactController@index
GET  /cms/pesan/{id}            → CmsContactController@show
PUT  /cms/pesan/{id}/baca       → CmsContactController@markRead
DEL  /cms/pesan/{id}            → CmsContactController@destroy
```

### 7.4 CMS Routes — Super Admin Only (prefix: /cms)

```
// User
GET|POST        /cms/user       → CmsUserController
GET|PUT|DEL     /cms/user/{id}  → CmsUserController

// Role
GET             /cms/role       → CmsRoleController@index

// Pengaturan
GET  /cms/pengaturan            → CmsSettingController@index
PUT  /cms/pengaturan            → CmsSettingController@update

// Log Aktivitas
GET  /cms/log-aktivitas         → CmsActivityLogController@index
GET  /cms/log-aktivitas/export  → CmsActivityLogController@export
```

---

## 8. Seeder & Default Data

Semua seeder dipanggil melalui `DatabaseSeeder.php` secara berurutan sesuai dependensi relasi antar tabel.

### Urutan Pemanggilan di `DatabaseSeeder`

```php
$this->call([
    UserSeeder::class,
    SettingSeeder::class,
    ProfileContentSeeder::class,
    OrganizationMemberSeeder::class,
    NewsCategorySeeder::class,
    NewsSeeder::class,
    ServiceCategorySeeder::class,   // harus sebelum ServiceSeeder
    ServiceSeeder::class,
    GalleryAlbumSeeder::class,
    GalleryPhotoSeeder::class,
    GalleryVideoSeeder::class,
    DocumentSeeder::class,
    BannerSeeder::class,
    ContactSeeder::class,
]);
```

> `activity_logs` tidak perlu seeder — data dihasilkan otomatis oleh sistem saat ada aksi user.

---

### `UserSeeder`

| Field | Super Admin | Admin |
|---|---|---|
| name | Administrator Utama | Admin Dinas |
| email | `superadmin@perikanan.go.id` | `admin@perikanan.go.id` |
| password | `Password123` (bcrypt) | `Password123` (bcrypt) |
| role | `super_admin` | `admin` |
| is_active | 1 | 1 |

---

### `SettingSeeder`

| Key | Value Default |
|---|---|
| `nama_website` | Portal Informasi Dinas Perikanan |
| `tagline` | Melayani dengan Profesional, Membangun Perikanan Berkelanjutan |
| `deskripsi` | Website resmi Dinas Perikanan yang menyediakan informasi layanan, program, dan kegiatan dinas. |
| `email` | info@perikanan.go.id |
| `telepon` | (0281) 123456 |
| `fax` | (0281) 123457 |
| `alamat` | Jl. Merdeka No. 1, Purwokerto, Jawa Tengah 53111 |
| `jam_operasional` | Senin–Jumat: 08.00–16.00 WIB |
| `logo` | assets/images/logo-default.png |
| `favicon` | assets/images/favicon.ico |
| `facebook_url` | https://facebook.com/dinasperikanan |
| `instagram_url` | https://instagram.com/dinasperikanan |
| `youtube_url` | https://youtube.com/@dinasperikanan |
| `twitter_url` | https://twitter.com/dinasperikanan |
| `teks_footer` | © 2025 Dinas Perikanan. Seluruh hak cipta dilindungi undang-undang. |
| `google_maps_embed` | *(URL embed Google Maps kantor dinas)* |
| `statistik_nelayan` | 1.240 |
| `statistik_produksi` | 8.500 Ton |
| `statistik_pokdakan` | 320 Kelompok |
| `statistik_layanan` | 12 Layanan |

---

### `ProfileContentSeeder`

| Key | Konten Default |
|---|---|
| `sejarah` | *(Paragraf HTML placeholder tentang sejarah berdirinya dinas)* |
| `visi` | Terwujudnya sektor perikanan yang maju, mandiri, dan berdaya saing untuk kemakmuran masyarakat. |
| `misi` | *(List HTML: 1. Meningkatkan produksi... 2. Mengembangkan SDM... 3. Memperkuat kelembagaan... 4. Meningkatkan pelayanan...)* |
| `tupoksi` | *(Paragraf HTML tentang tugas pokok dan fungsi dinas sesuai peraturan yang berlaku)* |

---

### `OrganizationMemberSeeder`

| No | name | position | order |
|---|---|---|---|
| 1 | Drs. Budi Santoso, M.Si | Kepala Dinas | 1 |
| 2 | Ir. Siti Rahayu | Sekretaris Dinas | 2 |
| 3 | Ahmad Fauzi, S.Pi | Kabid Budidaya Perikanan | 3 |
| 4 | Dewi Kusuma, S.St.Pi | Kabid Penangkapan Ikan | 4 |
| 5 | Eko Prasetyo, S.Pi | Kabid Pengolahan & Pemasaran | 5 |
| 6 | Rina Hartati, S.E | Kasubbag Keuangan | 6 |

> `photo`: gunakan foto placeholder default `/assets/images/avatar-default.png`

---

### `NewsCategorySeeder`

| No | name | slug |
|---|---|---|
| 1 | Informasi | informasi |
| 2 | Kegiatan | kegiatan |
| 3 | Pengumuman | pengumuman |
| 4 | Program | program |
| 5 | Berita Dinas | berita-dinas |

---

### `NewsSeeder`

| No | title | category | status |
|---|---|---|---|
| 1 | Dinas Perikanan Gelar Pelatihan Budidaya Lele untuk Pokdakan | Kegiatan | published |
| 2 | Program Bantuan Bibit Ikan Gratis Dibuka untuk Kelompok Pembudidaya | Program | published |
| 3 | Capaian Produksi Perikanan Melampaui Target Tahun Ini | Informasi | published |
| 4 | Kunjungan Kerja Bupati ke Sentra Budidaya Udang Vaname | Kegiatan | published |
| 5 | Sosialisasi Larangan Penggunaan Alat Tangkap Tidak Ramah Lingkungan | Pengumuman | published |
| 6 | Dinas Perikanan Raih Penghargaan Inovasi Pelayanan Publik | Berita Dinas | published |
| 7 | Peluncuran Aplikasi e-Surat Izin Usaha Perikanan | Berita Dinas | draft |

> Semua berita: `user_id = 1` (Super Admin), `thumbnail` = placeholder default, `content` = lorem ipsum HTML 3 paragraf, `published_at` = berbeda-beda dalam 30 hari terakhir.

---

### `ServiceCategorySeeder`

Total: **5 kategori** sesuai bidang/unit di DKPP Kabupaten Banyumas.

| No | name | slug | description | order |
|---|---|---|---|---|
| 1 | Bidang Penganekaragaman dan Keamanan Pangan | penganekaragaman-keamanan-pangan | Layanan registrasi, sertifikasi, dan pengujian pangan segar asal tumbuhan dan ikan untuk menjamin keamanan pangan masyarakat. | 1 |
| 2 | Bidang Ketersediaan dan Stabilisasi Pangan | ketersediaan-stabilisasi-pangan | Layanan fasilitasi akses pangan murah bagi masyarakat sebagai upaya stabilisasi harga dan pengendalian inflasi komoditas pangan. | 2 |
| 3 | Bidang Penanganan Kerawanan Pangan | penanganan-kerawanan-pangan | Layanan intervensi dan penyaluran bantuan pangan bagi masyarakat di desa rentan pangan berdasarkan pemetaan FSVA dan SKPG. | 3 |
| 4 | Bidang Perikanan | perikanan | Layanan pembinaan, pendampingan, sertifikasi, dan pemeriksaan teknis bagi pembudidaya dan kelompok perikanan di Kabupaten Banyumas. | 4 |
| 5 | UPTD Pembenihan dan Budidaya Air Tawar (PBAT) | uptd-pbat | Layanan penjualan benih ikan berkualitas dari unit pembenihan milik pemerintah daerah secara langsung maupun melalui aplikasi SI-IKANMAS. | 5 |

> `icon`: gunakan placeholder ikon SVG/PNG per kategori. `description` diisi sesuai tabel di atas.

---

### `ServiceSeeder`

Total: **12 layanan aktif** dari 5 bidang/unit, sesuai dokumen Standar Pelayanan DKPP Kabupaten Banyumas.

---

#### Bidang Penganekaragaman dan Keamanan Pangan

**Layanan 1 — Registrasi PSAT PDUK**

| Field | Value |
|---|---|
| `service_category_id` | 1 (Bidang Penganekaragaman dan Keamanan Pangan) |
| `title` | Registrasi Pangan Segar Asal Tumbuhan Produksi Dalam Negeri Usaha Kecil (PSAT-PDUK) |
| `slug` | registrasi-psat-pduk |
| `description` | Layanan registrasi bagi pelaku usaha kecil yang memproduksi pangan segar asal tumbuhan untuk mendapatkan rekomendasi PB-UMKU melalui sistem OSS. |
| `requirements` | `<ul><li>Nomor Induk Berusaha (NIB)</li><li>Surat Permohonan</li><li>Surat Informasi Produk</li><li>Surat Pernyataan Komitmen (dibubuhi materai)</li></ul>` |
| `procedure` | `<ol><li>Pemohon datang ke kantor DKPP Banyumas untuk konsultasi</li><li>Pemohon mengunduh formulir di website oss.go.id</li><li>Pemohon mencetak formulir kemudian mengunggah scan dokumen persyaratan di oss.go.id</li><li>Pemohon menunggu hasil verifikasi dokumen</li><li>Jika memenuhi persyaratan maka diproses; jika tidak, dikembalikan kepada pemohon</li><li>Pemohon mengunduh PB-UMKU registrasi PSAT-PDUK melalui oss.go.id</li></ol>` |
| `duration` | 14 Hari Kerja |
| `cost` | Tidak ada biaya/tarif |
| `product` | Surat Rekomendasi Pengajuan PB-UMKU Registrasi PSAT-PDUK |
| `is_active` | 1 |
| `order` | 1 |

---

**Layanan 2 — Penerbitan Rekomendasi SKP**

| Field | Value |
|---|---|
| `service_category_id` | 1 (Bidang Penganekaragaman dan Keamanan Pangan) |
| `title` | Penerbitan Rekomendasi Sertifikasi Kelayakan Pengolahan (SKP) |
| `slug` | penerbitan-rekomendasi-skp |
| `description` | Layanan penerbitan rekomendasi sertifikasi kelayakan pengolahan bagi Unit Pengolah Ikan (UPI) yang telah memenuhi standar GMP dan SSOP. |
| `requirements` | `<ul><li>Nomor Induk Berusaha (NIB)</li></ul>` |
| `procedure` | `<ol><li>UPI/Pelaku Usaha mengajukan permohonan SKP melalui akun SKP online di web OSS dan mengisi formulir pengajuan</li><li>Kepala Dinas menerima pengajuan dan membuat disposisi pelaksanaan pembinaan</li><li>Pembina Mutu melakukan pembinaan Pra SKP</li><li>Pembina Mutu melakukan pengecekan kelengkapan persyaratan SKP</li><li>Jika persyaratan terpenuhi, Pembina Mutu melakukan pembinaan dan pengecekan penerapan GMP dan SSOP di UPI</li><li>Pembina Mutu memberikan saran perbaikan serta melakukan verifikasi tindak lanjut perbaikan UPI</li><li>Pengiriman dokumen Rekomendasi Penerbitan SKP ke Dirjen PDSPKP melalui web OSS</li></ol>` |
| `duration` | Maksimal 60 Hari Kerja |
| `cost` | Tidak ada biaya/tarif |
| `product` | Surat Rekomendasi SKP |
| `is_active` | 1 |
| `order` | 2 |

---

**Layanan 3 — Uji PSAT PDUK dan PSAI**

| Field | Value |
|---|---|
| `service_category_id` | 1 (Bidang Penganekaragaman dan Keamanan Pangan) |
| `title` | Uji Pangan Segar Asal Tumbuhan (PSAT-PDUK) dan Pangan Segar Asal Ikan (PSAI) |
| `slug` | uji-psat-pduk-psai |
| `description` | Layanan pengujian laboratorium untuk pangan segar asal tumbuhan dan pangan segar asal ikan guna memastikan keamanan pangan yang beredar di masyarakat. |
| `requirements` | `<ul><li>Isian Formulir Permohonan Pengujian (disediakan di loket atau via aplikasi)</li><li>Fotokopi KTP/Identitas Pemohon</li><li>Fotokopi NIB atau Surat Keterangan Usaha (khusus untuk PSAT-PDUK)</li><li>Surat Pengantar Sampel (jika dikirim via ekspedisi)</li><li><strong>Sampel PSAT-PDUK:</strong> Segar (beras, buah, sayur) dalam kondisi baik, dikemas rapi, minimal 500 gram s.d. 1 kg</li><li><strong>Sampel PSAI:</strong> Ikan/hasil perikanan segar/beku dalam cool box + es/gel (suhu &lt;4°C), minimal 500 gram</li></ul>` |
| `procedure` | `<ol><li>Pemohon membawa/mengirim sampel ke Loket Pelayanan; petugas memeriksa kelengkapan administrasi dan kondisi teknis sampel</li><li>Petugas melakukan registrasi dan menerbitkan Kode Sampel</li><li>Sampel yang memenuhi syarat didistribusikan ke laboratorium teknis untuk pengujian</li><li>Manajer Teknis memvalidasi data mentah dan hasil uji</li><li>Kepala Laboratorium/Pejabat berwenang menandatangani Laporan Hasil Uji</li><li>Petugas loket menyerahkan LHU asli kepada pemohon</li></ol>` |
| `duration` | 5–7 Hari Kerja (reguler); 7–10 Hari Kerja (kompleks: residu pestisida, logam berat, formalin) |
| `cost` | Tidak ada biaya |
| `product` | Laporan Hasil Uji (LHU) / Certificate of Analysis |
| `is_active` | 1 |
| `order` | 3 |

---

#### Bidang Ketersediaan dan Stabilisasi Pangan

**Layanan 4 — Gerakan Pangan Murah (GPM)**

| Field | Value |
|---|---|
| `service_category_id` | 2 (Bidang Ketersediaan dan Stabilisasi Pangan) |
| `title` | Gerakan Pangan Murah (GPM) |
| `slug` | gerakan-pangan-murah |
| `description` | Fasilitasi pasar pangan murah bagi masyarakat sebagai upaya pengendalian inflasi harga komoditas pangan strategis di Kabupaten Banyumas. |
| `requirements` | `<ul><li>Surat Permohonan dari Desa/Kelurahan/Lembaga Masyarakat/Perusahaan</li><li>Atau berdasarkan Hasil Rapat Koordinasi Pengendalian Inflasi</li><li>Atau berdasarkan Hasil Analisa Peringatan Dini Kewaspadaan Pangan dan Gizi (SKPG)</li></ul>` |
| `procedure` | `<ol><li>Pemohon datang ke Kantor DKPP Kab. Banyumas dan menyerahkan surat permohonan</li><li>Pemohon menunggu hasil koordinasi dan pemeriksaan lapang</li><li>Jika memenuhi persyaratan, akan diproses lebih lanjut; jika tidak, disampaikan kepada pemohon</li><li>Pemohon memperoleh fasilitasi Gerakan Pangan Murah</li></ol>` |
| `duration` | 7 Hari Kerja |
| `cost` | Tidak ada biaya |
| `product` | Fasilitasi Gerakan Pangan Murah (GPM) |
| `is_active` | 1 |
| `order` | 4 |

---

#### Bidang Penanganan Kerawanan Pangan

**Layanan 5 — Intervensi Kewaspadaan Pangan dan Gizi**

| Field | Value |
|---|---|
| `service_category_id` | 3 (Bidang Penanganan Kerawanan Pangan) |
| `title` | Intervensi Kewaspadaan Pangan dan Gizi |
| `slug` | intervensi-kewaspadaan-pangan-gizi |
| `description` | Penyaluran bantuan komoditas pangan bergizi kepada masyarakat di desa rentan pangan prioritas berdasarkan hasil pemetaan FSVA dan SKPG. |
| `requirements` | `<ul><li>Penerima bantuan wajib terdaftar dalam desa rentan pangan prioritas 1, 2, dan 3 sesuai hasil peta rawan pangan (FSVA)</li><li>Sesuai hasil peta situasi kewaspadaan pangan dan gizi (SKPG)</li></ul>` |
| `procedure` | `<ol><li>Pengumpulan data untuk menentukan sasaran penerima bantuan</li><li>Analisis data kewaspadaan pangan dan gizi</li><li>Verifikasi data penerima</li><li>Penetapan sasaran penerima bantuan</li><li>Penyaluran bantuan dilakukan setelah penetapan data sasaran dari desa prioritas 1 dan 2 sesuai pemetaan FSVA dan SKPG</li><li>Monitoring, Evaluasi, dan Pelaporan</li></ol>` |
| `duration` | ±1 bulan; penyaluran dilakukan secara periodik 2 kali |
| `cost` | Tidak ada biaya/tarif |
| `product` | Bantuan komoditas pangan yang bermutu, bergizi tinggi, dan aman dikonsumsi |
| `is_active` | 1 |
| `order` | 5 |

---

**Layanan 6 — Penyaluran Beras Cadangan Pangan Pemerintah Daerah (CPPD)**

| Field | Value |
|---|---|
| `service_category_id` | 3 (Bidang Penanganan Kerawanan Pangan) |
| `title` | Penyaluran Beras Cadangan Pangan Pemerintah Daerah (CPPD) |
| `slug` | penyaluran-beras-cppd |
| `description` | Penyaluran bantuan beras dari cadangan pangan pemerintah daerah kepada masyarakat terdampak bencana atau kerawanan pangan atas dasar disposisi Bupati. |
| `requirements` | `<ul><li>Surat Permintaan dari Kades/Lurah mengetahui Camat, atau Surat Permintaan Kepala BPBD, atau SK Tanggap Darurat dari Bupati</li><li>Surat Permintaan kepada Bupati (tembusan Kepala DKPP) dilampiri By Name By Address (BNBA) penerima bantuan</li><li>Disposisi dari Bupati untuk menindaklanjuti penyaluran CPPD</li></ul>` |
| `procedure` | `<ol><li>Pemohon menyampaikan surat permintaan bantuan CPPD kepada Bupati (tembusan Kepala DKPP) dilampiri jumlah penerima, BNBA, dan jumlah CPPD yang diminta</li><li>Jika disposisi Bupati "ditindaklanjuti" maka bantuan CPPD akan disalurkan</li><li>Masyarakat menerima bantuan CPPD berupa beras</li></ol>` |
| `duration` | 1 Hari Kerja |
| `cost` | Tidak ada biaya/tarif |
| `product` | Bantuan Beras Cadangan Pangan Pemerintah Daerah Kabupaten Banyumas |
| `is_active` | 1 |
| `order` | 6 |

---

#### Bidang Perikanan

**Layanan 7 — Pendampingan CPIB**

| Field | Value |
|---|---|
| `service_category_id` | 4 (Bidang Perikanan) |
| `title` | Pendampingan Cara Pembenihan Ikan yang Baik (CPIB) |
| `slug` | pendampingan-cpib |
| `description` | Layanan pendampingan dan pembinaan bagi pembudidaya ikan aktif di bidang pembenihan untuk memenuhi standar Cara Pembenihan Ikan yang Baik (CPIB). |
| `requirements` | `<ul><li>Pembudidaya Ikan Aktif (Pembenihan Ikan)</li><li>Memenuhi Persyaratan Teknis CPIB</li><li>Memenuhi Persyaratan Administrasi CPIB</li></ul>` |
| `procedure` | `<ol><li>Pemohon melakukan pendaftaran secara online maupun offline</li><li>Pemeriksaan dokumen pemohon oleh petugas</li></ol>` |
| `duration` | Kondisional |
| `cost` | Tidak dipungut biaya |
| `product` | Laporan Pembinaan CPIB |
| `is_active` | 1 |
| `order` | 7 |

---

**Layanan 8 — Pendampingan CBIB**

| Field | Value |
|---|---|
| `service_category_id` | 4 (Bidang Perikanan) |
| `title` | Pendampingan Cara Budidaya Ikan yang Baik (CBIB) |
| `slug` | pendampingan-cbib |
| `description` | Layanan pendampingan dan pembinaan bagi pembudidaya ikan aktif di bidang pembesaran untuk memenuhi standar Cara Budidaya Ikan yang Baik (CBIB). |
| `requirements` | `<ul><li>Pembudidaya Ikan Aktif (Pembesaran)</li><li>Memenuhi Persyaratan Teknis CBIB</li><li>Memenuhi Persyaratan Administrasi CBIB</li></ul>` |
| `procedure` | `<ol><li>Pemohon melakukan pendaftaran secara online maupun offline</li><li>Pemeriksaan dokumen pemohon oleh petugas</li></ol>` |
| `duration` | Kondisional |
| `cost` | Tidak dipungut biaya |
| `product` | Laporan Pembinaan CBIB |
| `is_active` | 1 |
| `order` | 8 |

---

**Layanan 9 — Penerbitan STDK**

| Field | Value |
|---|---|
| `service_category_id` | 4 (Bidang Perikanan) |
| `title` | Penerbitan Surat Tanda Daftar Kelompok Perikanan (STDK) |
| `slug` | penerbitan-stdk |
| `description` | Layanan penerbitan surat tanda daftar resmi bagi kelompok pembudidaya ikan yang telah memenuhi persyaratan administrasi dan kelembagaan. |
| `requirements` | `<ul><li>Surat permohonan STDK ditandatangani ketua kelompok</li><li>Fotokopi Surat Pengesahan/SK Pembentukan Kelompok Pembudidaya Ikan</li><li>Fotokopi Berita Acara Pembentukan Kelompok</li><li>Fotokopi AD-ART Kelompok</li><li>Fotokopi KTP seluruh pengurus dan anggota kelompok</li></ul>` |
| `procedure` | `<ol><li>Pemohon menyampaikan surat permohonan penerbitan STDK dengan melampirkan seluruh dokumen persyaratan</li><li>Petugas melakukan pemeriksaan kelengkapan dokumen persyaratan</li><li>Penerbitan STDK oleh pejabat berwenang</li><li>Penyerahan STDK kepada pemohon</li></ol>` |
| `duration` | Maksimal 3 Hari Kerja |
| `cost` | Tidak dikenakan biaya |
| `product` | Surat Tanda Daftar Kelompok Perikanan (STDK) |
| `is_active` | 1 |
| `order` | 9 |

---

**Layanan 10 — Pemeriksaan Kualitas Air Perikanan**

| Field | Value |
|---|---|
| `service_category_id` | 4 (Bidang Perikanan) |
| `title` | Pemeriksaan Kualitas Air Perikanan |
| `slug` | pemeriksaan-kualitas-air |
| `description` | Layanan pemeriksaan kualitas air kolam budidaya oleh petugas POSIKANDU secara langsung ke lokasi, disertai pemberian saran dan rekomendasi untuk mendukung keberhasilan budidaya. |
| `requirements` | `<ul><li>Kelompok Pembudidaya Ikan (Pokdakan)</li><li>Pembudidaya Perorangan</li></ul>` |
| `procedure` | `<ol><li>Pemohon mengajukan permohonan uji kualitas air kolam melalui telepon / datang langsung / surat</li><li>Petugas POSIKANDU menentukan jadwal pemeriksaan ke lapangan</li><li>Petugas POSIKANDU mendatangi lokasi dan melakukan pemeriksaan kualitas air kolam budidaya</li><li>Petugas POSIKANDU menyampaikan hasil uji kualitas air dan memberikan saran serta rekomendasi untuk kegiatan budidaya</li></ol>` |
| `duration` | Maksimal 2 Hari |
| `cost` | Gratis |
| `product` | Form Hasil Uji Kualitas Air Budidaya Perikanan |
| `is_active` | 1 |
| `order` | 10 |

---

**Layanan 11 — Pemeriksaan Hama dan Penyakit Ikan**

| Field | Value |
|---|---|
| `service_category_id` | 4 (Bidang Perikanan) |
| `title` | Pemeriksaan Hama dan Penyakit Ikan |
| `slug` | pemeriksaan-hama-penyakit-ikan |
| `description` | Layanan pemeriksaan hama dan penyakit ikan pada kolam budidaya oleh petugas POSIKANDU secara langsung ke lokasi, disertai saran penanganan dan rekomendasi budidaya. |
| `requirements` | `<ul><li>Kelompok Pembudidaya Ikan (Pokdakan)</li><li>Pembudidaya Perorangan</li></ul>` |
| `procedure` | `<ol><li>Pemohon mengajukan permohonan pemeriksaan hama dan penyakit ikan melalui telepon / datang langsung / surat</li><li>Petugas POSIKANDU menentukan jadwal pemeriksaan ke lapangan</li><li>Petugas POSIKANDU mendatangi lokasi dan melakukan pemeriksaan hama dan kualitas air kolam budidaya</li><li>Petugas POSIKANDU menyampaikan hasil pemeriksaan dan memberikan saran serta rekomendasi untuk kegiatan budidaya</li></ol>` |
| `duration` | Maksimal 2 Hari |
| `cost` | Tidak dipungut biaya |
| `product` | Lembar Form Hasil Pemeriksaan Hama dan Penyakit Ikan |
| `is_active` | 1 |
| `order` | 11 |

---

#### UPTD PBAT

**Layanan 12 — Penjualan Benih Ikan**

| Field | Value |
|---|---|
| `service_category_id` | 5 (UPTD Pembenihan dan Budidaya Air Tawar / PBAT) |
| `title` | Penjualan Benih Ikan |
| `slug` | penjualan-benih-ikan |
| `description` | Layanan penjualan benih ikan berkualitas dari UPTD PBAT secara langsung maupun melalui aplikasi SI-IKANMAS, disertai Surat Keterangan Asal Ikan. |
| `requirements` | `<ul><li>Permohonan pelayanan penjualan benih ikan</li><li>Identitas pemohon (KTP)</li></ul>` |
| `procedure` | `<p><strong>Pembelian Langsung:</strong></p><ol><li>Pembeli datang langsung ke UPTD PBAT (Senin–Kamis 07.30–15.30 WIB, Jumat 07.30–15.15 WIB)</li><li>Pembeli diantar ke kolam penampungan untuk melihat stok ikan</li><li>Pembeli melakukan pemesanan jumlah dan waktu pengambilan</li><li>Melakukan pembayaran tunai</li><li>Ikan dipacking dan diberikan kepada pembeli</li><li>Menerima Surat Keterangan Asal Ikan</li></ol><p><strong>Pembelian Online (Aplikasi SI-IKANMAS):</strong></p><ol><li>Unduh Aplikasi SI-IKANMAS dari Playstore</li><li>Daftarkan akun dengan data diri (KTP, nomor telepon, e-mail)</li><li>Lakukan pemesanan ikan di aplikasi</li><li>Lakukan pembayaran di lokasi pengambilan ikan</li><li>Ambil ikan yang sudah dipacking</li><li>Menerima Surat Keterangan Asal Ikan</li></ol>` |
| `duration` | 1 Hari |
| `cost` | Sesuai Perda Kabupaten Banyumas Nomor 1 Tahun 2025 |
| `product` | Benih Ikan + Surat Keterangan Asal Ikan |
| `is_active` | 1 |
| `order` | 12 |

---

### `GalleryAlbumSeeder`

| No | name | description |
|---|---|---|
| 1 | Kegiatan Budidaya 2024 | Dokumentasi kegiatan budidaya ikan selama tahun 2024 |
| 2 | Pelatihan & Sosialisasi | Foto-foto kegiatan pelatihan dan sosialisasi bersama pokdakan |
| 3 | Kunjungan Lapangan | Dokumentasi kunjungan ke lokasi sentra perikanan |
| 4 | Panen Raya | Momen panen raya bersama kelompok pembudidaya |

---

### `GalleryPhotoSeeder`

Setiap album diisi **3 foto placeholder** dengan data:

| Field | Value |
|---|---|
| `image` | `assets/images/placeholder-gallery.jpg` |
| `title` | Foto Kegiatan [nama album] [nomor urut] |
| `description` | Dokumentasi kegiatan dinas perikanan |

> Total: **12 foto** (3 per album × 4 album)

---

### `GalleryVideoSeeder`

| No | title | url | description |
|---|---|---|---|
| 1 | Cara Budidaya Lele yang Benar dan Menguntungkan | https://www.youtube.com/watch?v=dQw4w9WgXcQ | Panduan lengkap budidaya lele untuk pemula |
| 2 | Sosialisasi Program Bantuan Bibit Ikan 2024 | https://www.youtube.com/watch?v=dQw4w9WgXcQ | Video dokumentasi sosialisasi program bantuan |
| 3 | Profil Dinas Perikanan | https://www.youtube.com/watch?v=dQw4w9WgXcQ | Video profil dan pengenalan Dinas Perikanan |

> Ganti URL dengan video YouTube nyata setelah deploy. `thumbnail` di-generate otomatis dari embed YouTube.

---

### `DocumentSeeder`

| No | title | category | description |
|---|---|---|---|
| 1 | Laporan Kinerja Dinas Perikanan Tahun 2024 | Laporan | Laporan kinerja tahunan dinas perikanan |
| 2 | Laporan Produksi Perikanan Semester I 2024 | Laporan | Data produksi ikan periode Januari–Juni 2024 |
| 3 | Formulir Permohonan Izin Usaha Perikanan | Formulir | Formulir resmi pengajuan IUP |
| 4 | Formulir Pendaftaran Kelompok Pembudidaya Ikan | Formulir | Formulir registrasi pokdakan baru |
| 5 | Panduan Budidaya Ikan Lele Intensif | Panduan | Juknis budidaya lele sistem bioflok |
| 6 | Panduan Cara Budidaya Ikan yang Baik (CBIB) | Panduan | Buku panduan standar CBIB dari KKP |
| 7 | Brosur Program Bantuan Benih Ikan 2024 | Brosur | Informasi program bantuan benih gratis |
| 8 | Data Statistik Perikanan Kabupaten 2023 | Laporan | Kompilasi data statistik sektor perikanan |

---

### `BannerSeeder`

| No | title | is_active | order |
|---|---|---|---|
| 1 | Selamat Datang di Portal Dinas Perikanan | 1 | 1 |
| 2 | Program Bantuan Benih Ikan 2024 | 1 | 2 |
| 3 | Pelatihan Budidaya Ikan — Daftar Sekarang | 1 | 3 |

> `image`: gunakan gambar banner placeholder berukuran 1920×600px. `link`: `null` untuk seeder.

---

### `ContactSeeder`

| No | name | email | subject | is_read |
|---|---|---|---|---|
| 1 | Budi Hartono | budi@email.com | Pertanyaan tentang Program Bantuan Benih | 0 |
| 2 | Sari Dewi | sari@email.com | Prosedur Pengajuan Izin Usaha Perikanan | 1 |
| 3 | Wahyu Pratama | wahyu@email.com | Jadwal Pelatihan Budidaya Lele | 0 |
| 4 | Ningsih | ningsih@email.com | Informasi Pokdakan Wilayah Utara | 1 |

> `message`: diisi dengan teks pesan dummy yang relevan. `phone`: nomor HP dummy.
