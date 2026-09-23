<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Contact;
use App\Models\User;
use App\Services\BrevoMailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CmsContactReplyTest extends TestCase
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
     * TEST 1 — Balasan kosong
     */
    public function test_skenario_1_balasan_kosong_validasi_dan_tidak_panggil_brevo()
    {
        Http::fake();

        $contact = Contact::create([
            'name' => 'Rina',
            'email' => 'rina@example.com',
            'phone' => '08222333444',
            'subject' => 'Pertanyaan',
            'message' => 'Isi pesan',
            'is_read' => true,
        ]);

        $initialActivityCount = ActivityLog::count();

        $response = $this->actingAs($this->adminUser)
            ->from(route('cms.pesan.show', $contact->id))
            ->post(route('cms.pesan.reply', $contact->id), [
                'reply' => '',
            ]);

        $response->assertRedirect(route('cms.pesan.show', $contact->id));
        $response->assertSessionHasErrors([
            'reply' => 'Balasan wajib diisi.',
        ]);

        // 1. Database tidak berubah
        $contact->refresh();
        $this->assertNull($contact->reply);
        $this->assertNull($contact->replied_at);

        // 2. Brevo API sama sekali tidak dipanggil
        Http::assertNothingSent();

        // 3. Tidak ada Activity Log balasan berhasil
        $this->assertEquals($initialActivityCount, ActivityLog::where('action', 'reply_message')->count());
    }

    /**
     * TEST 2 — Balasan normal dengan line break
     */
    public function test_skenario_2_balasan_normal_dengan_line_breaks()
    {
        Http::fake([
            'https://api.brevo.com/v3/smtp/email' => Http::response([
                'messageId' => '<20260819.test-normal-id@smtp-relay.mailin.fr>',
            ], 201),
        ]);

        $contact = Contact::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'phone' => '08123456789',
            'subject' => 'Pertanyaan Pembibitan Ikan Nila',
            'message' => "Pertanyaan baris 1\nBaris 2",
            'is_read' => true,
        ]);

        $replyText = "Terima kasih atas masukannya.\nKami akan menindaklanjutinya.";

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.pesan.reply', $contact->id), [
                'reply' => $replyText,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Tanggapan berhasil disimpan dan email berhasil dikirim ke pengirim.');

        // 1. Cek Database Reply
        $contact->refresh();
        $this->assertEquals($replyText, $contact->reply);
        $this->assertNotNull($contact->replied_at);

        // 2. Cek Activity Log
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'reply_message',
            'module' => 'Pesan Masuk',
        ]);

        // 3. Cek HTTP Request ke Brevo API & HTML line breaks
        Http::assertSent(function ($request) use ($contact) {
            $data = $request->data();
            return $request->url() === 'https://api.brevo.com/v3/smtp/email'
                && $request->hasHeader('api-key')
                && $data['to'][0]['email'] === $contact->email
                && str_contains($data['htmlContent'], "Terima kasih atas masukannya.<br />\nKami akan menindaklanjutinya.");
        });
    }

    /**
     * TEST 3 — HTML injection (<script>alert('XSS')</script>)
     */
    public function test_skenario_3_html_injection_script_xss_escaped()
    {
        $contact = Contact::create([
            'name' => '<script>alert("XSS-Name")</script>',
            'email' => 'xss@example.com',
            'subject' => '<script>alert("XSS-Subject")</script>',
            'message' => '<script>alert("XSS-Message")</script>',
            'is_read' => true,
        ]);

        $replyText = '<script>alert(\'XSS\')</script>';

        $html = view('emails.contact-reply', [
            'recipientName' => $contact->name,
            'senderName' => 'SILAYAN',
            'senderEmail' => 'dkpp.kabupaten.banyumas@gmail.com',
            'originalSubject' => $contact->subject,
            'originalMessage' => $contact->message,
            'originalDate' => '19 Agustus 2026 13:00',
            'replyText' => $replyText,
        ])->render();

        // Script tag tidak boleh ada di HTML mentah
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('</script>', $html);
        // Harus ter-escape
        $this->assertStringContainsString('&lt;script&gt;alert(&#039;XSS&#039;)&lt;/script&gt;', $html);
    }

    /**
     * TEST 4 — HTML injection (<img src=x onerror=alert('XSS')>)
     */
    public function test_skenario_4_html_injection_img_onerror_escaped()
    {
        $contact = Contact::create([
            'name' => 'Pengirim Uji',
            'email' => 'pengirim@example.com',
            'subject' => 'Tes Gambar',
            'message' => '<img src=x onerror=alert("XSS-1")>',
            'is_read' => true,
        ]);

        $replyText = '<img src=x onerror=alert(\'XSS\')>';

        $html = view('emails.contact-reply', [
            'recipientName' => $contact->name,
            'senderName' => 'SILAYAN',
            'senderEmail' => 'dkpp.kabupaten.banyumas@gmail.com',
            'originalSubject' => $contact->subject,
            'originalMessage' => $contact->message,
            'originalDate' => '19 Agustus 2026 13:00',
            'replyText' => $replyText,
        ])->render();

        // Tag img tidak boleh dieksekusi mentah
        $this->assertStringNotContainsString('<img src=x onerror=', $html);
        $this->assertStringContainsString('&lt;img src=x onerror=alert(&#039;XSS&#039;)&gt;', $html);
    }

    /**
     * TEST 5 — Karakter khusus (< > & " ')
     */
    public function test_skenario_5_karakter_khusus_escaped_dan_tidak_merusak_html()
    {
        $replyText = 'Karakter uji: < > & " \' dan simbol matematik: a < b & c > d "quotes" \'single\'';

        $html = view('emails.contact-reply', [
            'recipientName' => 'Budi & Partner',
            'senderName' => 'SILAYAN "Dinas"',
            'senderEmail' => 'dkpp.kabupaten.banyumas@gmail.com',
            'originalSubject' => 'Subjek <Uji> & "Test"',
            'originalMessage' => 'Pesan <1> & <2>',
            'originalDate' => '19 Agustus 2026 13:00',
            'replyText' => $replyText,
        ])->render();

        // Cek escaping aman
        $this->assertStringContainsString('&lt; &gt; &amp; &quot; &#039;', $html);
        $this->assertStringContainsString('Budi &amp; Partner', $html);
        $this->assertStringContainsString('Subjek &lt;Uji&gt; &amp; &quot;Test&quot;', $html);
    }

    /**
     * TEST 6 — API Key Security (tidak bocor ke response / log / activity log)
     */
    public function test_skenario_6_api_key_security_tidak_bocor()
    {
        $apiKey = 'api key dari brave';
        config(['services.brevo.api_key' => $apiKey]);

        Http::fake([
            'https://api.brevo.com/v3/smtp/email' => Http::response([
                'code' => 'invalid_parameter',
                'message' => 'Sender not verified',
            ], 400),
        ]);

        $contact = Contact::create([
            'name' => 'Siti Aminah',
            'email' => 'siti.aminah@example.com',
            'phone' => '08987654321',
            'subject' => 'Keluhan Pasokan Pangan',
            'message' => 'Mohon info stok beras.',
            'is_read' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.pesan.reply', $contact->id), [
                'reply' => 'Balasan pengujian keamanan API key.',
            ]);

        $response->assertRedirect();
        
        // 1. API key tidak ada di session/flash response
        $this->assertStringNotContainsString($apiKey, session('warning', ''));
        $this->assertStringNotContainsString($apiKey, session('success', ''));
        $this->assertStringNotContainsString($apiKey, session('error', ''));

        // 2. API key tidak ada di Activity Log
        $activity = ActivityLog::where('action', 'reply_message')->latest('id')->first();
        $this->assertNotNull($activity);
        $this->assertStringNotContainsString($apiKey, $activity->description);

        // 3. Database reply tetap tersimpan
        $contact->refresh();
        $this->assertEquals('Balasan pengujian keamanan API key.', $contact->reply);
    }

    /**
     * TEST 7 — Authorization (tidak dapat diakses oleh guest / user publik)
     */
    public function test_skenario_7_authorization_guest_dan_unauthorized_ditolak()
    {
        $contact = Contact::create([
            'name' => 'Target',
            'email' => 'target@example.com',
            'phone' => '0812345678',
            'subject' => 'Subjek',
            'message' => 'Pesan',
            'is_read' => false,
        ]);

        // Request oleh Guest (tanpa login CMS)
        $response = $this->post(route('cms.pesan.reply', $contact->id), [
            'reply' => 'Balasan ilegal tanpa auth',
        ]);

        // Harus diredirect ke login CMS oleh middleware cms.auth
        $response->assertRedirect(route('cms.login'));

        // Database tidak berubah
        $contact->refresh();
        $this->assertNull($contact->reply);
    }

    /**
     * TEST 8 — Normalisasi tag <p> dari editor menjadi teks bersih
     */
    public function test_skenario_8_html_tag_p_editor_dinormalisasi_menjadi_plain_text()
    {
        Http::fake([
            'https://api.brevo.com/v3/smtp/email' => Http::response(['messageId' => 'msg-8'], 201),
        ]);

        $contact = Contact::create([
            'name' => 'User Editor',
            'email' => 'editor@example.com',
            'subject' => 'Pertanyaan Editor',
            'message' => 'Pesan',
            'is_read' => true,
        ]);

        $inputHtml = '<p>oi tes 22222</p>';

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.pesan.reply', $contact->id), [
                'reply' => $inputHtml,
            ]);

        $response->assertRedirect();
        $contact->refresh();

        // 1. Clean reply accessor menghasilkan teks murni
        $this->assertEquals('oi tes 22222', $contact->clean_reply);

        // 2. Email yang dikirim via Brevo tidak menampilkan tag <p>
        Http::assertSent(function ($request) {
            $data = $request->data();
            return str_contains($data['htmlContent'], 'oi tes 22222')
                && !str_contains($data['htmlContent'], '&lt;p&gt;oi tes 22222&lt;/p&gt;')
                && !str_contains($data['htmlContent'], '<p>oi tes 22222</p>');
        });
    }

    /**
     * TEST 9 — Mempertahankan pemisah paragraf dari multiple <p>
     */
    public function test_skenario_9_html_multiple_paragraphs_mempertahankan_pemisah_paragraf()
    {
        Http::fake([
            'https://api.brevo.com/v3/smtp/email' => Http::response(['messageId' => 'msg-9'], 201),
        ]);

        $contact = Contact::create([
            'name' => 'User Paragraf',
            'email' => 'paragraf@example.com',
            'subject' => 'Tes Paragraf',
            'message' => 'Pesan',
            'is_read' => true,
        ]);

        $inputHtml = "<p>Paragraf pertama.</p>\n<p>Paragraf kedua.</p>";

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.pesan.reply', $contact->id), [
                'reply' => $inputHtml,
            ]);

        $response->assertRedirect();
        $contact->refresh();

        $this->assertEquals("Paragraf pertama.\n\nParagraf kedua.", $contact->clean_reply);

        Http::assertSent(function ($request) {
            $data = $request->data();
            return str_contains($data['htmlContent'], 'Paragraf pertama.')
                && str_contains($data['htmlContent'], 'Paragraf kedua.')
                && !str_contains($data['htmlContent'], '&lt;p&gt;');
        });
    }

    /**
     * TEST 10 — Tag format bold/italic dari editor dibersihkan
     */
    public function test_skenario_10_html_editor_formatting_strong_em_dibersihkan_menjadi_readable_text()
    {
        Http::fake([
            'https://api.brevo.com/v3/smtp/email' => Http::response(['messageId' => 'msg-10'], 201),
        ]);

        $contact = Contact::create([
            'name' => 'User Format',
            'email' => 'format@example.com',
            'subject' => 'Tes Format',
            'message' => 'Pesan',
            'is_read' => true,
        ]);

        $inputHtml = '<p><strong>Terima kasih</strong> atas <em>masukannya</em>.</p>';

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.pesan.reply', $contact->id), [
                'reply' => $inputHtml,
            ]);

        $response->assertRedirect();
        $contact->refresh();

        $this->assertEquals('Terima kasih atas masukannya.', $contact->clean_reply);
    }

    /**
     * TEST 11 — HTML kosong dari editor (<p>&nbsp;</p>) ditolak validasi
     */
    public function test_skenario_11_empty_html_tags_dari_editor_ditolak_validasi()
    {
        Http::fake();

        $contact = Contact::create([
            'name' => 'User Kosong',
            'email' => 'kosong@example.com',
            'subject' => 'Tes Kosong',
            'message' => 'Pesan',
            'is_read' => true,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('cms.pesan.reply', $contact->id), [
                'reply' => '<p>&nbsp;</p>',
            ]);

        $response->assertSessionHasErrors(['reply' => 'Balasan wajib diisi.']);
        Http::assertNothingSent();
    }
}
