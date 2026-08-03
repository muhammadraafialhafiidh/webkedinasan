<?php

namespace App\Http\Middleware;

use App\Models\WebsiteVisitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya proses request GET yang berhasil (HTTP 200)
        if (!$request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return $response;
        }

        // Jangan catat jika pengguna sedang mengakses area CMS, admin, login, atau auth
        if ($request->is('cms*') || $request->is('admin*') || $request->is('login*') || $request->is('logout*') || $request->is('lupa-password*') || $request->is('reset-password*')) {
            return $response;
        }

        // Jangan catat request asset, storage, atau debug
        $rawPath = strtolower($request->path());
        if ($request->ajax() || $request->prefetch() || str_contains($rawPath, 'storage/') || str_contains($rawPath, 'assets/') || str_contains($rawPath, 'build/')) {
            return $response;
        }

        Log::info('[TrackVisitor] Middleware dijalankan untuk path: ' . $request->path());

        try {
            $ip = $request->ip();
            $userAgent = $request->userAgent() ?? '';
            $sessionId = ($request->hasSession() && !empty($request->session()->getId())) ? $request->session()->getId() : null;

            // Gunakan path URL relatif konsisten (misal: /layanan, /berita, /profil)
            $urlPath = '/' . ltrim($request->path(), '/');

            // Cooldown check (1 menit per URL path per visitor): Cegah rapid F5 refresh spam pada halaman yang sama
            $recentPageVisit = WebsiteVisitor::where('ip_address', $ip)
                ->where('url', $urlPath)
                ->where(function ($query) use ($sessionId, $userAgent) {
                    if ($sessionId) {
                        $query->where('session_id', $sessionId);
                    } else {
                        $query->where('user_agent', $userAgent);
                    }
                })
                ->where('visited_at', '>=', now()->subMinutes(1))
                ->exists();

            if (!$recentPageVisit) {
                $browser = $this->parseBrowser($userAgent);
                $device = $this->parseDevice($userAgent);
                $os = $this->parseOs($userAgent);

                $visitor = WebsiteVisitor::create([
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'session_id' => $sessionId,
                    'browser' => $browser,
                    'device' => $device,
                    'operating_system' => $os,
                    'url' => $urlPath,
                    'page_name' => $this->resolvePageName($request),
                    'referer' => $request->header('referer'),
                    'visited_at' => now(),
                ]);

                Log::info('[TrackVisitor] Query insert berhasil', [
                    'id' => $visitor->id,
                    'ip' => $ip,
                    'session_id' => $sessionId,
                    'url' => $urlPath,
                ]);
            } else {
                Log::info('[TrackVisitor] Visitor ditolak (cooldown 1 menit aktif)', [
                    'ip' => $ip,
                    'session_id' => $sessionId,
                    'url' => $urlPath,
                ]);
            }
        } catch (\Throwable $e) {
            // Log error silently without crashing user request
            Log::error('[TrackVisitor] Query insert gagal: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        }

        return $response;
    }

    private function parseBrowser(string $ua): string
    {
        if (preg_match('/edg/i', $ua)) {
            return 'Edge';
        }
        if (preg_match('/firefox|fxios/i', $ua)) {
            return 'Firefox';
        }
        if (preg_match('/chrome|crios/i', $ua)) {
            return 'Chrome';
        }
        if (preg_match('/safari/i', $ua)) {
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
