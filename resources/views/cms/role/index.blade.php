@extends('layouts.cms')

@section('title', 'Hak Akses & Role — Super Admin')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Hak Akses Role</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Matriks Hak Akses & Role Sistem</h3>
        <p class="text-muted small mb-0">Daftar perizinan dan pembatasan akses untuk role Super Admin, Admin, dan Publik</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th>Fitur & Modul Sistem</th>
                    <th class="text-center" style="width: 150px;">Super Admin</th>
                    <th class="text-center" style="width: 150px;">Admin Dinas</th>
                    <th class="text-center" style="width: 150px;">Publik / Guest</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matrix as $item)
                    <tr>
                        <td class="fw-bold text-dark">{{ $item['feature'] }}</td>
                        <td class="text-center">
                            @if($item['super_admin'])
                                <i class="bi bi-check-circle-fill text-success fs-5" title="Diizinkan"></i>
                            @else
                                <i class="bi bi-x-circle-fill text-danger fs-5" title="Ditolak"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item['admin'])
                                <i class="bi bi-check-circle-fill text-success fs-5" title="Diizinkan"></i>
                            @else
                                <i class="bi bi-x-circle-fill text-danger fs-5" title="Ditolak"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item['public'])
                                <i class="bi bi-check-circle-fill text-success fs-5" title="Diizinkan"></i>
                            @else
                                <i class="bi bi-x-circle-fill text-danger fs-5" title="Ditolak"></i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-light py-3 border-0">
        <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Catatan: Role Super Admin memiliki hak penuh untuk mengelola pengguna, pengaturan website, dan audit log aktivitas.</small>
    </div>
</div>

@endsection
