<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\User;
use App\Services\MediaSourceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstagramAuditFixTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'email' => 'admin@silayan.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        Storage::fake('public');
    }

    /* =========================================================================
     * 1. INSTAGRAM VIDEO VALID & INVALID TESTS
     * ========================================================================= */

    public function test_instagram_video_valid_urls_accepted()
    {
        $validUrls = [
            'https://www.instagram.com/p/VALID_P_123/',
            'https://www.instagram.com/reel/VALID_REEL_456/',
            'https://www.instagram.com/reels/VALID_REELS_789/',
            'https://m.instagram.com/p/VALID_MOB_999/',
            'https://instagram.com/tv/VALID_TV_111/?utm_source=ig',
        ];

        foreach ($validUrls as $index => $url) {
            $response = $this->actingAs($this->adminUser)
                ->post(route('cms.galeri.video.store'), [
                    'title' => "Video Valid {$index}",
                    'source_type' => 'instagram',
                    'url' => $url,
                    'description' => "Deskripsi video {$index}",
                ]);

            $response->assertRedirect();
            $response->assertSessionHas('success');
        }

        $this->assertEquals(count($validUrls), GalleryVideo::where('source_type', 'instagram')->count());
    }

    public function test_instagram_video_invalid_urls_rejected()
    {
        $invalidUrls = [
            'https://example.com',
            'https://evil-instagram.com/test',
            'https://instagram.com.evil.com/test',
            'https://example.com/instagram.com/test',
            'https://www.instagram.com/username_only',
            'javascript:alert(1)',
        ];

        foreach ($invalidUrls as $url) {
            $response = $this->actingAs($this->adminUser)
                ->from(route('cms.galeri.video.index'))
                ->post(route('cms.galeri.video.store'), [
                    'title' => 'Video Invalid Test',
                    'source_type' => 'instagram',
                    'url' => $url,
                ]);

            $response->assertRedirect(route('cms.galeri.video.index'));
            $response->assertSessionHasErrors(['url']);
        }

        $this->assertEquals(0, GalleryVideo::count());
    }

    public function test_instagram_video_defense_in_depth_model_accessor_returns_null_for_malicious_url()
    {
        // Simulasi jika data jahat pernah lolos ke database secara langsung
        $video = GalleryVideo::create([
            'title' => 'Injected Video',
            'source_type' => 'instagram',
            'url' => 'https://evil.com/phishing/login',
        ]);

        // Model accessor HARUS mengembalikan null, TIDAK BOLEH rtrim() . '/embed'
        $this->assertNull($video->instagram_embed_url);

        // Halaman publik galeri video TIDAK BOLEH merender iframe untuk URL tersebut
        $response = $this->get(route('gallery.video'));
        $response->assertOk();
        $response->assertDontSee('https://evil.com/phishing/login/embed', false);
        $response->assertSee('Postingan / Reel Instagram');
    }

    public function test_instagram_video_duplicate_url_rejected()
    {
        $igUrl = 'https://www.instagram.com/reel/C_duplicate_video/';

        // Tambah pertama -> Berhasil
        $response1 = $this->actingAs($this->adminUser)
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video Pertama',
                'source_type' => 'instagram',
                'url' => $igUrl,
            ]);
        $response1->assertRedirect();
        $response1->assertSessionHas('success');

        // Tambah kedua dengan URL yang sama -> Ditolak
        $response2 = $this->actingAs($this->adminUser)
            ->from(route('cms.galeri.video.index'))
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video Kedua Duplikat',
                'source_type' => 'instagram',
                'url' => $igUrl,
            ]);
        $response2->assertRedirect(route('cms.galeri.video.index'));
        $response2->assertSessionHasErrors(['url']);
        $this->assertEquals(1, GalleryVideo::where('url', $igUrl)->count());
    }

    /* =========================================================================
     * 2. INSTAGRAM PHOTO VALIDATION & DUPLICATE TESTS
     * ========================================================================= */

    public function test_instagram_photo_duplicate_in_same_album_rejected()
    {
        $album = GalleryAlbum::create(['name' => 'Album Uji Duplikat']);
        $igUrl = 'https://www.instagram.com/p/C_duplicate_photo/';

        // Input 1 -> Berhasil
        $response1 = $this->actingAs($this->adminUser)
            ->post(route('cms.galeri.foto.store', $album->id), [
                'title' => 'Foto IG 1',
                'source_type' => 'instagram',
                'instagram_url' => $igUrl,
            ]);
        $response1->assertRedirect();
        $response1->assertSessionHas('success');

        // Input 2 -> Ditolak
        $response2 = $this->actingAs($this->adminUser)
            ->from(route('cms.galeri.foto.index', $album->id))
            ->post(route('cms.galeri.foto.store', $album->id), [
                'title' => 'Foto IG Duplikat',
                'source_type' => 'instagram',
                'instagram_url' => $igUrl,
            ]);
        $response2->assertRedirect(route('cms.galeri.foto.index', $album->id));
        $response2->assertSessionHasErrors(['instagram_url']);
        $this->assertEquals(1, GalleryPhoto::where('gallery_album_id', $album->id)->count());
    }

    /* =========================================================================
     * 3. HOMEPAGE RENDERING COMBINATIONS TESTS
     * ========================================================================= */

    public function test_homepage_renders_instagram_and_local_photos_without_broken_images()
    {
        $album = GalleryAlbum::create(['name' => 'Album Dinas Banyumas']);

        // 1. Buat foto upload lokal
        $file = UploadedFile::fake()->image('local.jpg');
        $storedPath = $file->store("gallery/album-{$album->id}", 'public');

        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => 'Foto Lokal Asli',
            'source_type' => 'upload',
            'image' => $storedPath,
            'external_url' => null,
        ]);

        // 2. Buat foto Instagram
        $igUrl = 'https://www.instagram.com/p/C_test_homepage_123/';
        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => 'Dokumentasi Instagram Resmi',
            'source_type' => 'instagram',
            'image' => null,
            'external_url' => $igUrl,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();

        // Foto lokal harus punya link glightbox dan asset storage
        $response->assertSee('storage/' . $storedPath, false);

        // Foto Instagram TIDAK BOLEH memanggil asset('storage/') kosong
        $response->assertDontSee('storage//', false);

        // Foto Instagram harus menampilkan badge dan link asli
        $response->assertSee('Postingan Instagram');
        $response->assertSee($igUrl, false);
        $response->assertSee('Dokumentasi Instagram Resmi');
    }

    public function test_homepage_when_empty_photos()
    {
        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee('Belum ada galeri foto.');
    }

    /* =========================================================================
     * 4. PUBLIC VIDEO RENDERING ALL SOURCES (REGRESSION TEST)
     * ========================================================================= */

    public function test_public_video_page_supports_all_four_sources_cleanly()
    {
        // 1. YouTube
        GalleryVideo::create([
            'title' => 'Video YouTube Test',
            'source_type' => 'youtube',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        // 2. Google Drive
        GalleryVideo::create([
            'title' => 'Video GDrive Test',
            'source_type' => 'google_drive',
            'url' => 'https://drive.google.com/file/d/1Bxyz9876543210_abcdefGHIJKLMN/view',
        ]);

        // 3. Local File
        GalleryVideo::create([
            'title' => 'Video Lokal Test',
            'source_type' => 'file',
            'video_file' => 'videos/sample.mp4',
        ]);

        // 4. Instagram
        $igUrl = 'https://www.instagram.com/reel/C_reel_test_999/';
        GalleryVideo::create([
            'title' => 'Video Instagram Test',
            'source_type' => 'instagram',
            'url' => $igUrl,
        ]);

        $response = $this->get(route('gallery.video'));

        $response->assertOk();

        // Periksa YouTube
        $response->assertSee('https://www.youtube.com/embed/dQw4w9WgXcQ', false);
        // Periksa GDrive
        $response->assertSee('https://drive.google.com/file/d/1Bxyz9876543210_abcdefGHIJKLMN/preview', false);
        // Periksa Video Lokal
        $response->assertSee('videos/sample.mp4', false);
        // Periksa Instagram Embed dan tombol Buka di Instagram
        $response->assertSee('https://www.instagram.com/p/C_reel_test_999/embed', false);
        $response->assertSee('loading="lazy"', false);
        $response->assertSee('Buka di Instagram');
        $response->assertSee($igUrl, false);
    }

    /* =========================================================================
     * 5. JAVASCRIPT & SPECIAL CHARACTERS ESCAPING TESTS
     * ========================================================================= */

    public function test_special_characters_quotes_and_newlines_do_not_break_views()
    {
        $album = GalleryAlbum::create(['name' => 'Album Karakter Khusus']);

        $trickyTitle = 'Kegiatan "Panen" & \'Budidaya\' Ikan \\ Special';
        $trickyDesc = "Baris 1: Informasi resmi.\nBaris 2: <script>alert('xss')</script>\nBaris 3: Selesai.";
        $igUrl = 'https://www.instagram.com/p/C_special_chars_123/?utm_source=ig_web_copy_link';

        $photo = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => $trickyTitle,
            'source_type' => 'instagram',
            'external_url' => $igUrl,
            'description' => $trickyDesc,
        ]);

        // 1. Cek halaman public photo-show
        $responsePublic = $this->get(route('gallery.photo.show', $album->id));
        $responsePublic->assertOk();
        // Memastikan tidak ada pola inline onclick="openInstagramModal('...')" mentah yang pecah
        $responsePublic->assertDontSee("onclick=\"openInstagramModal('", false);
        $responsePublic->assertSee('js-ig-photo-trigger', false);
        $responsePublic->assertSee('data-title=', false);
        // Memastikan tag script di deskripsi dibersihkan/escaped
        $responsePublic->assertDontSee("<script>alert('xss')</script>", false);

        // 2. Cek halaman CMS photo
        $responseCmsPhoto = $this->actingAs($this->adminUser)
            ->get(route('cms.galeri.foto.index', $album->id));
        $responseCmsPhoto->assertOk();
        $responseCmsPhoto->assertDontSee("onclick=\"editPhoto(", false);
        $responseCmsPhoto->assertSee('js-btn-edit-photo', false);

        // 3. Cek halaman CMS video
        GalleryVideo::create([
            'title' => $trickyTitle,
            'source_type' => 'instagram',
            'url' => $igUrl,
            'description' => $trickyDesc,
        ]);

        $responseCmsVideo = $this->actingAs($this->adminUser)
            ->get(route('cms.galeri.video.index'));
        $responseCmsVideo->assertOk();
        $responseCmsVideo->assertDontSee("onclick=\"editVideo(", false);
        $responseCmsVideo->assertSee('js-btn-edit-video', false);
    }
}
