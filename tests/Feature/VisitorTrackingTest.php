<?php

namespace Tests\Feature;

use App\Models\WebsiteVisitor;
use App\Services\VisitorStatisticService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_skenario_1_browser_a_membuka_website_total_pengunjung_bertambah()
    {
        $service = app(VisitorStatisticService::class);
        $initialSummary = $service->getPublicSummary();

        // Browser A mengunjungi homepage
        $response = $this->withSession(['_token' => 'token_a'])
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0'])
            ->get('/');

        $response->assertStatus(200);

        $newSummary = $service->getPublicSummary();
        $this->assertEquals($initialSummary['total'] + 1, $newSummary['total']);
        $this->assertEquals($initialSummary['hari_ini'] + 1, $newSummary['hari_ini']);
    }

    public function test_skenario_2_browser_incognito_session_baru_total_pengunjung_bertambah()
    {
        $service = app(VisitorStatisticService::class);

        // Session 1 (Browser Normal)
        $this->withSession(['_token' => 'token_normal'])
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 Chrome/120.0.0.0'])
            ->get('/');

        $summaryAfterFirst = $service->getPublicSummary();
        $this->assertEquals(1, $summaryAfterFirst['total']);

        // Session 2 (Browser Incognito - session ID beda, IP sama)
        $this->flushSession();
        $this->withSession(['_token' => 'token_incognito'])
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 Chrome/120.0.0.0'])
            ->get('/');

        $summaryAfterIncognito = $service->getPublicSummary();
        $this->assertEquals(2, $summaryAfterIncognito['total']);
    }

    public function test_skenario_3_browser_edge_total_pengunjung_bertambah()
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

        $summary = $service->getPublicSummary();
        $this->assertEquals(2, $summary['total']);
    }

    public function test_skenario_4_hp_jaringan_berbeda_total_pengunjung_bertambah()
    {
        $service = app(VisitorStatisticService::class);

        // Desktop Local
        $this->withSession(['_token' => 'token_desktop'])
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1', 'HTTP_USER_AGENT' => 'Mozilla/5.0 Chrome/120.0.0.0'])
            ->get('/');

        // HP Mobile pada network lain
        $this->flushSession();
        $this->withSession(['_token' => 'token_mobile'])
            ->withServerVariables(['REMOTE_ADDR' => '180.252.10.20', 'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) Mobile/15E148'])
            ->get('/');

        $summary = $service->getPublicSummary();
        $this->assertEquals(2, $summary['total']);
    }

    public function test_skenario_5_refresh_berkali_kali_session_sama_tidak_bertambah_selama_cooldown()
    {
        $service = app(VisitorStatisticService::class);

        // Kunjungan 1
        $response1 = $this->get('/');
        $this->assertEquals(1, $service->getPublicSummary()['total']);

        // Set default cookie untuk seluruh request berikutnya
        $sessionCookieName = config('session.cookie');
        $cookieValue = $response1->getCookie($sessionCookieName)->getValue();
        $this->defaultCookies = [$sessionCookieName => $cookieValue];

        // Refresh 1 & 2 (Session cookie & IP sama)
        $this->get('/');
        $this->get('/');

        // Total tetap 1 karena masih session & IP yang sama dalam jeda cooldown 1 menit
        $this->assertEquals(1, $service->getPublicSummary()['total']);
        $this->assertEquals(1, WebsiteVisitor::count());
    }

    public function test_grafik_statistik_per_jam_menggunakan_timezone_asia_jakarta()
    {
        $service = app(VisitorStatisticService::class);

        // Buat Kunjungan pada jam 08:30:00 Asia/Jakarta
        $visitTime = \Illuminate\Support\Carbon::create(2026, 8, 3, 8, 30, 0, 'Asia/Jakarta');

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
        ]);

        \Illuminate\Support\Carbon::setTestNow($visitTime);

        $cmsStats = $service->getCmsFullStatistics();
        $hourlyData = $cmsStats['hourlyChart']['data'];

        // Jam 08:00 (index 8) harus bernilai 1
        $this->assertEquals(1, $hourlyData[8]);

        \Illuminate\Support\Carbon::setTestNow();
    }
}
