<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — CMS Portal Perikanan</title>
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
        <i class="bi bi-key-fill text-primary" style="font-size: 2.5rem; color: var(--primary) !important;"></i>
        <h1>Lupa Password</h1>
        <p class="text-muted small">Masukkan email terdaftar Anda untuk menerima instruksi reset password.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show small" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('cms.lupa-password.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label fw-bold small">Alamat Email Terdaftar <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@perikanan.go.id" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary-cms shadow-sm mb-3">
            <i class="bi bi-send me-1"></i> Kirim Link Reset
        </button>
    </form>

    <div class="text-center">
        <a href="{{ route('cms.login') }}" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Login
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
