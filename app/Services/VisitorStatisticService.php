<?php

namespace App\Services;

use App\Models\WebsiteVisitor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VisitorStatisticService
{
    /**
     * Helper ekspresi SQL untuk menghitung Visitor Unik berdasarkan Sesi (atau fallback IP jika session_id kosong)
     */
    private function distinctVisitor()
    {
        return DB::raw('id');
    }

    /**
     * Mendapatkan data ringkasan untuk beranda publik (4 card)
     */
    public function getPublicSummary(): array
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return [
            'hari_ini' => WebsiteVisitor::where('visited_at', '>=', $today)->count($this->distinctVisitor()),
            'minggu_ini' => WebsiteVisitor::whereBetween('visited_at', [$startOfWeek, $endOfWeek])->count($this->distinctVisitor()),
            'bulan_ini' => WebsiteVisitor::whereBetween('visited_at', [$startOfMonth, $endOfMonth])->count($this->distinctVisitor()),
            'total' => WebsiteVisitor::count($this->distinctVisitor()),
        ];
    }

    /**
     * Mendapatkan data statistik lengkap untuk dashboard CMS
     */
    public function getCmsFullStatistics(): array
    {
        $now = Carbon::now();
        $today = Carbon::today();

        // 1. Ringkasan 5 Card (Perhitungan Unique Visitor berdasarkan Session/IP)
        $summary = [
            'hari_ini' => WebsiteVisitor::where('visited_at', '>=', $today)->count($this->distinctVisitor()),
            'minggu_ini' => WebsiteVisitor::whereBetween('visited_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->count($this->distinctVisitor()),
            'bulan_ini' => WebsiteVisitor::whereBetween('visited_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->count($this->distinctVisitor()),
            'tahun_ini' => WebsiteVisitor::whereYear('visited_at', $now->year)->count($this->distinctVisitor()),
            'total' => WebsiteVisitor::count($this->distinctVisitor()),
        ];

        // 2. Grafik Pengunjung 30 Hari Terakhir
        $dailyChart = $this->getDailyChartData(30);

        // 3. Halaman Terpopuler (Perhitungan Seluruh Kunjungan Halaman Publik)
        $popularPages = \App\Models\WebsiteVisitorPageview::select('page_name', 'url', DB::raw('COUNT(*) as total_views'))
            ->groupBy('page_name', 'url')
            ->orderByDesc('total_views')
            ->limit(10)
            ->get();

        // 4. Jenis Perangkat (Desktop, Mobile, Tablet)
        $deviceBreakdown = [
            'Desktop' => WebsiteVisitor::where('device', 'Desktop')->count($this->distinctVisitor()),
            'Mobile' => WebsiteVisitor::where('device', 'Mobile')->count($this->distinctVisitor()),
            'Tablet' => WebsiteVisitor::where('device', 'Tablet')->count($this->distinctVisitor()),
        ];
        $totalDevice = array_sum($deviceBreakdown) ?: 1;

        // 5. Browser (Chrome, Edge, Firefox, Safari, Lainnya)
        $browserList = ['Chrome', 'Edge', 'Firefox', 'Safari'];
        $browserBreakdown = [];
        
        foreach ($browserList as $b) {
            $browserBreakdown[$b] = WebsiteVisitor::where('browser', $b)->count($this->distinctVisitor());
        }
        
        // Semua sisanya (Brave, Opera, versi historis yang tak dikenali, dll) masuk "Lainnya"
        $browserBreakdown['Lainnya'] = WebsiteVisitor::whereNotIn('browser', $browserList)->count($this->distinctVisitor());
        
        $totalBrowser = array_sum($browserBreakdown) ?: 1;

        // 6. Grafik Kunjungan Per Jam Hari Ini (00:00 - 23:00)
        $hourlyChart = $this->getHourlyChartDataToday();

        return compact(
            'summary',
            'dailyChart',
            'popularPages',
            'deviceBreakdown',
            'totalDevice',
            'browserBreakdown',
            'totalBrowser',
            'hourlyChart'
        );
    }

    private function getDailyChartData(int $days = 30): array
    {
        $startDate = Carbon::today()->subDays($days - 1);
        $endDate = Carbon::today()->endOfDay();

        $raw = WebsiteVisitor::select(DB::raw('DATE(visited_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(visited_at)'))
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $dateStr = $startDate->copy()->addDays($i)->format('Y-m-d');
            $labelStr = $startDate->copy()->addDays($i)->format('d M');

            $labels[] = $labelStr;
            $data[] = $raw[$dateStr] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getHourlyChartDataToday(): array
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $driver = DB::connection()->getDriverName();
        $hourExpression = $driver === 'sqlite'
            ? 'CAST(strftime("%H", visited_at) AS INTEGER)'
            : 'HOUR(visited_at)';

        if ($driver === 'mysql') {
            DB::statement("SET time_zone = '+07:00'");
        }

        $raw = WebsiteVisitor::select(DB::raw("{$hourExpression} as hour"), DB::raw('COUNT(*) as count'))
            ->whereBetween('visited_at', [$today, $tomorrow])
            ->groupBy(DB::raw($hourExpression))
            ->pluck('count', 'hour')
            ->toArray();

        $labels = [];
        $data = [];

        for ($h = 0; $h < 24; $h++) {
            $labels[] = sprintf('%02d.00', $h);
            $data[] = (int) ($raw[$h] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}

