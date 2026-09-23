<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — SILAYAN</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2b2d42;
            line-height: 1.6;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f6f9;
            padding: 30px 0;
        }
        .email-container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .email-header {
            background: linear-gradient(135deg, #002A5C 0%, #003F88 50%, #0077B6 100%);
            color: #ffffff;
            padding: 30px 24px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0 0 6px;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .email-header p {
            margin: 0;
            font-size: 13px;
            color: #e0f2fe;
        }
        .email-body {
            padding: 32px 28px;
        }
        .email-body h2 {
            font-size: 18px;
            color: #002A5C;
            margin-top: 0;
            margin-bottom: 16px;
            font-weight: 700;
        }
        .greeting {
            font-size: 15px;
            font-weight: 600;
            color: #002A5C;
            margin-bottom: 14px;
        }
        .intro-text {
            font-size: 14px;
            color: #4a5568;
            margin-bottom: 20px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 28px 0;
        }
        .btn-reset {
            background-color: #003F88;
            color: #ffffff !important;
            display: inline-block;
            padding: 14px 32px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 63, 136, 0.25);
        }
        .link-fallback {
            font-size: 12px;
            color: #718096;
            margin-top: 20px;
            margin-bottom: 6px;
        }
        .link-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            word-break: break-all;
            font-size: 12px;
            color: #0077B6;
        }
        .notice-box {
            background-color: #fffbe6;
            border-left: 4px solid #f59e0b;
            padding: 14px 16px;
            border-radius: 6px;
            margin-top: 24px;
            font-size: 13px;
            color: #78350f;
            line-height: 1.5;
        }
        .signature {
            margin-top: 28px;
            font-size: 14px;
            color: #4a5568;
            border-top: 1px dashed #e2e8f0;
            padding-top: 16px;
        }
        .email-footer {
            background-color: #0f172a;
            color: #94a3b8;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            line-height: 1.6;
        }
        .email-footer strong {
            color: #f1f5f9;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <h1>SILAYAN</h1>
                <p>Sistem Informasi Pelayanan & Pengaduan Masyarakat<br>Dinas Ketahanan Pangan dan Perikanan Kabupaten Banyumas</p>
            </div>

            <!-- Body -->
            <div class="email-body">
                <h2>Reset Password</h2>
                
                <div class="greeting">Yth. {{ $recipientName ?? $user->name ?? 'Pengguna' }},</div>
                
                <p class="intro-text">
                    Kami menerima permintaan untuk mengatur ulang password akun Anda pada portal SILAYAN. Silakan klik tombol berikut untuk membuat password baru:
                </p>

                <!-- CTA Button -->
                <div class="btn-wrapper">
                    <a href="{{ $resetUrl }}" class="btn-reset" target="_blank">Reset Password</a>
                </div>

                <!-- Fallback URL -->
                <p class="link-fallback">Jika tombol di atas tidak dapat diklik, salin dan tempel URL berikut ke peramban (browser) Anda:</p>
                <div class="link-box">
                    <a href="{{ $resetUrl }}" style="color: #0077B6; text-decoration: none;">{{ $resetUrl }}</a>
                </div>

                <!-- Notice -->
                <div class="notice-box">
                    <strong>⏳ Batas Waktu:</strong> Link berlaku selama <strong>{{ $expireMinutes ?? 15 }} menit</strong> sejak email ini dikirimkan.<br><br>
                    <strong>Catatan Keamanan:</strong> Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini. Password akun Anda tetap aman.
                </div>

                <!-- Sign off -->
                <div class="signature">
                    Salam,<br><br>
                    <strong>SILAYAN</strong><br>
                    Sistem Informasi Pelayanan
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <strong>Dinas Ketahanan Pangan dan Perikanan Kabupaten Banyumas</strong><br>
                Jl. Raya Perikanan, Kabupaten Banyumas, Jawa Tengah<br>
                <p style="margin-top: 8px; margin-bottom: 0; font-size: 11px; color: #64748b;">
                    Email ini dikirim secara otomatis oleh sistem keamanan SILAYAN.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
