<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsBannerUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $superAdminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'email' => 'admin@silayan.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->superAdminUser = User::factory()->create([
            'email' => 'superadmin@silayan.test',
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        Storage::fake('public');
    }

    /**
     * TEST: Memastikan form Edit Banner pada Blade memiliki input name="is_active"
     */
    public function test_view_edit_banner_has_correct_is_active_input_name()
    {
        $banner = Banner::create([
            'title' => 'Banner Test Awal',
            'link_type' => 'none',
            'image_source' => 'custom',
            'image' => 'banners/dummy.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('cms.banner.index'));

        $response->assertOk();
        // Harus ada select dengan name="is_active" dan id="edit_is_active"
        $response->assertSee('id="edit_is_active"', false);
        $response->assertDontSee('name="edit_is_active"', false);
        $response->assertSee('<select name="is_active" id="edit_is_active"', false);
    }

    /**
     * KRITERIA 1: Edit banner aktif tanpa mengganti gambar
     */
    public function test_edit_banner_aktif_tanpa_mengganti_gambar()
    {
        $banner = Banner::create([
            'title' => 'Banner Aktif Lama',
            'link_type' => 'none',
            'image_source' => 'custom',
            'image' => 'banners/existing.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.banner.index'))
            ->put(route('cms.banner.update', $banner->id), [
                'title' => 'Banner Aktif Diperbarui',
                'link_type' => 'none',
                'image_source' => 'custom',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('cms.banner.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $banner->refresh();
        $this->assertEquals('Banner Aktif Diperbarui', $banner->title);
        $this->assertEquals('banners/existing.jpg', $banner->image);
        $this->assertTrue($banner->is_active);
    }

    /**
     * KRITERIA 2: Edit banner nonaktif tanpa mengganti gambar
     */
    public function test_edit_banner_nonaktif_tanpa_mengganti_gambar()
    {
        $banner = Banner::create([
            'title' => 'Banner Nonaktif Lama',
            'link_type' => 'none',
            'image_source' => 'custom',
            'image' => 'banners/existing_inactive.jpg',
            'order' => 2,
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.banner.index'))
            ->put(route('cms.banner.update', $banner->id), [
                'title' => 'Banner Nonaktif Diperbarui',
                'link_type' => 'none',
                'image_source' => 'custom',
                'is_active' => '0',
            ]);

        $response->assertRedirect(route('cms.banner.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $banner->refresh();
        $this->assertEquals('Banner Nonaktif Diperbarui', $banner->title);
        $this->assertEquals('banners/existing_inactive.jpg', $banner->image);
        $this->assertFalse($banner->is_active);
    }

    /**
     * KRITERIA 3: Edit banner sambil mengganti gambar
     */
    public function test_edit_banner_sambil_mengganti_gambar()
    {
        // Simpan dummy gambar lama di storage
        Storage::disk('public')->put('banners/old_banner.jpg', 'fake-image-content');

        $banner = Banner::create([
            'title' => 'Banner Gambar Lama',
            'link_type' => 'none',
            'image_source' => 'custom',
            'image' => 'banners/old_banner.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        $newImage = UploadedFile::fake()->image('new_banner.png', 1200, 400);

        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.banner.index'))
            ->put(route('cms.banner.update', $banner->id), [
                'title' => 'Banner Gambar Baru',
                'link_type' => 'none',
                'image_source' => 'custom',
                'image' => $newImage,
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('cms.banner.index'));
        $response->assertSessionHasNoErrors();

        $banner->refresh();
        $this->assertEquals('Banner Gambar Baru', $banner->title);
        $this->assertNotEquals('banners/old_banner.jpg', $banner->image);
        $this->assertTrue($banner->is_active);

        // Gambar baru tersimpan di disk
        Storage::disk('public')->assertExists($banner->image);
        // Gambar lama terhapus
        Storage::disk('public')->assertMissing('banners/old_banner.jpg');
    }

    /**
     * KRITERIA 4: Mengubah status aktif -> nonaktif
     */
    public function test_mengubah_status_aktif_ke_nonaktif()
    {
        $banner = Banner::create([
            'title' => 'Banner Mulanya Aktif',
            'link_type' => 'none',
            'image_source' => 'custom',
            'image' => 'banners/banner.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.banner.index'))
            ->put(route('cms.banner.update', $banner->id), [
                'title' => 'Banner Sekarang Nonaktif',
                'link_type' => 'none',
                'image_source' => 'custom',
                'is_active' => '0',
            ]);

        $response->assertRedirect(route('cms.banner.index'));
        $response->assertSessionHasNoErrors();

        $banner->refresh();
        $this->assertFalse($banner->is_active);
    }

    /**
     * KRITERIA 5: Mengubah status nonaktif -> aktif
     */
    public function test_mengubah_status_nonaktif_ke_aktif()
    {
        $banner = Banner::create([
            'title' => 'Banner Mulanya Nonaktif',
            'link_type' => 'none',
            'image_source' => 'custom',
            'image' => 'banners/banner.jpg',
            'order' => 1,
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.banner.index'))
            ->put(route('cms.banner.update', $banner->id), [
                'title' => 'Banner Sekarang Aktif',
                'link_type' => 'none',
                'image_source' => 'custom',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('cms.banner.index'));
        $response->assertSessionHasNoErrors();

        $banner->refresh();
        $this->assertTrue($banner->is_active);
    }

    /**
     * KRITERIA 6: Validasi tetap ketat dan menolak bila is_active tidak ada atau tidak boolean
     */
    public function test_validasi_menolak_jika_is_active_kosong()
    {
        $banner = Banner::create([
            'title' => 'Banner Test',
            'link_type' => 'none',
            'image_source' => 'custom',
            'image' => 'banners/banner.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.banner.index'))
            ->put(route('cms.banner.update', $banner->id), [
                'title' => 'Banner Tanpa Is Active',
                'link_type' => 'none',
                'image_source' => 'custom',
                // is_active sengaja tidak dikirim
            ]);

        $response->assertRedirect(route('cms.banner.index'));
        $response->assertSessionHasErrors(['is_active']);
    }

    /**
     * DEFENSE-IN-DEPTH: Fallback jika terkirim edit_is_active
     */
    public function test_fallback_kompatibilitas_jika_request_mengirim_edit_is_active()
    {
        $banner = Banner::create([
            'title' => 'Banner Fallback',
            'link_type' => 'none',
            'image_source' => 'custom',
            'image' => 'banners/banner.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.banner.index'))
            ->put(route('cms.banner.update', $banner->id), [
                'title' => 'Banner Fallback Berhasil',
                'link_type' => 'none',
                'image_source' => 'custom',
                'edit_is_active' => '0',
            ]);

        $response->assertRedirect(route('cms.banner.index'));
        $response->assertSessionHasNoErrors();

        $banner->refresh();
        $this->assertFalse($banner->is_active);
    }

    /**
     * AUTHORIZATION: Memastikan super_admin juga dapat melakukan update, sedangkan guest ditolak
     */
    public function test_authorization_role_super_admin_dan_guest()
    {
        $banner = Banner::create([
            'title' => 'Banner Auth Test',
            'link_type' => 'none',
            'image_source' => 'custom',
            'image' => 'banners/banner.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        // Guest ditolak (redirect ke login)
        $guestResponse = $this->put(route('cms.banner.update', $banner->id), [
            'title' => 'Update Oleh Guest',
            'link_type' => 'none',
            'image_source' => 'custom',
            'is_active' => '1',
        ]);
        $guestResponse->assertRedirect(route('cms.login'));

        // Super Admin berhasil
        $superAdminResponse = $this->actingAs($this->superAdminUser)
            ->from(route('cms.banner.index'))
            ->put(route('cms.banner.update', $banner->id), [
                'title' => 'Update Oleh Super Admin',
                'link_type' => 'none',
                'image_source' => 'custom',
                'is_active' => '1',
            ]);
        $superAdminResponse->assertRedirect(route('cms.banner.index'));
        $superAdminResponse->assertSessionHasNoErrors();
    }
}
