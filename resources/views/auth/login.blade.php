<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login CMS — Portal Informasi Dinas Perikanan</title>
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
            --teal: #00B4D8;
            --gold: #F4A100;
            --white: #FFFFFF;
            --light: #F4F6F9;
            --dark-gray: #495057;
            --near-black: #1A1D23;
            --radius-md: 12px;
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

        .login-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            width: 100%;
            max-width: 440px;
            padding: 40px 36px;
            transition: transform var(--transition);
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-header img {
            width: 68px;
            height: auto;
            margin-bottom: 12px;
        }

        .login-header h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary);
            margin: 0 0 6px 0;
        }

        .login-header p {
            color: #6C757D;
            font-size: 0.875rem;
            margin: 0;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--near-black);
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 8px;
            border: 1.5px solid #DEE2E6;
            padding: 11px 14px;
            font-size: 0.95rem;
            transition: all var(--transition);
        }

        .form-control:focus {
            border-color: var(--ocean);
            box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.15);
        }

        .input-group-text {
            background: transparent;
            border: 1.5px solid #DEE2E6;
            border-left: none;
            cursor: pointer;
            color: #6C757D;
            border-radius: 0 8px 8px 0;
        }

        .input-group .form-control {
            border-right: none;
            border-radius: 8px 0 0 8px;
        }

        .btn-primary-cms {
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            transition: background var(--transition), transform var(--transition);
        }

        .btn-primary-cms:hover {
            background: var(--primary-dark);
            color: var(--white);
            transform: translateY(-1px);
        }

        .forgot-link {
            color: var(--ocean);
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 12px 16px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="mb-2">
            <i class="bi bi-shield-lock-fill text-primary" style="font-size: 2.8rem; color: var(--primary) !important;"></i>
        </div>
        <h1>Masuk ke CMS</h1>
        <p>Portal Informasi Dinas Perikanan</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('cms.login.post') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="nama@perikanan.go.id" required autofocus>
                <span class="input-group-text" style="border-left: 1.5px solid #DEE2E6; border-radius: 0 8px 8px 0;"><i class="bi bi-envelope"></i></span>
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">Password <span class="text-danger">*</span></label>
                <a href="{{ route('cms.lupa-password') }}" class="forgot-link">Lupa Password?</a>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                <span class="input-group-text" id="togglePassword"><i class="bi bi-eye"></i></span>
            </div>
        </div>

        <div class="mb-4 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label text-muted" for="remember" style="font-size: 0.875rem;">Ingat saya di perangkat ini</label>
        </div>

        <button type="submit" class="btn btn-primary-cms shadow-sm">
            <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="text-secondary text-decoration-none" style="font-size: 0.875rem;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Publik
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                }
            });
        }
    });
</script>
</body>
</html>
