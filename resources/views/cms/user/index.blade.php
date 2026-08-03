@extends('layouts.cms')

@section('title', 'Manajemen User — Super Admin')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Manajemen User</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Manajemen User Pengelola</h3>
        <p class="text-muted small mb-0">Kelola akun Super Admin dan Admin Dinas yang berhak mengakses CMS</p>
    </div>
    <a href="{{ route('cms.user.create') }}" class="btn btn-primary font-bold shadow-sm">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah User Baru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama User</th>
                    <th>Email Login</th>
                    <th>Role Hak Akses</th>
                    <th>Status</th>
                    <th>Terakhir Login</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $usr)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td class="fw-bold text-primary">
                            {{ $usr->name }}
                            @if($usr->id === Auth::id())
                                <span class="badge bg-info text-dark ms-1 small">Akun Anda</span>
                            @endif
                        </td>
                        <td>{{ $usr->email }}</td>
                        <td>
                            @if($usr->isRootSuperAdmin())
                                <span class="badge bg-danger"><i class="bi bi-shield-lock-fill me-1"></i>Root Super Admin</span>
                            @elseif($usr->role === 'super_admin')
                                <span class="badge bg-primary"><i class="bi bi-shield-check me-1"></i>Super Admin</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-person me-1"></i>Admin Dinas</span>
                            @endif
                        </td>
                        <td>
                            @if($usr->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $usr->last_login_at ? $usr->last_login_at->format('d/m/Y H:i') : 'Belum pernah' }}</td>
                        <td class="text-center">
                            <div class="btn-action-group">
                                @can('resetPassword', $usr)
                                    <button type="button" class="btn btn-action btn-action-warning" data-bs-toggle="tooltip" data-bs-title="Reset Password" onclick="openResetPasswordModal({{ $usr->id }}, '{{ addslashes($usr->name) }}')">
                                        <i class="bi bi-key-fill"></i>
                                    </button>
                                @endcan
                                @can('update', $usr)
                                    <a href="{{ route('cms.user.edit', $usr->id) }}" class="btn btn-action btn-action-edit" data-bs-toggle="tooltip" data-bs-title="Edit User"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('delete', $usr)
                                    <form action="{{ route('cms.user.destroy', $usr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-action-delete" data-bs-toggle="tooltip" data-bs-title="Hapus Data"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-center mb-0">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="resetPasswordForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary">Reset Password User: <span id="resetUserName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning fw-bold">Simpan Password Baru</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openResetPasswordModal(id, name) {
        document.getElementById('resetUserName').innerText = name;
        document.getElementById('resetPasswordForm').action = "{{ url('/cms/user') }}/" + id + "/reset-password";
        const modal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
        modal.show();
    }
</script>
@endpush
