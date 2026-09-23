<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan atas Masukan Anda - SILAYAN</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2b2d42;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .email-header {
            background: linear-gradient(135deg, #002A5C 0%, #003F88 50%, #0077B6 100%);
            color: #ffffff;
            padding: 30px 25px;
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
            padding: 30px 25px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #002A5C;
            margin-bottom: 15px;
        }
        .intro-text {
            font-size: 14px;
            color: #4a5568;
            margin-bottom: 20px;
        }
        .box-section {
            border-radius: 8px;
            padding: 16px 18px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .original-message-box {
            background-color: #f8fafc;
            border-left: 4px solid #94a3b8;
            color: #475569;
        }
        .original-message-box .title {
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .reply-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 4px solid #16a34a;
            color: #166534;
        }
        .reply-box .title {
            font-weight: 700;
            color: #15803d;
            margin-bottom: 10px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .reply-content {
            color: #1e293b;
            font-size: 14px;
            line-height: 1.7;
            white-space: pre-wrap;
        }
        .footer-note {
            font-size: 13px;
            color: #64748b;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px dashed #e2e8f0;
        }
        .email-footer {
            background-color: #0f172a;
            color: #94a3b8;
            padding: 20px 25px;
            font-size: 12px;
            text-align: center;
            line-height: 1.6;
        }
        .email-footer a {
            color: #38bdf8;
            text-decoration: none;
        }
        .email-footer strong {
            color: #f1f5f9;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>SILAYAN</h1>
            <p>Sistem Informasi Pelayanan & Pengaduan Masyarakat<br>Dinas Ketahanan Pangan dan Perikanan Kabupaten Banyumas</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">Yth. Bapak/Ibu {{ $recipientName }},</div>
            <p class="intro-text">
                Terima kasih telah menghubungi Dinas Ketahanan Pangan dan Perikanan Kabupaten Banyumas melalui portal SILAYAN. Berikut ini adalah tanggapan resmi dari tim kami atas pesan/masukan yang telah Anda kirimkan:
            </p>

            <!-- Reply Box -->
            <div class="box-section reply-box">
                <div class="title">💬 Tanggapan Tim Layanan SILAYAN:</div>
                <div class="reply-content">{!! nl2br(e($replyText)) !!}</div>
            </div>

            <!-- Original Message Summary Box -->
            <div class="box-section original-message-box">
                <div class="title">📋 Rincian Pesan yang Anda Kirimkan:</div>
                <p style="margin: 0 0 6px 0;"><strong>Subjek:</strong> {{ $originalSubject }}</p>
                @if(!empty($originalDate))
                    <p style="margin: 0 0 6px 0;"><strong>Waktu Kirim:</strong> {{ $originalDate }}</p>
                @endif
                <p style="margin: 0 0 6px 0;"><strong>Isi Pesan:</strong></p>
                <div style="font-style: italic; color: #64748b;">
                    "{!! nl2br(e($originalMessage)) !!}"
                </div>
            </div>

            <p class="footer-note">
                Apabila Bapak/Ibu masih memiliki pertanyaan lebih lanjut, silakan mengirimkan kembali pesan melalui menu Kontak pada website resmi kami.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <strong>Dinas Ketahanan Pangan dan Perikanan Kabupaten Banyumas</strong><br>
            Jl. Raya Perikanan, Kabupaten Banyumas, Jawa Tengah<br>
            Email: <a href="mailto:{{ $senderEmail }}">{{ $senderEmail }}</a><br>
            <p style="margin-top: 10px; margin-bottom: 0; font-size: 11px; color: #64748b;">
                Email ini dibuat secara otomatis melalui integrasi resmi SILAYAN. Mohon tidak membalas langsung ke alamat email sistem jika tidak ditujukan untuk korespondensi resmi.
            </p>
        </div>
    </div>
</body>
</html>
