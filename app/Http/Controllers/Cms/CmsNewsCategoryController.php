<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsNewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::withCount('news')->orderBy('name', 'asc')->get();
        return view('cms.news-category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:news_categories,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ]);

        $category = NewsCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        ActivityLog::record('create', 'Kategori Berita', "Menambahkan kategori berita: {$category->name}");

        return back()->with('success', 'Kategori berita berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = NewsCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:news_categories,name,' . $id,
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();

        ActivityLog::record('update', 'Kategori Berita', "Mengubah kategori berita ID #{$category->id}: {$category->name}");

        return back()->with('success', 'Kategori berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = NewsCategory::withCount('news')->findOrFail($id);

        if ($category->news_count > 0) {
            return back()->with('error', "Kategori '{$category->name}' tidak bisa dihapus karena masih digunakan oleh {$category->news_count} berita.");
        }

        $name = $category->name;
        $category->delete();

        ActivityLog::record('delete', 'Kategori Berita', "Menghapus kategori berita: {$name}");

        return back()->with('success', 'Kategori berita berhasil dihapus.');
    }
}
