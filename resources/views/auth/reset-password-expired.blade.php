<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Reset Password Tidak Valid — CMS Portal Perikanan</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #003F88;
            --primary-dark: #002A5C;
            --ocean: #0077B6;
            --white: #FFFFFF;
            --radius-lg: 20px;
            --shadow-md: 0 8px 30px rgba(0,0,0,0.15);
            --transition: 0.25s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--ocean) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        .auth-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            width: 100%;
            max-width: 440px;
            padding: 40px 36px;
            text-align: center;
        }

        .auth-header {
            margin-bottom: 24px;
        }

        .icon-container {
            width: 70px;
            height: 70px;
            background-color: #fee2e2;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .auth-header h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            color: #dc3545;
            margin-top: 5px;
            margin-bottom: 12px;
        }

        .description-text {
            color: #4b5563;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 28px;
            text-align: center;
        }

        .btn-primary-cms {
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 700;
            width: 100%;
            transition: background var(--transition);
            text-decoration: none;
            display: block;
        }

        .btn-primary-cms:hover {
            background: var(--primary-dark);
            color: var(--white);
        }

        .btn-outline-cms {
            background: transparent;
            color: #6b7280;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 11px;
            font-weight: 600;
            width: 100%;
            transition: all var(--transition);
            text-decoration: none;
            display: block;
            font-size: 0.9rem;
        }

        .btn-outline-cms:hover {
            background: #f3f4f6;
            color: #374151;
            border-color: #9ca3af;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-header">
        <div class="icon-container">
            <i class="bi bi-shield-exclamation text-danger" style="font-size: 2.2rem;"></i>
        </div>
        <h1>Link Reset Password Tidak Valid</h1>
    </div>

    <div class="description-text">
        <p class="mb-3">Link reset password yang Anda gunakan tidak valid atau telah kedaluwarsa.</p>
        <p class="mb-3">Demi keamanan akun, setiap link reset password hanya dapat digunakan satu kali dan memiliki batas waktu penggunaan.</p>
        <p class="mb-0 fw-semibold text-dark">Silakan minta link reset password yang baru.</p>
    </div>

    <div class="d-flex flex-column gap-2">
        <a href="{{ route('cms.lupa-password') }}" class="btn btn-primary-cms shadow-sm">
            <i class="bi bi-arrow-clockwise me-1"></i> Minta Link Reset Baru
        </a>
        <a href="{{ route('cms.login') }}" class="btn btn-outline-cms">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
