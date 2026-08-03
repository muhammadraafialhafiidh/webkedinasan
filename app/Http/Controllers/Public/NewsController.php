<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $categories = NewsCategory::withCount(['news' => function ($query) {
            $query->published();
        }])->get();

        $query = News::with(['newsCategory', 'author'])->published();

        $breadcrumbs = [
            ['label' => 'Berita', 'url' => $request->filled('kategori') ? route('news.index') : null],
        ];

        if ($request->filled('kategori')) {
            $query->whereHas('newsCategory', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });

            $selectedCategory = $categories->firstWhere('slug', $request->kategori);
            if ($selectedCategory) {
                $breadcrumbs[] = ['label' => $selectedCategory->name, 'url' => null];
            }
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $newsList = $query->orderBy('published_at', 'desc')->paginate(10)->withQueryString();
        $popularNews = News::published()->orderBy('published_at', 'desc')->take(5)->get();

        return view('public.news.index', compact('newsList', 'categories', 'popularNews', 'breadcrumbs'));
    }

    public function show(string $slug)
    {
        $news = News::with(['newsCategory', 'author'])->published()->where('slug', $slug)->firstOrFail();
        $news->increment('views_count');
        $relatedNews = News::with('newsCategory')
            ->published()
            ->where('news_category_id', $news->news_category_id)
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        $breadcrumbs = [
            ['label' => 'Berita', 'url' => route('news.index')],
            ['label' => $news->title, 'url' => null],
        ];

        return view('public.news.show', compact('news', 'relatedNews', 'breadcrumbs'));
    }
}
