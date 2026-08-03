<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('services')->orderBy('order', 'asc')->get();
        return view('cms.service-category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200|unique:service_categories,name',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ], [
            'name.required' => 'Nama kategori/bidang layanan wajib diisi.',
            'name.unique' => 'Nama kategori/bidang sudah ada.',
        ]);

        $category = ServiceCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => 'assets/images/service-cat-icon.png',
            'order' => $request->order ?? 0,
        ]);

        ActivityLog::record('create', 'Kategori Layanan', "Menambahkan bidang layanan: {$category->name}");

        return back()->with('success', 'Kategori/bidang layanan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:200|unique:service_categories,name,' . $id,
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->order = $request->order ?? 0;
        $category->save();

        ActivityLog::record('update', 'Kategori Layanan', "Mengubah bidang layanan ID #{$category->id}: {$category->name}");

        return back()->with('success', 'Kategori/bidang layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = ServiceCategory::withCount('services')->findOrFail($id);

        if ($category->services_count > 0) {
            return back()->with('error', "Kategori '{$category->name}' tidak bisa dihapus karena masih memiliki {$category->services_count} layanan aktif. Hapus atau pindahkan layanan terkait terlebih dahulu.");
        }

        $name = $category->name;
        $category->delete();

        ActivityLog::record('delete', 'Kategori Layanan', "Menghapus bidang layanan: {$name}");

        return back()->with('success', 'Kategori/bidang layanan berhasil dihapus.');
    }
}
