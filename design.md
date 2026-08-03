# Design Guide — CMS Portal Informasi Dinas Perikanan

## 1. Filosofi Desain

Portal ini dirancang dengan pendekatan **"Profesional & Terpercaya"** — mencerminkan identitas instansi pemerintah yang berwibawa namun tetap ramah dan mudah diakses oleh masyarakat. Desain mengutamakan:

- **Keterbacaan** — konten mudah dibaca di semua perangkat
- **Kepercayaan** — kesan formal dan resmi sebagai instansi pemerintah
- **Efisiensi** — informasi dapat ditemukan dengan cepat
- **Konsistensi** — tampilan seragam di seluruh halaman

---

## 2. Color Palette

### Warna Utama

| Nama | Hex | Penggunaan |
|---|---|---|
| Primary Blue | `#003F88` | Header, tombol utama, accent heading |
| Ocean Blue | `#0077B6` | Hover state, link aktif, badge |
| Teal | `#00B4D8` | Highlight, border aktif, indikator |
| Accent Gold | `#F4A100` | Badge penting, CTA sekunder, ikon unggulan |

### Warna Netral

| Nama | Hex | Penggunaan |
|---|---|---|
| White | `#FFFFFF` | Background utama, kartu |
| Light Gray | `#F4F6F9` | Background section, tabel zebra |
| Mid Gray | `#ADB5BD` | Border, placeholder, teks sekunder |
| Dark Gray | `#495057` | Teks paragraf |
| Near Black | `#1A1D23` | Heading, teks utama |

### Warna Status

| Status | Hex | Penggunaan |
|---|---|---|
| Success | `#198754` | Notifikasi sukses, badge published |
| Warning | `#FFC107` | Peringatan, draft badge |
| Danger | `#DC3545` | Error, tombol hapus |
| Info | `#0DCAF0` | Informasi tambahan |

### CSS Variables (root)

```css
:root {
  --primary:      #003F88;
  --primary-dark: #002A5C;
  --ocean:        #0077B6;
  --teal:         #00B4D8;
  --gold:         #F4A100;
  --white:        #FFFFFF;
  --light:        #F4F6F9;
  --mid-gray:     #ADB5BD;
  --dark-gray:    #495057;
  --near-black:   #1A1D23;
  --success:      #198754;
  --warning:      #FFC107;
  --danger:       #DC3545;

  --sidebar-width: 260px;
  --topbar-height: 64px;
  --radius-sm: 6px;
  --radius-md: 12px;
  --radius-lg: 20px;
  --shadow-sm: 0 1px 4px rgba(0,0,0,0.08);
  --shadow-md: 0 4px 16px rgba(0,0,0,0.10);
  --transition: 0.25s ease;
}
```

---

## 3. Tipografi

### Font Family

```css
/* Heading — formal, tegas */
font-family: 'Plus Jakarta Sans', sans-serif;

/* Body — bersih, mudah dibaca */
font-family: 'Inter', sans-serif;

/* Fallback */
font-family: system-ui, -apple-system, sans-serif;
```

Import via Google Fonts:
```html
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
```

### Skala Tipografi

| Elemen | Size | Weight | Line Height |
|---|---|---|---|
| H1 (hero/banner) | 2.5rem (40px) | 800 | 1.2 |
| H2 (section title) | 1.875rem (30px) | 700 | 1.3 |
| H3 (card title) | 1.375rem (22px) | 600 | 1.4 |
| H4 (sub section) | 1.125rem (18px) | 600 | 1.4 |
| Body / Paragraf | 1rem (16px) | 400 | 1.7 |
| Small / Caption | 0.875rem (14px) | 400 | 1.5 |
| XSmall / Badge | 0.75rem (12px) | 600 | 1.3 |

---

## 4. Spacing & Grid

### Sistem Spacing (kelipatan 8px)

```
4px   — micro gap (badge padding, inline elements)
8px   — xs (gap antar ikon dan teks)
16px  — sm (padding kartu kecil, gap kolom)
24px  — md (padding section kecil, gap antar kartu)
32px  — lg (padding kartu, gap section)
48px  — xl (padding section utama)
64px  — 2xl (jarak antar section besar)
96px  — 3xl (hero padding)
```

