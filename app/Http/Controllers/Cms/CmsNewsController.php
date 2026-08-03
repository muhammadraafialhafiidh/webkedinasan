<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CmsNewsController extends Controller
{
    public function index(Request $request)
    {
        $categories = NewsCategory::all();
        $query = News::with(['newsCategory', 'author']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('news_category_id', $request->kategori);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $newsList = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('cms.news.index', compact('newsList', 'categories'));
    }

    public function create()
    {
        $categories = NewsCategory::all();
        return view('cms.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'news_category_id' => 'required|exists:news_categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ], [
            'title.required' => 'Judul berita wajib diisi.',
            'news_category_id.required' => 'Kategori berita wajib dipilih.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 5MB.',
            'content.required' => 'Konten berita wajib diisi.',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $news = News::create([
            'news_category_id' => $request->news_category_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath ?? 'assets/images/placeholder-news.jpg',
            'content' => $request->content,
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        ActivityLog::record('create', 'Berita', "Menambahkan berita baru: {$news->title} ({$news->status})");

        return redirect()->route('cms.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = NewsCategory::all();
        return view('cms.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'news_category_id' => 'required|exists:news_categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail && $news->thumbnail !== 'assets/images/placeholder-news.jpg' && Storage::disk('public')->exists($news->thumbnail)) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $news->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($news->title !== $request->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;
            while (News::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
            $news->slug = $slug;
        }

        $news->title = $request->title;
        $news->news_category_id = $request->news_category_id;
        $news->content = $request->content;

        if ($news->status !== 'published' && $request->status === 'published') {
            $news->published_at = now();
        }

        $news->status = $request->status;
        $news->save();

        ActivityLog::record('update', 'Berita', "Mengubah berita ID #{$news->id}: {$news->title}");

        return redirect()->route('cms.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $title = $news->title;

        if ($news->thumbnail && $news->thumbnail !== 'assets/images/placeholder-news.jpg' && Storage::disk('public')->exists($news->thumbnail)) {
            Storage::disk('public')->delete($news->thumbnail);
        }

        $news->delete();

        ActivityLog::record('delete', 'Berita', "Menghapus berita: {$title}");

        return redirect()->route('cms.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $news = News::findOrFail($id);
        $news->status = $news->status === 'published' ? 'draft' : 'published';
        if ($news->status === 'published' && !$news->published_at) {
            $news->published_at = now();
        }
        $news->save();

        ActivityLog::record('toggle_status', 'Berita', "Mengubah status berita '{$news->title}' menjadi {$news->status}");

        return back()->with('success', "Status berita '{$news->title}' berhasil diubah menjadi " . strtoupper($news->status));
    }
}
