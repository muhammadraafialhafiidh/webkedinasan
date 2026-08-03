@extends('layouts.cms')

@section('title', 'Statistik Website — CMS Perikanan')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Statistik Website</h4>
            <p class="text-muted small mb-0">Analisis dan pemantauan jumlah kunjungan pengunjung portal informasi publik secara otomatis (Auto Refresh 30s).</p>
        </div>
        <div class="bg-white border rounded-3 px-3 py-2 shadow-sm text-end d-flex align-items-center gap-3">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 rounded-pill">
                <i class="bi bi-arrow-repeat me-1"></i> Auto Refresh 30s
            </span>
            <div>
                <span class="small text-muted d-block" style="font-size: 0.75rem;">Pembaruan Terakhir:</span>
                <span class="fw-bold text-primary small" id="cms-last-update-time">{{ now()->format('d F Y, H:i:s') }} WIB</span>
            </div>
        </div>
    </div>

    <!-- Section 1: Ringkasan Statistik (5 Cards - NO ICONS) -->
    <div class="row g-3 mb-4">
        <div class="col-lg col-md-4 col-sm-6">
            <div class="bg-white p-3 rounded-3 border shadow-sm text-center h-100">
                <div class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Hari Ini</div>
                <div class="fs-3 fw-extrabold text-primary" id="cms-stat-hari-ini">{{ number_format($summary['hari_ini'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-lg col-md-4 col-sm-6">
            <div class="bg-white p-3 rounded-3 border shadow-sm text-center h-100">
                <div class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Minggu Ini</div>
                <div class="fs-3 fw-extrabold text-info" id="cms-stat-minggu-ini">{{ number_format($summary['minggu_ini'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-lg col-md-4 col-sm-6">
            <div class="bg-white p-3 rounded-3 border shadow-sm text-center h-100">
                <div class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Bulan Ini</div>
                <div class="fs-3 fw-extrabold text-success" id="cms-stat-bulan-ini">{{ number_format($summary['bulan_ini'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-lg col-md-6 col-sm-6">
            <div class="bg-white p-3 rounded-3 border shadow-sm text-center h-100">
                <div class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Tahun Ini</div>
                <div class="fs-3 fw-extrabold text-warning" id="cms-stat-tahun-ini">{{ number_format($summary['tahun_ini'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-lg col-md-6 col-sm-12">
            <div class="bg-white p-3 rounded-3 border shadow-sm text-center h-100">
                <div class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Pengunjung</div>
                <div class="fs-3 fw-extrabold text-dark" id="cms-stat-total-pengunjung">{{ number_format($summary['total'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Section 2 & Section 6: Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Section 2: Grafik Pengunjung 30 Hari Terakhir -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">Grafik Pengunjung (30 Hari Terakhir)</h6>
                    <span class="badge bg-light text-dark border">Line Chart</span>
                </div>
                <div class="card-body p-3">
                    <div style="height: 300px; position: relative;">
                        <canvas id="dailyVisitorsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 6: Statistik Per Jam Hari Ini -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">Statistik Per Jam Hari Ini</h6>
                    <span class="badge bg-light text-dark border">Bar Chart</span>
                </div>
                <div class="card-body p-3">
                    <div style="height: 300px; position: relative;">
                        <canvas id="hourlyVisitorsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3, Section 4 & Section 5: Analytics Details -->
    <div class="row g-4">
        <!-- Section 3: Halaman Terpopuler -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark">Halaman Terpopuler</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th>Nama Halaman</th>
                                    <th style="width: 150px;" class="text-center">Jumlah Pengunjung</th>
                                </tr>
                            </thead>
                            <tbody id="popular-pages-tbody">
                                @forelse($popularPages as $index => $page)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $page->page_name ?? 'Halaman Publik' }}</div>
                                            <small class="text-muted text-truncate d-block" style="max-width: 320px;">{{ $page->url }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1.5 rounded-pill border border-primary-subtle">
                                                {{ number_format($page->total_views, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada data kunjungan halaman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Jenis Perangkat (NO ICONS) -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark">Jenis Perangkat</h6>
                </div>
                <div class="card-body p-3">
                    <div id="device-breakdown-container">
                        @foreach($deviceBreakdown as $device => $count)
                            @php
                                $percentage = round(($count / $totalDevice) * 100, 1);
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold text-dark small">{{ $device }}</span>
                                    <span class="small text-muted">{{ number_format($count) }} ({{ $percentage }}%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $device === 'Desktop' ? 'bg-primary' : ($device === 'Mobile' ? 'bg-info' : 'bg-warning') }}" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-sm table-bordered mb-0 text-center small">
                            <thead class="bg-light">
                                <tr>
                                    <th>Perangkat</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="device-table-tbody">
                                @foreach($deviceBreakdown as $device => $count)
                                    <tr>
                                        <td class="fw-medium text-start ps-3">{{ $device }}</td>
                                        <td class="fw-bold">{{ number_format($count) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Browser -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark">Statistik Browser</h6>
                </div>
                <div class="card-body p-3">
                    <div style="height: 180px; position: relative;" class="mb-3">
                        <canvas id="browserDoughnutChart"></canvas>
                    </div>

                    <div class="pt-2 border-top" id="browser-breakdown-container">
                        @foreach($browserBreakdown as $browser => $count)
                            @php
                                $percent = round(($count / $totalBrowser) * 100, 1);
                            @endphp
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom small">
                                <span class="fw-medium text-dark">{{ $browser }}</span>
                                <span class="fw-bold">{{ number_format($count) }} <span class="text-muted fw-normal">({{ $percent }}%)</span></span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let dailyChartInstance = null;
        let hourlyChartInstance = null;
        let browserChartInstance = null;

        // Counter Animation Helper
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

        // Section 2: Line Chart 30 Hari
        const dailyCtx = document.getElementById('dailyVisitorsChart');
        if (dailyCtx) {
            dailyChartInstance = new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($dailyChart['labels']) !!},
                    datasets: [{
                        label: 'Jumlah Pengunjung',
                        data: {!! json_encode($dailyChart['data']) !!},
                        borderColor: '#0077B6',
                        backgroundColor: 'rgba(0, 119, 182, 0.08)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#003F88'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        }

        // Section 6: Bar Chart Hourly
        const hourlyCtx = document.getElementById('hourlyVisitorsChart');
        if (hourlyCtx) {
            hourlyChartInstance = new Chart(hourlyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($hourlyChart['labels']) !!},
                    datasets: [{
                        label: 'Pengunjung',
                        data: {!! json_encode($hourlyChart['data']) !!},
                        backgroundColor: '#00B4D8',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        }

        // Section 5: Doughnut Chart Browser
        const browserCtx = document.getElementById('browserDoughnutChart');
        if (browserCtx) {
            browserChartInstance = new Chart(browserCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($browserBreakdown)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($browserBreakdown)) !!},
                        backgroundColor: ['#003F88', '#0077B6', '#00B4D8', '#F4A100', '#64748B'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                    },
                    cutout: '68%'
                }
            });
        }

        // Helper untuk memperbarui UI komponen non-chart saat event diterima
        function updatePageComponents(data) {
            // Update waktu pembaruan
            const timeEl = document.getElementById('cms-last-update-time');
            if (timeEl) {
                const now = new Date();
                timeEl.innerText = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) + ', ' +
                                   now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
            }

            // 1. Update 5 Ringkasan Card
            if (data.summary) {
                animateCounter('cms-stat-hari-ini', data.summary.hari_ini);
                animateCounter('cms-stat-minggu-ini', data.summary.minggu_ini);
                animateCounter('cms-stat-bulan-ini', data.summary.bulan_ini);
                animateCounter('cms-stat-tahun-ini', data.summary.tahun_ini);
                animateCounter('cms-stat-total-pengunjung', data.summary.total);
            }

            // 2. Update Line Chart 30 Hari
            if (dailyChartInstance && data.dailyChart) {
                dailyChartInstance.data.labels = data.dailyChart.labels;
                dailyChartInstance.data.datasets[0].data = data.dailyChart.data;
                dailyChartInstance.update();
            }

            // 3. Update Bar Chart Hourly
            if (hourlyChartInstance && data.hourlyChart) {
                hourlyChartInstance.data.labels = data.hourlyChart.labels;
                hourlyChartInstance.data.datasets[0].data = data.hourlyChart.data;
                hourlyChartInstance.update();
            }

            // 4. Update Doughnut Chart Browser
            if (browserChartInstance && data.browserBreakdown) {
                browserChartInstance.data.labels = Object.keys(data.browserBreakdown);
                browserChartInstance.data.datasets[0].data = Object.values(data.browserBreakdown);
                browserChartInstance.update();
            }

            // 5. Update Tabel Halaman Terpopuler
            if (data.popularPages && document.getElementById('popular-pages-tbody')) {
                let html = '';
                data.popularPages.forEach(function (page, idx) {
                    const views = new Intl.NumberFormat('id-ID').format(page.total_views);
                    html += `
                        <tr>
                            <td class="text-center fw-bold text-muted">${idx + 1}</td>
                            <td>
                                <div class="fw-semibold text-dark">${page.page_name || 'Halaman Publik'}</div>
                                <small class="text-muted text-truncate d-block" style="max-width: 320px;">${page.url}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1.5 rounded-pill border border-primary-subtle">
                                    ${views}
                                </span>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('popular-pages-tbody').innerHTML = html;
            }

            // 6. Update Breakdown Perangkat
            if (data.deviceBreakdown && data.totalDevice) {
                const totalDev = data.totalDevice || 1;
                let progressHtml = '';
                let tableHtml = '';

                Object.keys(data.deviceBreakdown).forEach(function (device) {
                    const count = data.deviceBreakdown[device];
                    const percent = Math.round((count / totalDev) * 1000) / 10;
                    const bgClass = device === 'Desktop' ? 'bg-primary' : (device === 'Mobile' ? 'bg-info' : 'bg-warning');
                    const formattedCount = new Intl.NumberFormat('id-ID').format(count);

                    progressHtml += `
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark small">${device}</span>
                                <span class="small text-muted">${formattedCount} (${percent}%)</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar ${bgClass}" role="progressbar" style="width: ${percent}%;" aria-valuenow="${percent}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    `;

                    tableHtml += `
                        <tr>
                            <td class="fw-medium text-start ps-3">${device}</td>
                            <td class="fw-bold">${formattedCount}</td>
                        </tr>
                    `;
                });

                const devContainer = document.getElementById('device-breakdown-container');
                if (devContainer) devContainer.innerHTML = progressHtml;

                const devTbody = document.getElementById('device-table-tbody');
                if (devTbody) devTbody.innerHTML = tableHtml;
            }

            // 7. Update Browser Breakdown List
            if (data.browserBreakdown && data.totalBrowser) {
                const totalB = data.totalBrowser || 1;
                let browserHtml = '';
                Object.keys(data.browserBreakdown).forEach(function (b) {
                    const count = data.browserBreakdown[b];
                    const percent = Math.round((count / totalB) * 1000) / 10;
                    const formattedCount = new Intl.NumberFormat('id-ID').format(count);
                    browserHtml += `
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom small">
                            <span class="fw-medium text-dark">${b}</span>
                            <span class="fw-bold">${formattedCount} <span class="text-muted fw-normal">(${percent}%)</span></span>
                        </div>
                    `;
                });
                const bContainer = document.getElementById('browser-breakdown-container');
                if (bContainer) bContainer.innerHTML = browserHtml;
            }
        }

        function fetchLiveStats() {
            fetch("{{ route('cms.statistik.live') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                if (data) {
                    updatePageComponents(data);
                }
            })
            .catch(error => {
                // Ignore network errors silently
            });
        }

        // Auto Refresh statistik setiap 30 detik via Fetch API
        setInterval(fetchLiveStats, 30000);
    });
</script>
@endpush
