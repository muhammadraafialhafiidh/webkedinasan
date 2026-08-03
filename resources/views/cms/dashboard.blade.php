@extends('layouts.cms')

@section('title', 'Dashboard — CMS Perikanan')

@section('cms_breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')

<!-- Welcome Banner -->
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, var(--primary) 0%, var(--ocean) 100%); color: white; border-radius: var(--radius-md);">
    <div class="card-body p-4 p-md-5 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <span class="badge bg-warning text-dark fw-bold text-uppercase px-3 py-1 mb-2">Selamat Datang</span>
            <h2 class="fw-extrabold text-white mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                Halo, {{ $user->name }}!
            </h2>
            <p class="text-white-50 mb-0">
                Anda login sebagai <strong class="text-white">{{ $user->isSuperAdmin() ? 'Super Admin' : 'Admin Dinas' }}</strong>. Kelola berita, layanan, dan informasi dinas di sini.
            </p>
        </div>
        <div>
            <a href="{{ route('cms.berita.create') }}" class="btn btn-warning fw-bold text-dark rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tulis Berita Baru
            </a>
        </div>
    </div>
</div>

<!-- 4 Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid var(--primary) !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Total Berita</div>
                    <h3 class="fw-bold mb-0 text-primary mt-1">{{ $stats['total_berita'] }}</h3>
                </div>
                <div class="bg-light text-primary rounded-circle p-3">
                    <i class="bi bi-newspaper fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid var(--success) !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Berita Dipublikasi</div>
                    <h3 class="fw-bold mb-0 text-success mt-1">{{ $stats['berita_published'] }}</h3>
                </div>
                <div class="bg-light text-success rounded-circle p-3">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid var(--danger) !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Pesan Belum Dibaca</div>
                    <h3 class="fw-bold mb-0 text-danger mt-1">{{ $stats['pesan_unread'] }}</h3>
                </div>
                <div class="bg-light text-danger rounded-circle p-3">
                    <i class="bi bi-envelope-exclamation-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3 h-100" style="border-left: 4px solid var(--teal) !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Galeri Foto</div>
                    <h3 class="fw-bold mb-0 text-info mt-1">{{ $stats['total_foto'] }}</h3>
                </div>
                <div class="bg-light text-info rounded-circle p-3">
                    <i class="bi bi-images fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Website Visitors Summary Cards (NO ICONS, Auto Refresh 30s) -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="fw-bold mb-0 text-dark">Ringkasan Statistik Website</h6>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill">
            <i class="bi bi-arrow-repeat me-1"></i> Auto Refresh 30s
        </span>
    </div>
    <div class="card-body p-3">
        <div class="row g-3">
            <div class="col-lg col-md-4 col-sm-6">
                <div class="bg-light p-3 rounded-3 border text-center h-100">
                    <div class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.725rem;">Hari Ini</div>
                    <div class="fs-4 fw-extrabold text-primary" id="stat-hari-ini">{{ number_format($visitorStats['summary']['hari_ini'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-lg col-md-4 col-sm-6">
                <div class="bg-light p-3 rounded-3 border text-center h-100">
                    <div class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.725rem;">Minggu Ini</div>
                    <div class="fs-4 fw-extrabold text-info" id="stat-minggu-ini">{{ number_format($visitorStats['summary']['minggu_ini'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-lg col-md-4 col-sm-6">
                <div class="bg-light p-3 rounded-3 border text-center h-100">
                    <div class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.725rem;">Bulan Ini</div>
                    <div class="fs-4 fw-extrabold text-success" id="stat-bulan-ini">{{ number_format($visitorStats['summary']['bulan_ini'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-lg col-md-6 col-sm-6">
                <div class="bg-light p-3 rounded-3 border text-center h-100">
                    <div class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.725rem;">Tahun Ini</div>
                    <div class="fs-4 fw-extrabold text-warning" id="stat-tahun-ini">{{ number_format($visitorStats['summary']['tahun_ini'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-lg col-md-6 col-sm-12">
                <div class="bg-light p-3 rounded-3 border text-center h-100">
                    <div class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.725rem;">Total Pengunjung</div>
                    <div class="fs-4 fw-extrabold text-dark" id="stat-total-pengunjung">{{ number_format($visitorStats['summary']['total'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="row g-4 mb-4">
    <!-- 5 Berita Terbaru (Left Column) -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-0">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-newspaper me-2"></i>Berita Terbaru</h6>
                <a href="{{ route('cms.berita.index') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua →</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-cms mb-0">
                    <thead>
                        <tr>
                            <th>Judul Berita</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentNews as $news)
                            <tr>
                                <td class="fw-semibold">
                                    <a href="{{ route('cms.berita.edit', $news->id) }}" class="text-dark text-decoration-none">
                                        {{ Str::limit($news->title, 40) }}
                                    </a>
                                </td>
                                <td><span class="badge bg-light text-primary border">{{ $news->newsCategory->name ?? '-' }}</span></td>
                                <td>
                                    @if($news->status === 'published')
                                        <span class="badge badge-published">Published</span>
                                    @else
                                        <span class="badge badge-draft">Draft</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $news->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted">Belum ada berita.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 5 Pesan Terbaru (Right Column) -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-0">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-envelope-exclamation me-2"></i>Pesan Masuk Terbaru</h6>
                <a href="{{ route('cms.pesan.index') }}" class="btn btn-sm btn-link text-decoration-none">Buka Inbox →</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentMessages as $msg)
                    <a href="{{ route('cms.pesan.show', $msg->id) }}" class="list-group-item list-group-item-action p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold small text-primary">{{ $msg->name }}</span>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $msg->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="fw-semibold small text-dark mb-1">{{ $msg->subject }}</div>
                        <p class="text-muted small mb-0 text-truncate" style="font-size: 0.8rem;">{{ $msg->message }}</p>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted small">Tidak ada pesan belum dibaca.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- 5 Log Aktivitas Terakhir (Super Admin visible or default) -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-0">
        <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-journal-text me-2"></i>Log Aktivitas Terakhir</h6>
        @if($user->isSuperAdmin())
            <a href="{{ route('cms.log-aktivitas.index') }}" class="btn btn-sm btn-link text-decoration-none">Semua Log →</a>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-cms mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Modul</th>
                    <th>Aksi</th>
                    <th>Deskripsi Detail</th>
                    <th>IP Address</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                    <tr>
                        <td class="fw-bold small">{{ $log->user_name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $log->module }}</span></td>
                        <td><span class="badge bg-info text-dark">{{ $log->action }}</span></td>
                        <td class="small">{{ $log->description }}</td>
                        <td class="small text-muted font-monospace">{{ $log->ip_address }}</td>
                        <td class="small text-muted">{{ $log->created_at ? $log->created_at->format('d/m/Y H:i') : '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-3 text-muted">Belum ada catatan log aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function animateCounter(elementId, targetValue) {
            const el = document.getElementById(elementId);
            if (!el) return;

            const currentText = el.innerText.replace(/[^0-9]/g, '');
            const currentValue = parseInt(currentText || '0', 10);
            if (currentValue === targetValue) return;

            let start = currentValue;
            const diff = Math.abs(targetValue - start);
            const step = targetValue > start ? 1 : -1;
            const duration = 600;
            const interval = Math.max(16, Math.floor(duration / Math.max(diff, 1)));

            const timer = setInterval(function () {
                start += step;
                el.innerText = new Intl.NumberFormat('id-ID').format(start);
                if (start === targetValue) {
                    clearInterval(timer);
                }
            }, interval);
        }

        function fetchLiveStats() {
            fetch("{{ route('cms.statistik.live') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response error');
                return response.json();
            })
            .then(data => {
                if (data && data.summary) {
                    animateCounter('stat-hari-ini', data.summary.hari_ini);
                    animateCounter('stat-minggu-ini', data.summary.minggu_ini);
                    animateCounter('stat-bulan-ini', data.summary.bulan_ini);
                    animateCounter('stat-tahun-ini', data.summary.tahun_ini);
                    animateCounter('stat-total-pengunjung', data.summary.total);
                }
            })
            .catch(error => {
                // Ignore network errors silently to prevent interrupting user experience
            });
        }

        // Auto Refresh statistik setiap 30 detik
        setInterval(fetchLiveStats, 30000);
    });
</script>
@endpush

@endsection
