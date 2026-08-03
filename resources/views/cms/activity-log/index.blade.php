@extends('layouts.cms')

@section('title', 'Log Aktivitas — Super Admin')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Log Aktivitas</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-primary mb-1">Log Aktivitas Sistem</h3>
        <p class="text-muted small mb-0">Catatan audit otomatis seluruh riwayat aktivitas pengelola CMS</p>
    </div>
    <a href="{{ route('cms.log-aktivitas.export', request()->query()) }}" class="btn btn-outline-success font-bold shadow-sm">
        <i class="bi bi-file-earmark-excel me-1"></i> Export Log (CSV/Excel)
    </a>
</div>

<!-- Filter Box -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('cms.log-aktivitas.index') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama user, deskripsi, atau IP..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">-- Semua User --</option>
                    @foreach($users as $usr)
                        <option value="{{ $usr->id }}" {{ request('user_id') == $usr->id ? 'selected' : '' }}>{{ $usr->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="module" class="form-select form-select-sm">
                    <option value="">-- Semua Modul --</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter me-1"></i> Filter</button>
                <a href="{{ route('cms.log-aktivitas.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Waktu (WIB)</th>
                    <th>User Pengelola</th>
                    <th>Modul</th>
                    <th>Aksi</th>
                    <th>Deskripsi Aktivitas</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="small font-monospace">#{{ $log->id }}</td>
                        <td class="small text-muted">{{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                        <td class="fw-bold text-primary small">{{ $log->user_name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $log->module }}</span></td>
                        <td><span class="badge bg-info text-dark">{{ $log->action }}</span></td>
                        <td class="small text-dark">{{ $log->description }}</td>
                        <td class="small text-muted font-monospace">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada catatan log aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-center mb-0">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
