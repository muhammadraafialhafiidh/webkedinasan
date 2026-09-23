<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\OrganizationMember;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTextOverflowAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        // Seed basic settings
        Setting::set('nama_website', 'Dinas Perikanan');
        Setting::set('tagline', 'Kabupaten Banyumas');
        Setting::set('alamat', 'Jl. Jenderal Sudirman No. 123 Purwokerto');
        Setting::set('telepon', '(0281) 1234567');
        Setting::set('email', 'dinas@perikanan.go.id');
        Setting::set('jam_operasional', 'Senin - Jumat 08:00 - 15:30');
        Setting::set('sejarah', '<p>Sejarah dinas perikanan...</p>');
        Setting::set('visi', 'Visi dinas perikanan');
        Setting::set('misi', '<p>Misi dinas perikanan</p>');
        Setting::set('tupoksi', '<p>Tupoksi dinas perikanan</p>');
    }

    public function test_all_public_pages_return_200_and_contain_overflow_css_system()
    {
        $cat = NewsCategory::create(['name' => 'Kabar Dinas', 'slug' => 'kabar-dinas']);
        $news = News::create([
            'user_id' => $this->user->id,
            'news_category_id' => $cat->id,
            'title' => 'Berita Percobaan',
            'slug' => 'berita-percobaan',
            'content' => '<p>Konten berita percobaan</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $srvCat = ServiceCategory::create(['name' => 'Budidaya', 'slug' => 'budidaya', 'order' => 1]);
        $service = Service::create([
            'service_category_id' => $srvCat->id,
            'title' => 'Izin Budidaya Ikan',
            'slug' => 'izin-budidaya-ikan',
            'description' => 'Deskripsi izin budidaya',
            'requirements' => '<p>Syarat 1</p>',
            'procedure' => '<p>Prosedur 1</p>',
            'is_active' => true,
            'order' => 1,
        ]);

        $album = GalleryAlbum::create(['name' => 'Album Kegiatan 2026', 'slug' => 'album-kegiatan-2026']);
        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => 'Foto 1',
            'image' => 'photos/test.jpg',
        ]);

        GalleryVideo::create([
            'title' => 'Video Edukasi Budidaya',
            'source_type' => 'youtube',
            'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        Document::create([
            'title' => 'Laporan Tahunan 2025',
            'category' => 'Laporan',
            'file' => 'documents/report.pdf',
        ]);

        $publicRoutes = [
            '/',
            '/profil',
            '/berita',
            '/berita/' . $news->slug,
            '/layanan',
            '/layanan?kategori=' . $srvCat->slug,
            '/layanan/' . $service->slug,
            '/galeri/foto',
            '/galeri/foto/' . $album->id,
            '/galeri/video',
            '/dokumen',
            '/kontak',
        ];

        foreach ($publicRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);

            // Verify core shared CSS rules are present in the response
            $response->assertSee('.text-break-word', false);
            $response->assertSee('.min-w-0', false);
            $response->assertSee('overflow-wrap: anywhere', false);
            $response->assertSee('word-break: break-word', false);

            // Ensure no universal span rule exists
            $response->assertDontSee('body, p, span', false);
            $response->assertDontSee('p, span, h1', false);
        }
    }

    public function test_pages_handle_extreme_unbroken_text_without_errors()
    {
        $unbrokenLongText = str_repeat('supercalifragilisticexpialidocious_unbroken_long_string_without_spaces_', 5);
        $longUrl = 'https://perikanan.banyumaskab.go.id/portal/layanan/standar-operasional-prosedur/budidaya-ikan-air-tawar-dan-payau/dokumen-teknis-rekomendasi-perizinan-berusaha-berbasis-risiko-tahun-2026?token=' . str_repeat('a', 80);

        $cat = NewsCategory::create(['name' => 'Kategori-' . $unbrokenLongText, 'slug' => 'kategori-long']);
        $news = News::create([
            'user_id' => $this->user->id,
            'news_category_id' => $cat->id,
            'title' => 'Judul Berita ' . $unbrokenLongText,
            'slug' => 'judul-berita-long',
            'content' => '<p>' . $unbrokenLongText . '</p><p><a href="' . $longUrl . '">' . $longUrl . '</a></p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $srvCat = ServiceCategory::create(['name' => 'Bidang ' . $unbrokenLongText, 'slug' => 'bidang-long', 'order' => 1]);
        $service = Service::create([
            'service_category_id' => $srvCat->id,
            'title' => 'Layanan ' . $unbrokenLongText,
            'slug' => 'layanan-long',
            'description' => 'Deskripsi ' . $unbrokenLongText,
            'cost' => 'Biaya: ' . $unbrokenLongText,
            'requirements' => '<p>' . $unbrokenLongText . '</p>',
            'procedure' => '<p>' . $unbrokenLongText . '</p>',
            'is_active' => true,
            'order' => 1,
        ]);

        $album = GalleryAlbum::create(['name' => 'Album ' . $unbrokenLongText, 'slug' => 'album-long', 'description' => $unbrokenLongText]);
        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => 'Foto ' . $unbrokenLongText,
            'description' => $unbrokenLongText,
            'image' => 'photos/test.jpg',
        ]);

        GalleryVideo::create([
            'title' => 'Video ' . $unbrokenLongText,
            'description' => $unbrokenLongText,
            'source_type' => 'youtube',
            'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        Document::create([
            'title' => 'Dokumen ' . $unbrokenLongText,
            'description' => $unbrokenLongText,
            'category' => 'Laporan ' . $unbrokenLongText,
            'file' => 'documents/test.pdf',
        ]);

        OrganizationMember::create([
            'name' => 'Nama Pejabat ' . $unbrokenLongText,
            'position' => 'Jabatan Sangat Panjang ' . $unbrokenLongText,
            'order' => 1,
        ]);

        // Home
        $resHome = $this->get('/');
        $resHome->assertStatus(200);

        // Profil
        $resProfil = $this->get('/profil');
        $resProfil->assertStatus(200);

        // Berita list & detail
        $resNewsList = $this->get('/berita');
        $resNewsList->assertStatus(200);
        $resNewsShow = $this->get('/berita/' . $news->slug);
        $resNewsShow->assertStatus(200);

        // Layanan list & detail
        $resSrvList = $this->get('/layanan');
        $resSrvList->assertStatus(200);
        $resSrvShow = $this->get('/layanan/' . $service->slug);
        $resSrvShow->assertStatus(200);

        // Galeri foto & album
        $resGalPhoto = $this->get('/galeri/foto');
        $resGalPhoto->assertStatus(200);
        $resGalPhotoShow = $this->get('/galeri/foto/' . $album->id);
        $resGalPhotoShow->assertStatus(200);

        // Galeri video
        $resGalVideo = $this->get('/galeri/video');
        $resGalVideo->assertStatus(200);

        // Dokumen
        $resDoc = $this->get('/dokumen');
        $resDoc->assertStatus(200);

        // Kontak
        $resContact = $this->get('/kontak');
        $resContact->assertStatus(200);
    }

    public function test_rich_text_cms_styles_include_table_and_media_protection()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Table scroll rules
        $response->assertSee('.content-body table', false);
        $response->assertSee('.news-html-content table', false);
        $response->assertSee('.service-html-content table', false);
        $response->assertSee('overflow-x: auto', false);

        // Media rules
        $response->assertSee('.content-body img', false);
        $response->assertSee('max-width: 100% !important', false);

        // Code and pre rules
        $response->assertSee('.content-body pre', false);
        $response->assertSee('white-space: pre-wrap', false);
    }
}
