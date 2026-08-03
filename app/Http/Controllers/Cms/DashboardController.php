<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Contact;
use App\Models\GalleryPhoto;
use App\Models\News;
use App\Services\VisitorStatisticService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(VisitorStatisticService $visitorService)
    {
        $stats = [
            'total_berita' => News::count(),
            'berita_published' => News::published()->count(),
            'pesan_unread' => Contact::where('is_read', false)->count(),
            'total_foto' => GalleryPhoto::count(),
        ];

        $visitorStats = $visitorService->getCmsFullStatistics();
        $recentNews = News::with(['newsCategory', 'author'])->orderBy('created_at', 'desc')->take(5)->get();
        $recentMessages = Contact::where('is_read', false)->orderBy('created_at', 'desc')->take(5)->get();
        $recentLogs = ActivityLog::orderBy('created_at', 'desc')->take(5)->get();

        $user = Auth::user();

        return view('cms.dashboard', compact('stats', 'visitorStats', 'recentNews', 'recentMessages', 'recentLogs', 'user'));
    }
}
