@extends('layouts.cms')

@section('title', 'Tambah User Baru — Super Admin')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.user.index') }}" class="text-decoration-none text-muted">User</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Tambah User Pengelola Baru</h3>
        <p class="text-muted small mb-0">Buat akun akses CMS baru untuk staff atau administrator</p>
    </div>
    <a href="{{ route('cms.user.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 p-md-5">
            <form action="{{ route('cms.user.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Admin Pengelola Berita" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email Login <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="nama@perikanan.go.id" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role Hak Akses <span class="text-danger">*</span></label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Pengelola Konten)</option>
                        @if(Auth::user() && Auth::user()->isRootSuperAdmin())
                            <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin (Akses Penuh Pengaturan & User)</option>
                        @endif
                    </select>
                </div>

                <div class="mb-4">
                    <label for="is_active" class="form-label">Status Akun <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-select" required>
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif (Dapat Login)</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif (Tidak Bisa Login)</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-4">
                    <a href="{{ route('cms.user.index') }}" class="btn btn-light border">Batal</a>
                    <button type="submit" class="btn btn-primary font-bold px-4 shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Simpan User Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
