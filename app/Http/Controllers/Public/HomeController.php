<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\GalleryPhoto;
use App\Models\News;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Services\VisitorStatisticService;

class HomeController extends Controller
{
    public function index(VisitorStatisticService $visitorStatisticService)
    {
        $banners = Banner::with(['news', 'service'])->active()->get();
        $latestNews = News::with('newsCategory')->published()->orderBy('published_at', 'desc')->take(6)->get();
        $serviceCategories = ServiceCategory::with(['services' => function ($query) {
            $query->active()->orderBy('order', 'asc');
        }])->orderBy('order', 'asc')->get();
        $latestPhotos = GalleryPhoto::with('album')->orderBy('created_at', 'desc')->take(6)->get();

        $visitorStats = $visitorStatisticService->getPublicSummary();

        return view('public.home.index', compact('banners', 'latestNews', 'serviceCategories', 'latestPhotos', 'visitorStats'));
    }
}
