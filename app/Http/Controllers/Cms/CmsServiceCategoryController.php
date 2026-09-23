<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        ], [
            'name.required' => 'Nama kategori/bidang layanan wajib diisi.',
            'name.unique' => 'Nama kategori/bidang sudah ada.',
        ]);

        $category = DB::transaction(function () use ($request) {
            // Geser seluruh kategori lama ke bawah (+1)
            ServiceCategory::query()->increment('order');

            // Simpan kategori baru di posisi paling atas (order = 0)
            return ServiceCategory::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'icon' => 'assets/images/service-cat-icon.png',
                'order' => 0,
            ]);
        });

        ActivityLog::record('create', 'Kategori Layanan', "Menambahkan bidang layanan: {$category->name}");

        return back()->with('success', 'Kategori/bidang layanan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:200|unique:service_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        // Posisi order tetap dipertahankan tanpa perubahan saat edit
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

        DB::transaction(function () use ($category) {
            $category->delete();

            // Rapikan urutan kategori yang tersisa agar tidak ada gap (0, 1, 2, ...)
            $categories = ServiceCategory::orderBy('order', 'asc')->get();
            foreach ($categories as $index => $cat) {
                if ($cat->order !== $index) {
                    $cat->update(['order' => $index]);
                }
            }
        });

        ActivityLog::record('delete', 'Kategori Layanan', "Menghapus bidang layanan: {$name}");

        return back()->with('success', 'Kategori/bidang layanan berhasil dihapus.');
    }
}