### Grid Publik

- Container max-width: `1200px`, centered
- Gutter: `24px`
- Kolom: Bootstrap 5 grid (12 kolom)
- Breakpoint: `xs < 576px`, `sm 576`, `md 768`, `lg 992`, `xl 1200`

### Grid CMS

- Sidebar: `260px` fixed (collapse ke `72px` pada md)
- Content area: `calc(100% - 260px)`
- Card grid CMS: 2–4 kolom tergantung konten

---

## 5. Komponen UI

### 5.1 Tombol (Button)

```css
/* Primary */
.btn-primary {
  background: var(--primary);
  color: white;
  border-radius: var(--radius-sm);
  padding: 10px 24px;
  font-weight: 600;
  transition: background var(--transition);
}
.btn-primary:hover { background: var(--primary-dark); }

/* Secondary */
.btn-secondary {
  background: transparent;
  border: 2px solid var(--primary);
  color: var(--primary);
}

/* Danger */
.btn-danger { background: var(--danger); color: white; }

/* Icon Button (CMS aksi) */
.btn-icon {
  width: 36px; height: 36px;
  border-radius: var(--radius-sm);
  display: flex; align-items: center; justify-content: center;
}
```

### 5.2 Kartu (Card)

```css
.card {
  background: var(--white);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-sm);
  border: 1px solid #E9ECEF;
  overflow: hidden;
  transition: box-shadow var(--transition), transform var(--transition);
}
.card:hover {
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}
```

**Kartu Berita:**
- Gambar thumbnail (aspect ratio 16:9) di atas
- Badge kategori (ocean blue)
- Judul (H3, 2 baris max dengan ellipsis)
- Tanggal + ikon kalender (small, gray)
- Ringkasan (2–3 baris, ellipsis)
- Link "Selengkapnya →"

**Kartu Layanan:**
- Ikon layanan (bulat, background teal muda)
- Nama layanan (H3)
- Deskripsi singkat (2 baris)
- Tombol "Lihat Detail"

**Kartu Statistik (Beranda):**
- Angka besar (H1, primary blue)
- Label di bawah (small gray)
- Ikon kecil di pojok kanan atas (gold)
- Border-left 4px solid accent color

### 5.3 Badge

```css
.badge-kategori {
  background: var(--ocean);
  color: white;
  border-radius: 4px;
  padding: 3px 10px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.badge-published { background: var(--success); }
.badge-draft     { background: var(--warning); color: #333; }
```

### 5.4 Navbar Publik

- Background: `var(--primary)` (biru tua)
- Logo dinas (kiri) + nama dinas (putih, bold)
- Menu navigasi: putih, hover underline teal
- Tombol "Kontak Kami" (gold, rounded)
- Sticky top + shadow saat scroll
- Mobile: hamburger menu, collapse ke sidebar slide

Struktur menu:
```
Logo + Nama Dinas | Beranda | Profil | Berita | Layanan | Galeri ▼ | Dokumen | Kontak
                                                            └─ Foto
                                                            └─ Video
```

### 5.5 Hero/Slider Beranda

- Full width, height: `520px` desktop / `300px` mobile
- Background image + overlay gradient (primary 60% opacity)
- Teks: judul besar putih + subjudul + tombol CTA
- Dots indicator di bawah
- Auto-slide setiap 5 detik (Swiper.js atau Bootstrap Carousel)

### 5.6 Footer Publik

