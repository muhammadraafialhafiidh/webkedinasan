<?php

namespace App\Http\Middleware;

use App\Models\WebsiteVisitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        // 1. Pengecualian CMS berdasarkan Route Name (Paling Aman)
        $isCmsRoute = $route && $route->named('cms.*');

        // 2. Pengecualian CMS berdasarkan URL (Fallback)
        $isCmsPath = $request->is('cms*') 
                  || $request->is('admin*') 
                  || $request->is('login*') 
                  || $request->is('logout*')
                  || $request->is('lupa-password*')
                  || $request->is('reset-password*');

        // 3. Pengecualian Assets dan Files
        $rawPath = strtolower($request->path());
        $isAsset = str_contains($rawPath, 'storage/') 
                || str_contains($rawPath, 'assets/') 
                || str_contains($rawPath, 'build/');
                
        // Mencegah file statis (misal 404 dari tag img/script) tercatat sebagai visitor
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|webp|woff|woff2|ttf|eot|mp4|webm|pdf|zip)$/i', $rawPath)) {
            $isAsset = true;
        }

        // 4. Pengecualian AJAX / API / Prefetch
        $isAjax = $request->ajax() || $request->prefetch() || $request->wantsJson();

        // Hanya track request tipe GET yang valid untuk Publik
        if ($request->isMethod('GET') && !$isCmsRoute && !$isCmsPath && !$isAsset && !$isAjax) {
            $this->track($request);
        }

        return $next($request);
    }

    private function track(Request $request): void
    {
        Log::info('[TrackVisitor] Middleware dijalankan untuk path: ' . $request->path());

        try {
            $ip = $request->ip();
            $userAgent = $request->userAgent() ?? '';
            $sessionId = ($request->hasSession() && !empty($request->session()->getId())) ? $request->session()->getId() : null;
            $urlPath = '/' . ltrim($request->path(), '/');
            $pageName = $this->resolvePageName($request);
            $now = now();

            $lockKey = 'visitor_lock_' . md5($ip . $userAgent);

            // Mencegah race condition dari concurrent requests (e.g. assets, rapid refresh)
            Cache::lock($lockKey, 10)->block(5, function () use ($request, $ip, $userAgent, $sessionId, $urlPath, $pageName, $now) {
                // STEP 1 - SESSION ID
                $existingVisitor = null;

                if ($request->hasSession() && $request->session()->has('visitor_tracked_id')) {
                    $trackedId = $request->session()->get('visitor_tracked_id');
                    $existingVisitor = WebsiteVisitor::find($trackedId);
                }

                if (!$existingVisitor && !empty($sessionId)) {
                    $existingVisitor = WebsiteVisitor::where('session_id', $sessionId)
                        ->where('last_activity', '>=', $now->copy()->subMinutes(30))
                        ->latest('id')
                        ->first();
                }

                // STEP 2 - FALLBACK IP + USER AGENT (hanya jika request tidak memiliki session_id)
                if (!$existingVisitor && empty($sessionId)) {
                    $existingVisitor = WebsiteVisitor::where('ip_address', $ip)
                        ->where('user_agent', $userAgent)
                        ->where('last_activity', '>=', $now->copy()->subMinutes(30))
                        ->latest('id')
                        ->first();
                }

                // Hitung selisih menit inaktivitas dari last_activity
                $lastActivity = $existingVisitor ? ($existingVisitor->last_activity ?? $existingVisitor->visited_at) : null;
                $selisihMenit = $lastActivity ? (int) $lastActivity->diffInMinutes($now) : null;

                // Evaluasi Sesi Visit (Ambang batas 30 menit inaktivitas)
                $sessionActive = ($existingVisitor !== null && $selisihMenit !== null && $selisihMenit <= 30);
                $sessionExpired = ($existingVisitor !== null && ($selisihMenit === null || $selisihMenit > 30));

                if ($sessionActive) {
                    // Visit Aktif (Selisih <= 30 menit) -> Update last_activity tanpa membuat Visitor baru
                    $existingVisitor->update([
                        'last_activity' => $now,
                    ]);

                    // Catat Pageview baru (tidak menimpa yang lama)
                    $existingVisitor->pageviews()->create([
                        'url' => \Illuminate\Support\Str::limit($urlPath, 255),
                        'page_name' => \Illuminate\Support\Str::limit($pageName, 255),
                        'visited_at' => $now,
                    ]);

                    if ($request->hasSession()) {
                        $request->session()->put('visitor_tracked_id', $existingVisitor->id);
                    }

                    $alasan = 'Visit masih aktif (selisih ' . $selisihMenit . ' menit <= 30 menit). Waktu aktivitas diperbarui, Pageview ditambah.';

                    Log::info('[VisitorTracking] Processed Visit Request', [
                        'session_id' => $sessionId,
                        'visitor_id' => $existingVisitor->id,
                        'last_activity' => $now->toDateTimeString(),
                        'selisih_menit' => $selisihMenit,
                        'session_active' => true,
                        'session_expired' => false,
                        'visit_baru' => false,
                        'visitor_bertambah' => false,
                        'alasan' => $alasan,
                    ]);
                } else {
                    // STEP 3 - VISITOR BARU
                    $browser = $this->parseBrowser($request);
                    $device = $this->parseDevice($userAgent);
                    $os = $this->parseOs($userAgent);

                    $newVisitor = WebsiteVisitor::create([
                        'ip_address' => $ip,
                        'user_agent' => $userAgent,
                        'session_id' => $sessionId,
                        'browser' => $browser,
                        'device' => $device,
                        'operating_system' => $os,
                        'url' => \Illuminate\Support\Str::limit($urlPath, 255),
                        'page_name' => \Illuminate\Support\Str::limit($pageName, 255),
                        'referer' => $request->header('referer'),
                        'visited_at' => $now,
                        'last_activity' => $now,
                    ]);

                    // Catat Pageview pertama untuk Visit ini
                    $newVisitor->pageviews()->create([
                        'url' => \Illuminate\Support\Str::limit($urlPath, 255),
                        'page_name' => \Illuminate\Support\Str::limit($pageName, 255),
                        'visited_at' => $now,
                    ]);

                    if ($request->hasSession()) {
                        $request->session()->put('visitor_tracked_id', $newVisitor->id);
                    }

                    $alasan = $existingVisitor
                        ? 'Visit sebelumnya telah berakhir (inaktif ' . $selisihMenit . ' menit > 30 menit). Membuat Visit baru dan Pageview.'
                        : 'Belum ada Visit aktif (Pengunjung Baru / Session Baru / Fallback tidak ditemukan). Membuat Visit baru dan Pageview.';

                    Log::info('[VisitorTracking] Processed Visit Request', [
                        'session_id' => $sessionId,
                        'visitor_id' => $newVisitor->id,
                        'last_activity' => $now->toDateTimeString(),
                        'selisih_menit' => $selisihMenit,
                        'session_active' => false,
                        'session_expired' => $sessionExpired,
                        'visit_baru' => true,
                        'visitor_bertambah' => true,
                        'alasan' => $alasan,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            Log::error('[TrackVisitor] Gagal memproses tracking visitor: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    private function parseBrowser(Request $request): string
    {
        $userAgent = $request->userAgent() ?? '';

        // Prioritas 1: Client Hints
        $chUa = $request->header('sec-ch-ua');
        if ($chUa) {
            if (preg_match('/Brave/i', $chUa)) {
                return 'Lainnya';
            }
            if (preg_match('/Opera|OPR/i', $chUa)) {
                return 'Lainnya';
            }
            if (preg_match('/Edg/i', $chUa)) {
                return 'Edge';
            }
        }

        // Prioritas 2: User-Agent fallback
        if (preg_match('/edg|edge|edga|edgios/i', $userAgent)) {
            return 'Edge';
        }
        if (preg_match('/firefox|fxios/i', $userAgent)) {
            return 'Firefox';
        }
        if (preg_match('/opera|opr/i', $userAgent)) {
            return 'Lainnya';
        }
        if (preg_match('/brave/i', $userAgent)) {
            return 'Lainnya';
        }
        if (preg_match('/samsungbrowser/i', $userAgent)) {
            return 'Lainnya';
        }
        if (preg_match('/chrome|crios/i', $userAgent)) {
            return 'Chrome';
        }
        if (preg_match('/safari/i', $userAgent)) {
            return 'Safari';
        }

        return 'Lainnya';
    }

    private function parseDevice(string $ua): string
    {
        if (preg_match('/tablet|ipad|playbook|silk/i', $ua)) {
            return 'Tablet';
        }
        if (preg_match('/mobile|iphone|ipod|android|blackberry|opera mini|windows phone/i', $ua)) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    private function parseOs(string $ua): string
    {
        if (preg_match('/windows/i', $ua)) {
            return 'Windows';
        }
        if (preg_match('/android/i', $ua)) {
            return 'Android';
        }
        if (preg_match('/iphone|ipad|ipod/i', $ua)) {
            return 'iOS';
        }
        if (preg_match('/macintosh|mac os x/i', $ua)) {
            return 'macOS';
        }
        if (preg_match('/linux/i', $ua)) {
            return 'Linux';
        }

        return 'Lainnya';
    }

    private function resolvePageName(Request $request): string
    {
        $path = trim($request->path(), '/');

        if ($path === '' || $path === '/') {
            return 'Beranda';
        }

        if ($path === 'profil') {
            return 'Profil Dinas';
        }

        if ($path === 'berita') {
            return 'Berita & Informasi';
        }

        if (str_starts_with($path, 'berita/')) {
            return 'Detail Berita';
        }

        if ($path === 'layanan') {
            return 'Standar Pelayanan';
        }

        if (str_starts_with($path, 'layanan/kategori/')) {
            return 'Kategori Layanan';
        }

        if (str_starts_with($path, 'layanan/')) {
            return 'Detail Layanan';
        }

        if ($path === 'galeri/foto' || str_starts_with($path, 'galeri/foto/')) {
            return 'Galeri Foto';
        }

        if ($path === 'galeri/video' || str_starts_with($path, 'galeri/video/')) {
            return 'Galeri Video';
        }

        if (str_starts_with($path, 'galeri')) {
            return 'Galeri';
        }

        if ($path === 'dokumen') {
            return 'Dokumen Publik';
        }

        if ($path === 'kontak') {
            return 'Kontak Kami';
        }

        return ucwords(str_replace(['-', '_', '/'], ' ', $path));
    }
}
