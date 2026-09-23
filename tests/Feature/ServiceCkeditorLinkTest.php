<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\HtmlSanitizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceCkeditorLinkTest extends TestCase
{
    use RefreshDatabase;

    private ServiceCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = ServiceCategory::firstOrCreate(
            ['slug' => 'bidang-keamanan-pangan-test'],
            ['name' => 'Bidang Keamanan Pangan Test', 'order' => 99]
        );
    }

    /**
     * Test 1 — Link teks CKEditor (misal: "Klik di sini" dengan URL https://www.youtube.com/)
     * Expected: Merender tag <a> aktif dengan href yang benar, target="_blank", dan rel="noopener noreferrer".
     */
    public function test_1_link_teks_ckeditor_menjadi_hyperlink_aktif()
    {
        $htmlInput = '<p>Untuk informasi lengkap, silakan <a href="https://www.youtube.com/">Klik di sini</a> untuk panduan.</p>';

        $service = Service::create([
            'service_category_id' => $this->category->id,
            'title' => 'Layanan Uji Coba Link Teks ' . uniqid(),
            'slug' => 'layanan-link-teks-' . uniqid(),
            'description' => $htmlInput,
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->get('/layanan/' . $service->slug);
        $response->assertStatus(200);

        // Pastikan tidak di-escape menjadi &lt;a atau teks mentah tanpa link
        $response->assertSee('<a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer">Klik di sini</a>', false);
        $response->assertSee('Untuk informasi lengkap, silakan', false);
    }

    /**
     * Test 2 — URL HTTPS yang sangat panjang
     * Expected: Tag <a> tetap utuh dengan URL lengkap, kelas pembungkus css memiliki overflow-wrap & word-break.
     */
    public function test_2_url_panjang_tetap_dapat_diklik_dan_terbungkus_rapi()
    {
        $longUrl = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&feature=share&utm_source=very_long_tracking_parameter_value_to_verify_word_break_and_overflow_protection_in_service_description';
        $htmlInput = '<p>Akses tautan video layanan: <a href="' . $longUrl . '">' . $longUrl . '</a></p>';

        $service = Service::create([
            'service_category_id' => $this->category->id,
            'title' => 'Layanan Uji URL Panjang ' . uniqid(),
            'slug' => 'layanan-url-panjang-' . uniqid(),
            'description' => $htmlInput,
            'is_active' => true,
            'order' => 2,
        ]);

        $response = $this->get('/layanan/' . $service->slug);
        $response->assertStatus(200);

        // Pastikan link utuh dan dapat diklik
        $response->assertSee('href="' . htmlspecialchars($longUrl, ENT_QUOTES, 'UTF-8') . '"', false);
        $response->assertSee('target="_blank"', false);
        $response->assertSee('rel="noopener noreferrer"', false);

        // Pastikan ada container service-html-content yang memiliki aturan overflow-wrap
        $response->assertSee('class="service-description service-html-content', false);
    }

    /**
     * Test 3 — Format CKEditor tetap terjaga (bold, italic, list, paragraph, heading)
     */
    public function test_3_format_ckeditor_bold_italic_list_paragraph_heading_terjaga()
    {
        $htmlInput = '<h2>Petunjuk Pendaftaran</h2>'
            . '<p>Layanan ini <strong>wajib</strong> diikuti oleh seluruh pelaku usaha yang <em>memenuhi syarat</em>.</p>'
            . '<ul><li>Syarat berkas A</li><li>Syarat berkas B</li></ul>'
            . '<p>Kunjungi <a href="https://www.youtube.com/">Portal Resmi</a> sekarang.</p>';

        $service = Service::create([
            'service_category_id' => $this->category->id,
            'title' => 'Layanan Uji Format CKEditor ' . uniqid(),
            'slug' => 'layanan-format-ckeditor-' . uniqid(),
            'description' => $htmlInput,
            'is_active' => true,
            'order' => 3,
        ]);

        $response = $this->get('/layanan/' . $service->slug);
        $response->assertStatus(200);

        $response->assertSee('<h2>Petunjuk Pendaftaran</h2>', false);
        $response->assertSee('<strong>wajib</strong>', false);
        $response->assertSee('<em>memenuhi syarat</em>', false);
        $response->assertSee('<ul><li>Syarat berkas A</li><li>Syarat berkas B</li></ul>', false);
        $response->assertSee('<a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer">Portal Resmi</a>', false);
    }

    /**
     * Test 4 — Security: URL berbahaya (javascript:, data:, vbscript:) dan script tag dinetralisir
     */
    public function test_4_security_url_berbahaya_dan_script_dibersihkan()
    {
        $maliciousInput = '<p>Coba klik <a href="javascript:alert(1)">Link Jahat</a> atau <a href="data:text/html;base64,PHNjcmlwdD4=">Data Jahat</a>.</p>'
            . '<script>alert("hacked");</script>'
            . '<div onclick="alert(2)">Klik Saya</div>';

        $service = Service::create([
            'service_category_id' => $this->category->id,
            'title' => 'Layanan Uji Keamanan XSS ' . uniqid(),
            'slug' => 'layanan-keamanan-xss-' . uniqid(),
            'description' => $maliciousInput,
            'is_active' => true,
            'order' => 4,
        ]);

        $response = $this->get('/layanan/' . $service->slug);
        $response->assertStatus(200);

        // Pastikan javascript:, data:, <script>, dan onclick tidak ada di response output
        $response->assertDontSee('javascript:alert(1)', false);
        $response->assertDontSee('data:text/html', false);
        $response->assertDontSee('<script>alert("hacked");</script>', false);
        $response->assertDontSee('onclick="alert(2)"', false);

        // Teks aman di dalam link tetap tampil tanpa tag link berbahaya
        $response->assertSee('Link Jahat', false);
        $response->assertSee('Data Jahat', false);
    }

    /**
     * Test 5 — Existing service (layanan lama tanpa link atau teks biasa tanpa HTML)
     * Expected: Tampil normal tanpa error, dibungkus dalam paragraf yang rapi.
     */
    public function test_5_existing_service_tanpa_link_tampil_normal_tanpa_error()
    {
        $plainTextInput = 'Layanan penerbitan rekomendasi sertifikasi kelayakan pengolahan bagi Unit Pengolah Ikan (UPI).';

        $service = Service::create([
            'service_category_id' => $this->category->id,
            'title' => 'Layanan Lama Teks Biasa ' . uniqid(),
            'slug' => 'layanan-lama-teks-biasa-' . uniqid(),
            'description' => $plainTextInput,
            'is_active' => true,
            'order' => 5,
        ]);

        $response = $this->get('/layanan/' . $service->slug);
        $response->assertStatus(200);

        $response->assertSee($plainTextInput, false);
        $response->assertSee('<p>' . $plainTextInput . '</p>', false);
    }

    /**
     * Kondisi B — User hanya mengetik URL biasa (plain text tanpa tag <a>)
     * Expected: URL tersebut TIDAK diubah otomatis menjadi link <a>.
     */
    public function test_kondisi_b_url_biasa_tanpa_tag_a_tidak_otomatis_diubah_menjadi_link()
    {
        $rawUrlInput = '<p>Silakan pelajari lebih lanjut di https://www.youtube.com/ secara mandiri.</p>';

        $service = Service::create([
            'service_category_id' => $this->category->id,
            'title' => 'Layanan Uji Kondisi B ' . uniqid(),
            'slug' => 'layanan-kondisi-b-' . uniqid(),
            'description' => $rawUrlInput,
            'is_active' => true,
            'order' => 6,
        ]);

        $response = $this->get('/layanan/' . $service->slug);
        $response->assertStatus(200);

        // URL teks biasa harus tetap sebagai teks dalam paragraf, BUKAN <a href="https://www.youtube.com/">
        $response->assertSee('<p>Silakan pelajari lebih lanjut di https://www.youtube.com/ secara mandiri.</p>', false);
        $response->assertDontSee('<a href="https://www.youtube.com/">https://www.youtube.com/</a>', false);
    }
}
