<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExternalMediaTest extends TestCase
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
     * VIDEO TESTS (Upload Existing, Google Drive, Edit, Cleanup)
     * ========================================================================= */

    /**
     * Test 1 - Upload Video Existing tetap berjalan
     */
    public function test_video_upload_existing_tetap_berjalan()
    {
        $videoFile = UploadedFile::fake()->create('sample-video.mp4', 1024, 'video/mp4');

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video Budidaya Ikan Nila',
                'source_type' => 'file',
                'video_file' => $videoFile,
                'description' => 'Dokumentasi budidaya kolam terpal.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $video = GalleryVideo::where('title', 'Video Budidaya Ikan Nila')->first();
        $this->assertNotNull($video);
        $this->assertEquals('file', $video->source_type);
        $this->assertNotNull($video->video_file);
        $this->assertNull($video->url);

        // Verifikasi berkas tersimpan di Laravel Storage disk public
        Storage::disk('public')->assertExists($video->video_file);
        $this->assertEquals(asset('storage/' . $video->video_file), $video->video_file_url);
    }

    /**
     * Test 2 - URL Google Drive valid tersimpan sebagai reference dan TIDAK ada file di storage
     */
    public function test_video_google_drive_valid_tersimpan_sebagai_reference_dan_tidak_diunduh()
    {
        $gdriveUrl = 'https://drive.google.com/file/d/1Bxyz9876543210_abcdefGHIJKLMN/view?usp=sharing';

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video Panduan dari Google Drive',
                'source_type' => 'google_drive',
                'url' => $gdriveUrl,
                'description' => 'Video panduan di Google Drive.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $video = GalleryVideo::where('title', 'Video Panduan dari Google Drive')->first();
        $this->assertNotNull($video);
        $this->assertEquals('google_drive', $video->source_type);
        $this->assertEquals($gdriveUrl, $video->url);
        $this->assertNull($video->video_file);

        // Pastikan tidak ada file video yang tersimpan di disk public
        $this->assertEmpty(Storage::disk('public')->files('videos'));

        // Verifikasi URL embed Google Drive
        $expectedEmbed = 'https://drive.google.com/file/d/1Bxyz9876543210_abcdefGHIJKLMN/preview';
        $this->assertEquals($expectedEmbed, $video->google_drive_embed_url);
    }

    /**
     * Test 3 - Google Drive dengan URL tanpa query string (ID 1VPwoNB1TcYhRWxobUJw3WdhX0ArLwh8S)
     */
    public function test_video_google_drive_tanpa_query_string()
    {
        $gdriveUrl = 'https://drive.google.com/file/d/1VPwoNB1TcYhRWxobUJw3WdhX0ArLwh8S/view';

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video GDrive Bersih',
                'source_type' => 'google_drive',
                'url' => $gdriveUrl,
                'description' => 'Testing URL tanpa query string',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $video = GalleryVideo::where('title', 'Video GDrive Bersih')->first();
        $this->assertNotNull($video);
        $this->assertEquals('google_drive', $video->source_type);
        $this->assertEquals($gdriveUrl, $video->url);
        $this->assertEquals('https://drive.google.com/file/d/1VPwoNB1TcYhRWxobUJw3WdhX0ArLwh8S/preview', $video->google_drive_embed_url);
    }

    /**
     * Test 4 - Google Drive dengan query string (?usp=sharing)
     */
    public function test_video_google_drive_dengan_query_string()
    {
        $gdriveUrl = 'https://drive.google.com/file/d/1VPwoNB1TcYhRWxobUJw3WdhX0ArLwh8S/view?usp=sharing';

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video GDrive Sharing',
                'source_type' => 'google_drive',
                'url' => $gdriveUrl,
                'description' => 'Testing URL dengan query string',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $video = GalleryVideo::where('title', 'Video GDrive Sharing')->first();
        $this->assertNotNull($video);
        $this->assertEquals('google_drive', $video->source_type);
        $this->assertEquals($gdriveUrl, $video->url);
        $this->assertEquals('https://drive.google.com/file/d/1VPwoNB1TcYhRWxobUJw3WdhX0ArLwh8S/preview', $video->google_drive_embed_url);
    }

    /**
     * Test 5 - Google Drive URL kosong menghasilkan pesan error "URL video wajib diisi."
     */
    public function test_video_google_drive_url_kosong_menghasilkan_error_wajib_diisi()
    {
        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.galeri.video.index'))
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video GDrive Tanpa URL',
                'source_type' => 'google_drive',
                'url' => '',
            ]);

        $response->assertRedirect(route('cms.galeri.video.index'));
        $response->assertSessionHasErrors([
            'url' => 'URL video wajib diisi.',
        ]);
    }

    /**
     * Test 6 - URL Google Drive domain lain/tidak valid ditolak validasi
     */
    public function test_video_google_drive_invalid_ditolak_validasi()
    {
        // URL bukan domain Google Drive
        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.galeri.video.index'))
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video Invalid Domain',
                'source_type' => 'google_drive',
                'url' => 'https://dropbox.com/s/12345/video.mp4',
            ]);

        $response->assertRedirect(route('cms.galeri.video.index'));
        $response->assertSessionHasErrors(['url']);

        // URL Google Drive tanpa ID berkas yang valid
        $response2 = $this->actingAs($this->adminUser)
            ->from(route('cms.galeri.video.index'))
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video Tanpa ID',
                'source_type' => 'google_drive',
                'url' => 'https://drive.google.com/home',
            ]);

        $response2->assertRedirect(route('cms.galeri.video.index'));
        $response2->assertSessionHasErrors(['url']);

        $this->assertEquals(0, GalleryVideo::count());
    }

    /**
     * Test 7 - YouTube video store berjalan normal
     */
    public function test_video_youtube_tetap_berjalan_normal()
    {
        $ytUrl = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video YouTube',
                'source_type' => 'youtube',
                'url' => $ytUrl,
                'description' => 'Deskripsi YouTube.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $video = GalleryVideo::where('title', 'Video YouTube')->first();
        $this->assertNotNull($video);
        $this->assertEquals('youtube', $video->source_type);
        $this->assertEquals($ytUrl, $video->url);
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ', $video->youtube_embed_url);
    }

    /**
     * Test 8 - Instagram video store berjalan normal
     */
    public function test_video_instagram_tetap_berjalan_normal()
    {
        $igUrl = 'https://www.instagram.com/reel/C123456789/';

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.galeri.video.store'), [
                'title' => 'Video Instagram Reel',
                'source_type' => 'instagram',
                'url' => $igUrl,
                'description' => 'Deskripsi IG.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $video = GalleryVideo::where('title', 'Video Instagram Reel')->first();
        $this->assertNotNull($video);
        $this->assertEquals('instagram', $video->source_type);
        $this->assertEquals($igUrl, $video->url);
    }

    /**
     * Test 9 - Form CMS Video merender opsi Google Drive dan input URL dengan benar
     */
    public function test_cms_video_form_merender_input_google_drive_dengan_benar()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('cms.galeri.video.index'));

        $response->assertOk();
        $response->assertSee('value="google_drive"', false);
        $response->assertSee('id="add_url_gdrive"', false);
        $response->assertSee('name="url"', false);
        $response->assertSee('toggleSourceType', false);
    }

    /**
     * Test 4 - Edit video dari Upload (file) ke Google Drive membersihkan file lokal lama
     */
    public function test_edit_video_dari_upload_ke_google_drive_membersihkan_file_lokal()
    {
        $videoFile = UploadedFile::fake()->create('old-video.mp4', 1024, 'video/mp4');
        $storedPath = $videoFile->store('videos', 'public');

        $video = GalleryVideo::create([
            'title' => 'Video Awal',
            'source_type' => 'file',
            'video_file' => $storedPath,
            'url' => null,
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $newGdriveUrl = 'https://drive.google.com/file/d/2Cdef1234567890_ghijklMNOPQR/view';

        $response = $this->actingAs($this->adminUser)
            ->put(route('cms.galeri.video.update', $video->id), [
                'title' => 'Video Diperbarui ke GDrive',
                'source_type' => 'google_drive',
                'url' => $newGdriveUrl,
                'description' => 'Sekarang memakai Google Drive.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $video->refresh();
        $this->assertEquals('google_drive', $video->source_type);
        $this->assertEquals($newGdriveUrl, $video->url);
        $this->assertNull($video->video_file);

        // File lokal lama wajib sudah terhapus dari storage
        Storage::disk('public')->assertMissing($storedPath);
    }

    /**
     * Test 5 - Edit video dari Google Drive ke Upload (file) menyimpan file baru
     */
    public function test_edit_video_dari_google_drive_ke_upload_menyimpan_file_baru()
    {
        $video = GalleryVideo::create([
            'title' => 'Video GDrive Awal',
            'source_type' => 'google_drive',
            'url' => 'https://drive.google.com/file/d/1Bxyz9876543210_abcdefGHIJKLMN/view',
            'video_file' => null,
        ]);

        $newFile = UploadedFile::fake()->create('replacement-video.mp4', 2048, 'video/mp4');

        $response = $this->actingAs($this->adminUser)
            ->put(route('cms.galeri.video.update', $video->id), [
                'title' => 'Video Berganti ke Upload',
                'source_type' => 'file',
                'video_file' => $newFile,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $video->refresh();
        $this->assertEquals('file', $video->source_type);
        $this->assertNull($video->url);
        $this->assertNotNull($video->video_file);

        Storage::disk('public')->assertExists($video->video_file);
    }

    /**
     * Test 6 - Edit video tanpa mengganti media mempertahankan file/URL lama
     */
    public function test_edit_video_tanpa_mengganti_media_mempertahankan_data_lama()
    {
        $videoFile = UploadedFile::fake()->create('keep-video.mp4', 1024, 'video/mp4');
        $storedPath = $videoFile->store('videos', 'public');

        $video = GalleryVideo::create([
            'title' => 'Judul Sebelum Edit',
            'source_type' => 'file',
            'video_file' => $storedPath,
            'description' => 'Deskripsi lama.',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->put(route('cms.galeri.video.update', $video->id), [
                'title' => 'Judul Sesudah Edit',
                'source_type' => 'file',
                'description' => 'Deskripsi baru.',
            ]);

        $response->assertRedirect();
        $video->refresh();

        $this->assertEquals('Judul Sesudah Edit', $video->title);
        $this->assertEquals($storedPath, $video->video_file);
        Storage::disk('public')->assertExists($storedPath);
    }

    /* =========================================================================
     * FOTO TESTS (Upload Existing, Instagram, Edit, Cleanup)
     * ========================================================================= */

    /**
     * Test 7 - Upload Foto Existing tetap berjalan
     */
    public function test_foto_upload_existing_tetap_berjalan()
    {
        $album = GalleryAlbum::create([
            'name' => 'Album Budidaya',
            'description' => 'Dokumentasi budidaya.',
        ]);

        $file1 = UploadedFile::fake()->image('foto1.jpg', 600, 400);
        $file2 = UploadedFile::fake()->image('foto2.jpg', 600, 400);

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.galeri.foto.store', $album->id), [
                'title' => 'Dokumentasi Panen',
                'source_type' => 'upload',
                'photos' => [$file1, $file2],
                'description' => 'Foto dokumentasi panen raya.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $photos = GalleryPhoto::where('gallery_album_id', $album->id)->get();
        $this->assertCount(2, $photos);

        foreach ($photos as $photo) {
            $this->assertEquals('upload', $photo->source_type);
            $this->assertNotNull($photo->image);
            $this->assertNull($photo->external_url);
            Storage::disk('public')->assertExists($photo->image);
        }
    }

    /**
     * Test 8 - URL Instagram valid tersimpan sebagai reference dan TIDAK ada file di storage
     */
    public function test_foto_instagram_valid_tersimpan_sebagai_reference_dan_tidak_diunduh()
    {
        $album = GalleryAlbum::create([
            'name' => 'Album Pameran',
            'description' => 'Dokumentasi pameran.',
        ]);

        $igUrl = 'https://www.instagram.com/p/DB123456789/?utm_source=ig_web_copy_link';

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.galeri.foto.store', $album->id), [
                'title' => 'Postingan Instagram Pameran',
                'source_type' => 'instagram',
                'instagram_url' => $igUrl,
                'description' => 'Postingan pameran di Instagram resmi.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $photo = GalleryPhoto::where('title', 'Postingan Instagram Pameran')->first();
        $this->assertNotNull($photo);
        $this->assertEquals('instagram', $photo->source_type);
        $this->assertEquals($igUrl, $photo->external_url);
        $this->assertNull($photo->image);

        // Pastikan tidak ada file gambar yang tersimpan di disk public
        $this->assertEmpty(Storage::disk('public')->allFiles());

        // Verifikasi URL embed Instagram
        $expectedEmbed = 'https://www.instagram.com/p/DB123456789/embed';
        $this->assertEquals($expectedEmbed, $photo->instagram_embed_url);
    }

    /**
     * Test 9 - URL bukan Instagram ditolak validasi
     */
    public function test_foto_url_bukan_instagram_ditolak_validasi()
    {
        $album = GalleryAlbum::create(['name' => 'Album Test']);

        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.galeri.foto.index', $album->id))
            ->post(route('cms.galeri.foto.store', $album->id), [
                'title' => 'Invalid Link',
                'source_type' => 'instagram',
                'instagram_url' => 'https://facebook.com/photo/12345',
            ]);

        $response->assertRedirect(route('cms.galeri.foto.index', $album->id));
        $response->assertSessionHasErrors(['instagram_url']);

        $this->assertEquals(0, GalleryPhoto::count());
    }

    /**
     * Test 10 - Edit foto dari Upload ke Instagram membersihkan file fisik lama
     */
    public function test_edit_foto_dari_upload_ke_instagram_membersihkan_file_lokal()
    {
        $album = GalleryAlbum::create(['name' => 'Album Test']);

        $uploaded = UploadedFile::fake()->image('old-photo.jpg');
        $path = $uploaded->store("gallery/album-{$album->id}", 'public');

        $photo = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => 'Foto Awal',
            'source_type' => 'upload',
            'image' => $path,
            'external_url' => null,
        ]);

        Storage::disk('public')->assertExists($path);

        $igUrl = 'https://www.instagram.com/p/C9876543210/';

        $response = $this->actingAs($this->adminUser)
            ->put(route('cms.galeri.foto.update', $photo->id), [
                'title' => 'Foto Diubah ke Instagram',
                'source_type' => 'instagram',
                'instagram_url' => $igUrl,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $photo->refresh();
        $this->assertEquals('instagram', $photo->source_type);
        $this->assertEquals($igUrl, $photo->external_url);
        $this->assertNull($photo->image);

        // File lama di storage wajib terhapus
        Storage::disk('public')->assertMissing($path);
    }

    /**
     * Test 11 - Edit foto dari Instagram ke Upload menyimpan file baru
     */
    public function test_edit_foto_dari_instagram_ke_upload_menyimpan_file_baru()
    {
        $album = GalleryAlbum::create(['name' => 'Album Test']);

        $photo = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => 'Foto Instagram Awal',
            'source_type' => 'instagram',
            'external_url' => 'https://www.instagram.com/p/C123456789/',
            'image' => null,
        ]);

        $newImage = UploadedFile::fake()->image('new-upload.jpg');

        $response = $this->actingAs($this->adminUser)
            ->put(route('cms.galeri.foto.update', $photo->id), [
                'title' => 'Foto Diubah ke Upload',
                'source_type' => 'upload',
                'photo' => $newImage,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $photo->refresh();
        $this->assertEquals('upload', $photo->source_type);
        $this->assertNull($photo->external_url);
        $this->assertNotNull($photo->image);

        Storage::disk('public')->assertExists($photo->image);
    }

    /**
     * Test 12 - Edit foto tanpa mengganti media mempertahankan data dan file lama
     */
    public function test_edit_foto_tanpa_mengganti_media_mempertahankan_data_lama()
    {
        $album = GalleryAlbum::create(['name' => 'Album Test']);

        $uploaded = UploadedFile::fake()->image('keep-photo.jpg');
        $path = $uploaded->store("gallery/album-{$album->id}", 'public');

        $photo = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => 'Judul Sebelum Edit',
            'source_type' => 'upload',
            'image' => $path,
            'description' => 'Keterangan awal.',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->put(route('cms.galeri.foto.update', $photo->id), [
                'title' => 'Judul Setelah Edit',
                'source_type' => 'upload',
                'description' => 'Keterangan baru.',
            ]);

        $response->assertRedirect();
        $photo->refresh();

        $this->assertEquals('Judul Setelah Edit', $photo->title);
        $this->assertEquals('Keterangan baru.', $photo->description);
        $this->assertEquals($path, $photo->image);
        Storage::disk('public')->assertExists($path);
    }

    /* =========================================================================
     * PUBLIC RENDERING & SECURITY TESTS
     * ========================================================================= */

    /**
     * Test 13 - Halaman publik galeri video merender iframe embed Google Drive dan sanitize title
     */
    public function test_public_galeri_video_menampilkan_video_embed_google_drive()
    {
        GalleryVideo::create([
            'title' => 'Video Drive Publik <script>alert("xss")</script>',
            'source_type' => 'google_drive',
            'url' => 'https://drive.google.com/file/d/1Bxyz9876543210_abcdefGHIJKLMN/view',
            'description' => 'Deskripsi video publik.',
        ]);

        $response = $this->get(route('gallery.video'));

        $response->assertOk();
        // Memastikan iframe embed Google Drive ter-render
        $response->assertSee('https://drive.google.com/file/d/1Bxyz9876543210_abcdefGHIJKLMN/preview', false);
        // Memastikan tag XSS di-escape
        $response->assertDontSee('<script>alert("xss")</script>', false);
        $response->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false);
    }

    /**
     * Test 14 - Halaman publik galeri foto merender card Instagram 4:3 tanpa iframe pada grid utama
     */
    public function test_public_galeri_foto_menampilkan_card_instagram_4_3_tanpa_iframe()
    {
        $album = GalleryAlbum::create(['name' => 'Album Publik']);

        $igUrl = 'https://www.instagram.com/p/DB123456789/';

        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => 'Foto IG Publik',
            'source_type' => 'instagram',
            'external_url' => $igUrl,
            'description' => 'Deskripsi IG.',
        ]);

        $response = $this->get(route('gallery.photo.show', $album->id));

        $response->assertOk();
        // Memastikan TIDAK ADA iframe Instagram pada grid
        $response->assertDontSee('<iframe', false);
        // Memastikan card memiliki rasio 4/3
        $response->assertSee('aspect-ratio: 4/3', false);
        // Memastikan grid seragam col-lg-3 col-md-4 col-6
        $response->assertSee('col-lg-3 col-md-4 col-6', false);
        // Memastikan link/modal memiliki target external_url asli
        $response->assertSee($igUrl, false);
        $response->assertSee('Buka di Instagram');
        $response->assertSee('Postingan Instagram');
    }

    /**
     * Test 15 - Data Instagram lama tanpa judul dan deskripsi tetap aman dan tidak error
     */
    public function test_legacy_instagram_tanpa_judul_dan_deskripsi_tetap_aman()
    {
        $album = GalleryAlbum::create(['name' => 'Album Legacy']);

        $igUrl = 'https://www.instagram.com/p/C999999999/';

        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => null,
            'source_type' => 'instagram',
            'external_url' => $igUrl,
            'description' => null,
        ]);

        $response = $this->get(route('gallery.photo.show', $album->id));

        $response->assertOk();
        $response->assertDontSee('<iframe', false);
        $response->assertSee($igUrl, false);
        $response->assertSee('Buka di Instagram');
    }

    /**
     * Test 16 - Cover album aman jika album hanya berisi foto Instagram
     */
    public function test_album_cover_fallback_aman_jika_foto_pertama_instagram()
    {
        $album = GalleryAlbum::create(['name' => 'Album IG Only', 'cover' => null]);

        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'title' => 'Foto IG 1',
            'source_type' => 'instagram',
            'external_url' => 'https://www.instagram.com/p/DB123456789/',
        ]);

        $response = $this->get(route('gallery.photo'));
        $response->assertOk();
        $response->assertSee('Album IG Only');
        $response->assertSee('Dokumentasi Instagram');
    }
}
