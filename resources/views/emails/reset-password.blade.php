<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Portal Dinas Perikanan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f7f6;
            padding: 30px 0;
        }
        .email-container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .email-header {
            background: linear-gradient(135deg, #003F88 0%, #0077B6 100%);
            color: #ffffff;
            padding: 30px 24px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .email-header p {
            margin: 5px 0 0;
            font-size: 13px;
            opacity: 0.85;
        }
        .email-body {
            padding: 32px 28px;
        }
        .email-body h2 {
            font-size: 18px;
            color: #003F88;
            margin-top: 0;
            margin-bottom: 16px;
        }
        .email-body p {
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
            padding: 14px 28px;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 63, 136, 0.25);
        }
        .btn-reset:hover {
            background-color: #002A5C;
        }
        .link-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            word-break: break-all;
            font-size: 12px;
            color: #0077B6;
            margin-top: 15px;
        }
        .security-notice {
            background-color: #fffbe6;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 4px;
            margin-top: 24px;
            font-size: 13px;
            color: #78350f;
        }
        .email-footer {
            background-color: #f8fafc;
            border-top: 1px solid #edf2f7;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <h1>CMS PORTAL DINAS PERIKANAN</h1>
                <p>Permintaan Reset Password Akun</p>
            </div>
            <div class="email-body">
                <h2>Halo, {{ $user->name ?? 'Pengguna' }} 👋</h2>
                <p>Kami menerima permintaan untuk melakukan reset password akun CMS Anda. Silakan klik tombol di bawah ini untuk membuat password baru:</p>
                
                <div class="btn-wrapper">
                    <a href="{{ $resetUrl }}" class="btn-reset" target="_blank">Ubah Password Sekarang</a>
                </div>

                <p style="font-size: 13px; color: #718096; margin-bottom: 5px;">Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke peramban (browser) Anda:</p>
                <div class="link-box">
                    <a href="{{ $resetUrl }}" style="color: #0077B6; text-decoration: none;">{{ $resetUrl }}</a>
                </div>

                <div class="security-notice">
                    <strong>⏳ Batas Waktu:</strong> Link reset password ini hanya berlaku selama <strong>15 menit</strong> sejak email ini dikirimkan.<br>
                    <strong>Catatan Keamanan:</strong> Jika Anda tidak merasa meminta reset password, silakan abaikan email ini. Password akun Anda akan tetap aman dan tidak berubah.
                </div>
            </div>
            <div class="email-footer">
                &copy; {{ date('Y') }} Portal Informasi Dinas Perikanan. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
