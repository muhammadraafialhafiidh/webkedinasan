<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsBannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order', 'asc')->get();
        return view('cms.banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:200',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'link_url' => 'nullable|url',
            'order' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ], [
            'image.required' => 'Gambar banner wajib diunggah.',
            'image.max' => 'Ukuran gambar banner maksimal 10MB.',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link_url' => $request->link_url,
            'order' => $request->order,
            'is_active' => $request->is_active,
        ]);

        ActivityLog::record('create_banner', 'Banner & Slider', "Menambahkan banner baru: " . ($request->title ?? 'Banner tanpa judul'));

        return redirect()->route('cms.banner.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'link' => 'nullable|url|max:500',
            'order' => 'nullable|integer',
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image && $banner->image !== 'assets/images/placeholder-banner.jpg' && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->title = $request->title;
        $banner->link = $request->link;
        $banner->order = $request->order ?? 0;
        $banner->is_active = $request->is_active;
        $banner->save();

        ActivityLog::record('update', 'Banner', "Mengubah banner ID #{$banner->id}");

        return back()->with('success', 'Banner berhasil diperbarui.');
    }

    public function toggleActive($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        $statusText = $banner->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLog::record('toggle', 'Banner', "Banner ID #{$banner->id} berhasil {$statusText}.");

        return back()->with('success', "Status banner berhasil {$statusText}.");
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image && $banner->image !== 'assets/images/placeholder-banner.jpg' && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        ActivityLog::record('delete', 'Banner', "Menghapus banner ID #{$id}");

        return back()->with('success', 'Banner berhasil dihapus.');
    }
}
