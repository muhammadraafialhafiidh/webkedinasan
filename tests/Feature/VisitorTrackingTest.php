<?php

namespace Tests\Feature;

use App\Models\WebsiteVisitor;
use App\Services\VisitorStatisticService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class VisitorTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_skenario_1_pertama_kali_membuka_website_visitor_bertambah()
    {
        $service = app(VisitorStatisticService::class);

        $response = $this->get('/');
        $response->assertStatus(200);

        $this->assertEquals(1, $response->viewData('visitorStats')['total']);
        $this->assertEquals(1, WebsiteVisitor::count());
        $this->assertEquals(1, $service->getPublicSummary()['total']);
    }

    public function test_skenario_2_refresh_visitor_tetap()
    {
        $service = app(VisitorStatisticService::class);

        $response = $this->get('/');
        $sessionCookieName = config('session.cookie');
        $cookieValue = $response->getCookie($sessionCookieName)->getValue();
        $this->defaultCookies = [$sessionCookieName => $cookieValue];

        // Refresh 10x
        for ($i = 0; $i < 10; $i++) {
            $this->get('/');
        }

        $this->assertEquals(1, WebsiteVisitor::count());
        $this->assertEquals(1, $service->getPublicSummary()['total']);
    }

    public function test_skenario_3_beranda_berita_galeri_visitor_tetap()
    {
        $service = app(VisitorStatisticService::class);

        $pages = ['/', '/berita', '/layanan', '/galeri/foto', '/dokumen', '/kontak'];

        $response = $this->get($pages[0]);
        $sessionCookieName = config('session.cookie');
        $cookieValue = $response->getCookie($sessionCookieName)->getValue();
        $this->defaultCookies = [$sessionCookieName => $cookieValue];

        foreach (array_slice($pages, 1) as $page) {
            $this->get($page);
        }

        $this->assertEquals(1, WebsiteVisitor::count());
        $this->assertEquals(1, $service->getPublicSummary()['total']);
    }

    public function test_skenario_4_membaca_halaman_20_menit_visitor_tetap()
    {
        $service = app(VisitorStatisticService::class);

        $startTime = Carbon::now();
        Carbon::setTestNow($startTime);

        // 08:00 - Masuk halaman Berita
        $response = $this->get('/berita');
        $sessionCookieName = config('session.cookie');
        $cookieValue = $response->getCookie($sessionCookieName)->getValue();
        $this->defaultCookies = [$sessionCookieName => $cookieValue];

        $this->assertEquals(1, WebsiteVisitor::count());

        // 08:20 - Diam membaca selama 20 menit lalu klik Beranda (<= 30 menit)
        Carbon::setTestNow($startTime->copy()->addMinutes(20));
        $this->get('/');

        // Visitor tetap 1
        $this->assertEquals(1, WebsiteVisitor::count());
        $this->assertEquals(1, $service->getPublicSummary()['total']);

        Carbon::setTestNow();
    }

    public function test_skenario_5_inaktivitas_31_menit_membuat_visit_baru()
    {
        $service = app(VisitorStatisticService::class);

        $startTime = Carbon::now();
        Carbon::setTestNow($startTime);

        // 08:00 - Masuk Website
        $response = $this->get('/');
        $sessionCookieName = config('session.cookie');
        $cookieValue = $response->getCookie($sessionCookieName)->getValue();
        $this->defaultCookies = [$sessionCookieName => $cookieValue];

        $this->assertEquals(1, WebsiteVisitor::count());

        // 08:31 - Tidak ada aktivitas selama 31 menit lalu klik halaman lain (> 30 menit)
        Carbon::setTestNow($startTime->copy()->addMinutes(31));
        $this->get('/berita');

        // Visit baru dibuat (Visitor +1 -> Total = 2)
        $this->assertEquals(2, WebsiteVisitor::count());
        $this->assertEquals(2, $service->getPublicSummary()['total']);

        Carbon::setTestNow();
    }

    public function test_skenario_6_browser_incognito_visitor_bertambah()
    {
        $service = app(VisitorStatisticService::class);

        // Browser Normal
        $this->withSession(['_token' => 'token_normal'])
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 Chrome/120.0.0.0'])
            ->get('/');

        $this->assertEquals(1, $service->getPublicSummary()['total']);

        // Browser Incognito (Session ID beda)
        $this->flushSession();
        session()->setId('incognito_session_id_test');
        $this->withSession(['_token' => 'token_incognito'])
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 Chrome/120.0.0.0'])
            ->get('/');

        $this->assertEquals(2, WebsiteVisitor::count());
        $this->assertEquals(2, $service->getPublicSummary()['total']);
    }

    public function test_skenario_7_browser_edge_visitor_bertambah()
    {
        $service = app(VisitorStatisticService::class);

        // Chrome
        $this->withSession(['_token' => 'token_chrome'])
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 Chrome/120.0.0.0'])
            ->get('/');

        // Edge (Browser beda)
        $this->flushSession();
        $this->withSession(['_token' => 'token_edge'])
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 Edg/120.0.0.0'])
            ->get('/');

        $this->assertEquals(2, WebsiteVisitor::count());
        $this->assertEquals(2, $service->getPublicSummary()['total']);
    }

    public function test_skenario_8_hp_android_visitor_bertambah()
    {
        $service = app(VisitorStatisticService::class);

        // Laptop Desktop
        $this->withSession(['_token' => 'token_desktop'])
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 Chrome/120.0.0.0'])
            ->get('/');

        // HP Mobile
        $this->flushSession();
        $this->withSession(['_token' => 'token_mobile'])
            ->withServerVariables(['REMOTE_ADDR' => '180.252.10.20', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; Android 13; SM-G998B) AppleWebKit/537.36 Mobile Safari/537.36'])
            ->get('/');

        $this->assertEquals(2, WebsiteVisitor::count());
        $this->assertEquals(2, $service->getPublicSummary()['total']);
    }

    public function test_grafik_statistik_per_jam_menggunakan_timezone_asia_jakarta()
    {
        $service = app(VisitorStatisticService::class);

        $visitTime = Carbon::create(2026, 8, 3, 8, 30, 0, 'Asia/Jakarta');

        WebsiteVisitor::create([
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0',
            'session_id' => 'sess_tz_test',
            'browser' => 'Chrome',
            'device' => 'Desktop',
            'operating_system' => 'Windows',
            'url' => '/',
            'page_name' => 'Beranda',
            'visited_at' => $visitTime,
            'last_activity' => $visitTime,
        ]);

        Carbon::setTestNow($visitTime);

        $cmsStats = $service->getCmsFullStatistics();
        $hourlyData = $cmsStats['hourlyChart']['data'];

        $this->assertEquals(1, $hourlyData[8]);

        Carbon::setTestNow();
    }
}