- Background: `var(--near-black)` (#1A1D23)
- 4 kolom: Info Dinas | Navigasi Cepat | Layanan | Kontak
- Logo putih + deskripsi singkat
- Ikon sosmed: Facebook, Instagram, YouTube
- Bottom bar: "© 2025 Dinas Perikanan. Hak Cipta Dilindungi."
- Teks warna: putih 80% opacity

### 5.7 Sidebar CMS

```
┌─────────────────────────────┐
│  [Logo] CMS Perikanan       │
├─────────────────────────────┤
│  👤 Nama User               │
│     Admin                   │
├─────────────────────────────┤
│  🏠  Dashboard              │
│  📰  Berita              ▼  │
│       └ Semua Berita        │
│       └ Kategori            │
│  🛠   Layanan               │
│  🖼   Galeri             ▼  │
│       └ Foto & Album        │
│       └ Video               │
│  📁  Dokumen                │
│  🖼   Banner                │
│  📋  Profil Dinas           │
│  📨  Pesan Masuk    [3]     │  ← badge merah jumlah belum dibaca
├─────────────────────────────┤
│  [Super Admin Only]         │
│  👥  Manajemen User         │
│  🔑  Hak Akses              │
│  ⚙️   Pengaturan            │
│  📊  Log Aktivitas          │
├─────────────────────────────┤
│  🚪  Logout                 │
└─────────────────────────────┘
```

- Background sidebar: `var(--primary)` (#003F88)
- Teks: putih
- Menu aktif: background `rgba(255,255,255,0.15)`, border-left 4px `var(--teal)`
- Hover: background `rgba(255,255,255,0.08)`
- Collapse ke icon-only pada layar ≤ 768px

### 5.8 Topbar CMS

- Background: putih, shadow bottom
- Kiri: tombol toggle sidebar (hamburger)
- Tengah: breadcrumb halaman aktif
- Kanan: notifikasi bell + avatar user + dropdown (Profil, Logout)

### 5.9 Tabel Data CMS

```css
.table-cms {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}
.table-cms thead th {
  background: var(--light);
  color: var(--near-black);
  font-weight: 600;
  padding: 12px 16px;
  border-bottom: 2px solid #DEE2E6;
  white-space: nowrap;
}
.table-cms tbody tr:hover {
  background: #F8F9FA;
}
.table-cms tbody td {
  padding: 12px 16px;
  border-bottom: 1px solid #EEEEEE;
  vertical-align: middle;
}
```

- Kolom aksi: selalu di kanan, berisi tombol icon (Edit, Hapus, Toggle)
- Gunakan pagination Bootstrap di bawah tabel
- Tampilkan "Tidak ada data" jika kosong

### 5.10 Form CMS

```css
.form-label    { font-weight: 600; font-size: 14px; color: var(--near-black); }
.form-control  { border-radius: var(--radius-sm); border: 1.5px solid #DEE2E6; padding: 10px 14px; }
.form-control:focus { border-color: var(--ocean); box-shadow: 0 0 0 3px rgba(0,119,182,0.15); }
.form-text     { font-size: 12px; color: var(--mid-gray); }
```

- Label di atas input
- Wajib diisi ditandai `*` merah
- Error inline di bawah field (merah kecil)
- Tombol Submit: primary blue, kanan bawah
- Tombol Batal: outline secondary, sebelah tombol submit

---

## 6. Layout Halaman

### 6.1 Layout Publik — Beranda

```
┌───────────────────────────────────────┐
│           NAVBAR (sticky)             │
├───────────────────────────────────────┤
│           HERO SLIDER                 │
│   [Judul besar]  [Sub judul]          │
│   [Tombol CTA]                        │
├───────────────────────────────────────┤
│        STATISTIK (4 kotak)            │
│  [Nelayan] [Produksi] [Layanan] [Dst] │
├───────────────────────────────────────┤
│         BERITA TERBARU                │
│  [Judul Section]     [Lihat Semua →] │
│  [Card] [Card] [Card]                 │
│  [Card] [Card] [Card]                 │
├───────────────────────────────────────┤
│         LAYANAN UNGGULAN              │
│  [Card] [Card] [Card] [Card]          │
├───────────────────────────────────────┤
│         GALERI FOTO TERBARU           │
│  [Foto] [Foto] [Foto] [Foto]          │
├───────────────────────────────────────┤
│              FOOTER                   │
└───────────────────────────────────────┘
```

### 6.2 Layout Publik — Daftar Berita

```
┌───────────────────────────────────────┐
│           NAVBAR                      │
├───────────────────────────────────────┤
│    BREADCRUMB: Beranda > Berita        │
├─────────────────────────┬─────────────┤
│                         │             │
│  DAFTAR BERITA (8 kol)  │  SIDEBAR    │
│  [Filter Kategori]      │  (4 kol)    │
│  [Card Berita]          │             │
│  [Card Berita]          │  Kategori   │
│  [Card Berita]          │  Berita     │
│  [Card Berita]          │  Terpopuler │
│  [Pagination]           │             │
│                         │             │
├─────────────────────────┴─────────────┤
│              FOOTER                   │
└───────────────────────────────────────┘
```

### 6.3 Layout Publik — Detail Berita

```
┌───────────────────────────────────────┐
│           NAVBAR                      │
├───────────────────────────────────────┤
│    BREADCRUMB: Beranda > Berita > ...  │
├─────────────────────────┬─────────────┤
│                         │             │
│  KONTEN BERITA (8 kol)  │  SIDEBAR    │
│  [Thumbnail Besar]      │  (4 kol)    │
│  [Kategori] [Tanggal]   │             │
│  [Judul H1]             │  Berita     │
│  [Konten HTML]          │  Terkait    │
│                         │             │
│  [Share Button]         │  (4 card)   │
│                         │             │
│  BERITA TERKAIT         │             │
│  [Card] [Card] [Card]   │             │
├─────────────────────────┴─────────────┤
│              FOOTER                   │
└───────────────────────────────────────┘
```

### 6.4 Layout CMS — Dashboard

```
┌──────────┬────────────────────────────────────────┐
│          │  TOPBAR                                 │
│ SIDEBAR  ├────────────────────────────────────────┤
│  (260px) │  Selamat datang, [Nama]! [Role]         │
│          ├──────────┬──────────┬──────────┬────────┤
│          │  Total   │  Berita  │  Pesan   │ Galeri │
│          │ Berita   │ Publish  │ Belum    │  Foto  │
│          │   [N]    │   [N]    │ Dibaca   │  [N]   │
│          ├──────────┴──────────┴──────────┴────────┤
│          │  BERITA TERBARU          PESAN TERBARU  │
│          │  [List 5 berita]   │   [List 5 pesan]   │
│          ├────────────────────────────────────────┤
│          │  LOG AKTIVITAS TERAKHIR                 │
│          │  [List 5 log]                          │
└──────────┴────────────────────────────────────────┘
```

### 6.5 Layout CMS — Halaman CRUD (List)

```
┌──────────┬─────────────────────────────────────────┐
│          │  TOPBAR                                  │
│ SIDEBAR  ├─────────────────────────────────────────┤
│          │  [Judul Halaman]      [+ Tambah Button]  │
│          ├─────────────────────────────────────────┤
│          │  [Search Input]  [Filter Dropdown]       │
│          ├─────────────────────────────────────────┤
│          │  TABEL DATA                              │
│          │  No | Kolom1 | Kolom2 | Status | Aksi   │
│          │  1  | ...    | ...    | Badge  | ✏️ 🗑️  │
│          │  2  | ...    | ...    | Badge  | ✏️ 🗑️  │
│          ├─────────────────────────────────────────┤
│          │  [Pagination]                            │
└──────────┴─────────────────────────────────────────┘
```

### 6.6 Layout CMS — Form Tambah/Edit

```
┌──────────┬──────────────────────────────────────────┐
│          │  TOPBAR                                   │
│ SIDEBAR  ├──────────────────────────────────────────┤
│          │  [Judul]  Breadcrumb: CMS > Berita > Baru│
│          ├──────────────────────────────────────────┤
│          │  ┌─────────────────────────────────────┐ │
│          │  │  Label *                            │ │
│          │  │  [Input / Textarea / Select]        │ │
│          │  │                                     │ │
│          │  │  Label *                            │ │
│          │  │  [Rich Text Editor]                 │ │
│          │  │                                     │ │
│          │  │  [Upload Thumbnail]                 │ │
│          │  │                                     │ │
│          │  │         [Batal]  [Simpan]           │ │
│          │  └─────────────────────────────────────┘ │
└──────────┴──────────────────────────────────────────┘
```

---

## 7. Halaman Khusus

### 7.1 Halaman Login CMS

- Background: gradient `var(--primary)` → `var(--ocean)`
- Kartu login di tengah: white, rounded-lg, shadow-md
- Lebar kartu: 420px, padding 40px
- Logo dinas di atas form
- Judul: "Masuk ke CMS"
- Field: Email, Password (toggle show/hide), Remember Me
- Tombol Login: full-width primary blue
- Link lupa password di bawah

### 7.2 Halaman 404

- Ilustrasi ikan lucu dengan background laut
- Teks "404 — Halaman Tidak Ditemukan"
- Deskripsi singkat
- Tombol "Kembali ke Beranda"

### 7.3 Halaman Dokumen & Download

- Filter chip horizontal berdasarkan kategori dokumen (Laporan, Formulir, Panduan, Brosur, dll)
- Kartu list (bukan grid): ikon kategori, judul dokumen, deskripsi singkat, tombol unduh
- Background chip aktif: `var(--ocean)` putih

---

## 8. Ikon & Aset

- Ikon: **Bootstrap Icons** (bi-*) atau **Remix Icon** (ri-*)
- Ilustrasi: SVG custom atau dari unDraw.co (tema perikanan/kelautan)
- Foto placeholder: 800×450px, rasio 16:9 untuk berita; 400×400px bulat untuk profil
- Favicon: logo dinas, 32×32px

---

## 9. Animasi & Interaksi

```css
/* Transisi global */
* { transition: var(--transition); }

/* Kartu hover */
.card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }

/* Loading skeleton */
.skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; }

/* Fade in section */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}
.animate-fade-up { animation: fadeInUp 0.5s ease forwards; }
```

- Scroll reveal untuk section beranda (IntersectionObserver)
- Smooth scroll antar anchor
- Alert notifikasi CMS auto-dismiss 4 detik

---

## 10. Responsif

### Breakpoint Behavior

| Elemen | Desktop (≥992px) | Tablet (768–991px) | Mobile (<768px) |
|---|---|---|---|
| Navbar publik | Full menu horizontal | Full menu | Hamburger + offcanvas |
| Hero | 520px tinggi | 380px | 250px |
| Grid berita | 3 kolom | 2 kolom | 1 kolom |
| Grid layanan | 4 kolom | 2 kolom | 1 kolom |
| Galeri foto | 4 kolom | 3 kolom | 2 kolom |
| Sidebar CMS | 260px visible | Icon-only (72px) | Offcanvas (toggle) |
| Tabel CMS | Full kolom | Scroll horizontal | Scroll horizontal |
| Form CMS | 2 kolom field | 1 kolom | 1 kolom |

---

## 11. Panduan Penggunaan Bootstrap 5

- Gunakan utility class Bootstrap untuk margin, padding, flex, grid
- Semua CSS custom ditulis **inline** menggunakan tag `<style>` di dalam masing-masing file Blade
- Semua JavaScript ditulis **inline** menggunakan tag `<script>` di dalam masing-masing file Blade
- CSS variables (`:root`) dideklarasikan di `<style>` pada file layout utama (`layouts/public.blade.php` dan `layouts/cms.blade.php`) agar tersedia di semua child view
- Urutan CDN di `<head>` layout:

```html
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<!-- Swiper CSS (layout publik saja) -->
<link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
<!-- GLightbox CSS (layout publik saja) -->
<link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
<!-- Inline CSS -->
<style> ... semua CSS custom di sini ... </style>
```

- Urutan script sebelum `</body>`:

```html
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS (layout publik saja) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- GLightbox JS (layout publik saja) -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<!-- TinyMCE (layout CMS saja) -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
<!-- Inline JS -->
<script> ... semua JS custom di sini ... </script>
@stack('scripts')
```
