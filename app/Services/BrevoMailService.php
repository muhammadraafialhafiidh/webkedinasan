<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoMailService
{
    protected ?string $apiKey;
    protected string $senderEmail;
    protected string $senderName;
    protected string $apiUrl = 'https://api.brevo.com/v3/smtp/email';

    public function __construct()
    {
        $this->apiKey = config('services.brevo.api_key', env('BREVO_API_KEY'));
        $this->senderEmail = config('services.brevo.sender_email', env('BREVO_SENDER_EMAIL', 'dkpp.kabupaten.banyumas@gmail.com'));
        $this->senderName = config('services.brevo.sender_name', env('BREVO_SENDER_NAME', 'SILAYAN (Sistem Informasi Pelayanan) Dinas Ketahanan Pangan dan Perikanan'));
    }

    /**
     * Kirim email reset password ke user melalui Brevo Transactional Email API.
     *
     * @param User $user
     * @param string $resetUrl
     * @param int $expireMinutes
     * @return array
     */
    public function sendPasswordReset(User $user, string $resetUrl, int $expireMinutes = 15): array
    {
        if (empty($this->apiKey)) {
            Log::warning('[BrevoMailService] Gagal mengirim email reset password: BREVO_API_KEY belum dikonfigurasi.');
            return [
                'success' => false,
                'error' => 'API Key Brevo belum dikonfigurasi pada environment.',
            ];
        }

        if (empty($user->email) || !filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            Log::warning('[BrevoMailService] Gagal mengirim email reset password: Format alamat email pengguna tidak valid atau kosong.', [
                'user_id' => $user->id,
            ]);
            return [
                'success' => false,
                'error' => 'Alamat email pengguna tidak valid atau kosong.',
            ];
        }

        try {
            $recipientName = $user->name ?: 'Pengguna CMS';

            // Render template HTML email dengan data aman
            $htmlContent = view('emails.reset-password', [
                'user' => $user,
                'recipientName' => $recipientName,
                'resetUrl' => $resetUrl,
                'expireMinutes' => $expireMinutes,
                'senderName' => $this->senderName,
                'senderEmail' => $this->senderEmail,
            ])->render();

            $payload = [
                'sender' => [
                    'name' => $this->senderName,
                    'email' => $this->senderEmail,
                ],
                'to' => [
                    [
                        'email' => trim($user->email),
                        'name' => $recipientName,
                    ],
                ],
                'subject' => 'Reset Password Akun — SILAYAN',
                'htmlContent' => $htmlContent,
            ];

            // Request ke Brevo API dengan timeout 15 detik (tanpa mencatat api-key di log)
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(15)->post($this->apiUrl, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('[BrevoMailService] Email reset password berhasil dikirim melalui Brevo API', [
                    'user_id' => $user->id,
                    'recipient' => $user->email,
                    'message_id' => $responseData['messageId'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message_id' => $responseData['messageId'] ?? null,
                ];
            }

            // Log response error tanpa mencatat API key atau token
            Log::error('[BrevoMailService] Brevo API mengembalikan respon error saat kirim reset password', [
                'user_id' => $user->id,
                'recipient' => $user->email,
                'status' => $response->status(),
                'response' => $response->json() ?? $response->body(),
            ]);

            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body(),
            ];
        } catch (\Throwable $e) {
            // Tangkap exception (misal connection timeout) dan catat secara aman
            Log::error('[BrevoMailService] Terjadi exception saat menghubungi Brevo API untuk reset password', [
                'user_id' => $user->id,
                'recipient' => $user->email,
                'exception' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Kirim email balasan ke pengunjung kontak melalui Brevo Transactional Email API.
     *
     * @param Contact $contact
     * @param string $replyText
     * @return array
     */
    public function sendContactReply(Contact $contact, string $replyText): array
    {
        if (empty($this->apiKey)) {
            Log::warning('[BrevoMailService] Gagal mengirim email: BREVO_API_KEY belum dikonfigurasi.');
            return [
                'success' => false,
                'error' => 'API Key Brevo belum dikonfigurasi pada environment.',
            ];
        }

        if (empty($contact->email) || !filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
            Log::warning('[BrevoMailService] Gagal mengirim email: Format alamat email penerima tidak valid atau kosong.', [
                'contact_id' => $contact->id,
            ]);
            return [
                'success' => false,
                'error' => 'Alamat email penerima tidak valid atau kosong.',
            ];
        }

        try {
            $cleanReply = Contact::htmlToPlainText($replyText);
            $cleanMessage = Contact::htmlToPlainText($contact->message ?? '-');

            // Render template HTML email dengan data yang sudah di-escape di Blade
            $htmlContent = view('emails.contact-reply', [
                'recipientName' => $contact->name ?? 'Pengunjung',
                'senderName' => $this->senderName,
                'senderEmail' => $this->senderEmail,
                'originalSubject' => $contact->subject ?? '-',
                'originalMessage' => $cleanMessage,
                'originalDate' => $contact->created_at ? $contact->created_at->translatedFormat('d F Y H:i') : '',
                'replyText' => $cleanReply,
            ])->render();

            $payload = [
                'sender' => [
                    'name' => $this->senderName,
                    'email' => $this->senderEmail,
                ],
                'to' => [
                    [
                        'email' => trim($contact->email),
                        'name' => $contact->name ?: trim($contact->email),
                    ],
                ],
                'subject' => 'Balasan atas Masukan Anda - SILAYAN',
                'htmlContent' => $htmlContent,
            ];

            // Request ke Brevo API dengan timeout 15 detik (tanpa mencatat api-key di log)
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(15)->post($this->apiUrl, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('[BrevoMailService] Email balasan berhasil dikirim melalui Brevo API', [
                    'contact_id' => $contact->id,
                    'recipient' => $contact->email,
                    'message_id' => $responseData['messageId'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message_id' => $responseData['messageId'] ?? null,
                ];
            }

            // Log response error tanpa mencatat API key
            Log::error('[BrevoMailService] Brevo API mengembalikan respon error', [
                'contact_id' => $contact->id,
                'recipient' => $contact->email,
                'status' => $response->status(),
                'response' => $response->json() ?? $response->body(),
            ]);

            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body(),
            ];
        } catch (\Throwable $e) {
            // Tangkap exception (misal connection timeout) dan catat secara aman
            Log::error('[BrevoMailService] Terjadi exception saat menghubungi Brevo API', [
                'contact_id' => $contact->id,
                'recipient' => $contact->email,
                'exception' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
