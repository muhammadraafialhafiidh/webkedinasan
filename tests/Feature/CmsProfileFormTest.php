<?php

namespace Tests\Feature;

use App\Models\OrganizationMember;
use App\Models\ProfileContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsProfileFormTest extends TestCase
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
    }

    /**
     * TEST: Pastikan view profil tidak memiliki form bersarang (nested form)
     * yang menyebabkan tombol Hapus mengirim DELETE ke route cms/profil alih-alih cms/organisasi/{id}.
     */
    public function test_view_profile_does_not_contain_nested_forms()
    {
        OrganizationMember::create([
            'name' => 'Dr. H. Ahmad',
            'position' => 'Kepala Dinas',
            'photo' => 'organization/test.jpg',
            'order' => 0,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('cms.profil.index'));

        $response->assertOk();
        $html = $response->getContent();

        // Cari pola nested form: <form ...> yang muncul sebelum </form> penutup form sebelumnya
        preg_match_all('/<form\b[^>]*>|<\/form>/i', $html, $matches);
        $tags = $matches[0] ?? [];

        $depth = 0;
        $nestedFormFound = false;
        foreach ($tags as $tag) {
            if (stripos($tag, '<form') === 0) {
                $depth++;
                if ($depth > 1) {
                    $nestedFormFound = true;
                    break;
                }
            } else {
                $depth--;
            }
        }

        $this->assertFalse(
            $nestedFormFound,
            'Ditemukan form di dalam form (nested form) pada view cms/profile/index.blade.php!'
        );
    }

    /**
     * TEST: Pastikan penghapusan pejabat dinas pada route cms.organisasi.destroy berhasil
     */
    public function test_delete_organization_member_works()
    {
        $member = OrganizationMember::create([
            'name' => 'Pejabat Untuk Dihapus',
            'position' => 'Staff',
            'photo' => 'organization/staff.jpg',
            'order' => 0,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('cms.organisasi.destroy', $member->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('organization_members', [
            'id' => $member->id,
        ]);
    }

    /**
     * TEST: Pastikan update konten profil dinas pada route cms.profil.update berhasil
     */
    public function test_update_profile_contents_works()
    {
        $response = $this->actingAs($this->adminUser)
            ->put(route('cms.profil.update'), [
                'sejarah' => 'Sejarah baru dinas perikanan',
                'visi' => 'Visi baru',
                'misi' => 'Misi baru',
                'tupoksi' => 'Tupoksi baru',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('profile_contents', [
            'key' => 'sejarah',
            'value' => 'Sejarah baru dinas perikanan',
        ]);
    }
}
