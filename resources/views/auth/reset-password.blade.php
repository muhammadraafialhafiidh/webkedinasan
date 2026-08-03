<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — CMS Portal Perikanan</title>
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
        }

        .auth-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .auth-header h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary);
            margin-top: 10px;
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
        }

        .btn-primary-cms:hover {
            background: var(--primary-dark);
            color: var(--white);
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-header">
        <i class="bi bi-shield-lock text-primary" style="font-size: 2.5rem; color: var(--primary) !important;"></i>
        <h1>Buat Password Baru</h1>
        <p class="text-muted small">Masukkan password baru Anda (minimal 8 karakter).</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('cms.reset-password.post') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email" class="form-label fw-bold small">Alamat Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $email) }}" required readonly>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-bold small">Password Baru <span class="text-danger">*</span></label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required autofocus>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-bold small">Konfirmasi Password Baru <span class="text-danger">*</span></label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary-cms shadow-sm mb-3">
            <i class="bi bi-check2-circle me-1"></i> Simpan Password Baru
        </button>
    </form>

    <div class="text-center">
        <a href="{{ route('cms.login') }}" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left me-1"></i> Batal & Kembali ke Login
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
