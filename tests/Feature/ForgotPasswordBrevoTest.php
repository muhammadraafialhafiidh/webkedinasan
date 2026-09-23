<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\BrevoMailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class ForgotPasswordBrevoTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@silayan.test',
            'password' => Hash::make('passwordLama123'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    /**
     * TEST 1 — Forgot Password dengan email valid
     */
    public function test_skenario_1_forgot_password_email_valid_kirim_via_brevo()
    {
        Http::fake([
            'https://api.brevo.com/v3/smtp/email' => Http::response([
                'messageId' => '<20260824.reset-pass-msg-1@smtp-relay.brevo.com>',
            ], 201),
        ]);

        $initialActivityCount = ActivityLog::where('action', 'forgot_password')->count();

        $response = $this->from(route('cms.lupa-password'))
            ->post(route('cms.lupa-password.post'), [
                'email' => $this->user->email,
            ]);

        $response->assertRedirect(route('cms.lupa-password'));
        $response->assertSessionHas('success', 'Link reset password telah berhasil dikirim ke email Anda.');

        // 1. Reset token dibuat di database
        $resetRecord = DB::table('password_reset_tokens')->where('email', $this->user->email)->first();
        $this->assertNotNull($resetRecord);
        $this->assertNotNull($resetRecord->token);

        // 2. Activity Log tercatat
        $this->assertEquals($initialActivityCount + 1, ActivityLog::where('action', 'forgot_password')->count());

        // 3. Brevo API dipanggil dengan payload yang valid
        Http::assertSent(function ($request) {
            $data = $request->data();
            $email = $this->user->email;
            $name = $this->user->name;

            return $request->url() === 'https://api.brevo.com/v3/smtp/email'
                && $request->hasHeader('api-key')
                && $data['to'][0]['email'] === $email
                && $data['to'][0]['name'] === $name
                && $data['subject'] === 'Reset Password Akun — SILAYAN'
                && str_contains($data['htmlContent'], 'SILAYAN')
                && str_contains($data['htmlContent'], 'Reset Password')
                && str_contains($data['htmlContent'], '/cms/reset-password/')
                && str_contains($data['htmlContent'], '15 menit');
        });
    }

    /**
     * TEST 2 — Klik link reset password dengan token valid
     */
    public function test_skenario_2_klik_reset_password_token_valid()
    {
        $plainToken = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $this->user->email,
            'token' => Hash::make($plainToken),
            'created_at' => now(),
        ]);

        $response = $this->get(route('cms.reset-password.form', [
            'token' => $plainToken,
            'email' => $this->user->email,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
        $response->assertViewHas('token', $plainToken);
        $response->assertViewHas('email', $this->user->email);
    }

    /**
     * TEST 3 — Masukkan password baru dan verifikasi login
     */
    public function test_skenario_3_submit_password_baru_berhasil_dan_bisa_login()
    {
        $plainToken = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $this->user->email,
            'token' => Hash::make($plainToken),
            'created_at' => now(),
        ]);

        $newPassword = 'PasswordBaru2026!';

        $response = $this->post(route('cms.reset-password.post'), [
            'token' => $plainToken,
            'email' => $this->user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertRedirect(route('cms.login'));
        $response->assertSessionHas('success', 'Password Anda berhasil diperbarui. Silakan login kembali.');

        // 1. Password di DB sudah terupdate dengan hashing
        $this->user->refresh();
        $this->assertTrue(Hash::check($newPassword, $this->user->password));
        $this->assertFalse(Hash::check('passwordLama123', $this->user->password));

        // 2. Token di tabel password_reset_tokens sudah dihapus
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $this->user->email,
        ]);

        // 3. Activity Log reset password tercatat
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'reset_password',
            'module' => 'Autentikasi',
        ]);

        // 4. User dapat login menggunakan password baru
        $loginResponse = $this->post(route('cms.login.post'), [
            'email' => $this->user->email,
            'password' => $newPassword,
        ]);
        $loginResponse->assertRedirect(route('cms.dashboard'));
        $this->assertAuthenticatedAs($this->user);
    }

    /**
     * TEST 4 — Token expired (>15 menit) dan token invalid ditolak
     */
    public function test_skenario_4_token_expired_dan_token_invalid_ditolak()
    {
        $plainToken = Str::random(60);

        // Token sudah berumur 16 menit (expired)
        DB::table('password_reset_tokens')->insert([
            'email' => $this->user->email,
            'token' => Hash::make($plainToken),
            'created_at' => now()->subMinutes(16),
        ]);

        // 1. Akses form dengan token expired diarahkan ke expired page
        $formResponse = $this->get(route('cms.reset-password.form', [
            'token' => $plainToken,
            'email' => $this->user->email,
        ]));
        $formResponse->assertRedirect(route('cms.reset-password.expired'));

        // 2. Submit reset password dengan token expired ditolak
        $submitResponse = $this->post(route('cms.reset-password.post'), [
            'token' => $plainToken,
            'email' => $this->user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);
        $submitResponse->assertRedirect(route('cms.reset-password.expired'));

        // 3. Akses form dengan token yang salah (invalid)
        $invalidFormResponse = $this->get(route('cms.reset-password.form', [
            'token' => 'invalid-token-string',
            'email' => $this->user->email,
        ]));
        $invalidFormResponse->assertRedirect(route('cms.reset-password.expired'));
    }

    /**
     * TEST 5 — Email tidak terdaftar: validasi gagal dan Brevo TIDAK dipanggil
     */
    public function test_skenario_5_email_tidak_terdaftar_validasi_dan_tidak_panggil_brevo()
    {
        Http::fake();

        $response = $this->from(route('cms.lupa-password'))
            ->post(route('cms.lupa-password.post'), [
                'email' => 'tidak.terdaftar@silayan.test',
            ]);

        $response->assertRedirect(route('cms.lupa-password'));
        $response->assertSessionHasErrors([
            'email' => 'Email tidak ditemukan.',
        ]);

        // 1. Tidak ada token yang dibuat di database
        $this->assertEquals(0, DB::table('password_reset_tokens')->count());

        // 2. Brevo API sama sekali tidak dipanggil
        Http::assertNothingSent();

        // 3. Tidak ada Activity Log forgot_password
        $this->assertEquals(0, ActivityLog::where('action', 'forgot_password')->count());
    }

    /**
     * TEST 6 — Penanganan error jika Brevo API gagal / timeout
     */
    public function test_skenario_6_brevo_api_gagal_ditangani_dengan_aman()
    {
        // 6a: Brevo mengembalikan respon HTTP 500 error
        Http::fake([
            'https://api.brevo.com/v3/smtp/email' => Http::response([
                'code' => 'internal_error',
                'message' => 'Internal server error from Brevo',
            ], 500),
        ]);

        $response = $this->from(route('cms.lupa-password'))
            ->post(route('cms.lupa-password.post'), [
                'email' => $this->user->email,
            ]);

        $response->assertRedirect(route('cms.lupa-password'));
        $response->assertSessionHas('warning');

        // Pastikan tidak ada raw API key yang bocor di session
        $this->assertFalse(str_contains((string) session('warning'), 'xkeysib-'));
    }

    public function test_skenario_6b_brevo_api_key_kosong_ditangani_dengan_aman()
    {
        config(['services.brevo.api_key' => null]);

        $response = $this->from(route('cms.lupa-password'))
            ->post(route('cms.lupa-password.post'), [
                'email' => $this->user->email,
            ]);

        $response->assertRedirect(route('cms.lupa-password'));
        $response->assertSessionHas('warning');
    }

    /**
     * TEST 7 — Keamanan: Template email tidak mengekspos password atau raw token
     */
    public function test_skenario_7_template_email_keamanan_data()
    {
        $resetUrl = 'http://localhost/cms/reset-password/sample-token?email=' . urlencode($this->user->email);

        $view = view('emails.reset-password', [
            'user' => $this->user,
            'recipientName' => $this->user->name,
            'resetUrl' => $resetUrl,
            'expireMinutes' => 15,
            'senderName' => 'SILAYAN',
            'senderEmail' => 'dkpp@banyumaskab.go.id',
        ])->render();

        $this->assertStringContainsString('SILAYAN', $view);
        $this->assertStringContainsString('Reset Password', $view);
        $this->assertStringContainsString(e($this->user->name), $view);
        $this->assertStringContainsString($resetUrl, $view);
        $this->assertStringContainsString('15 menit', $view);

        // Memastikan password pengguna TIDAK berada di email
        $this->assertStringNotContainsString('passwordLama123', $view);
        $this->assertStringNotContainsString($this->user->password, $view);
    }
}
