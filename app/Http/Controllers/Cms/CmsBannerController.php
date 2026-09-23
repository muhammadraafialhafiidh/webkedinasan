<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Banner;
use App\Models\News;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CmsBannerController extends Controller
{
    public function index()
    {
        $banners = Banner::with(['news', 'service'])->orderBy('order', 'asc')->get();
        $newsList = News::published()->orderBy('title', 'asc')->get(['id', 'title', 'slug', 'thumbnail']);
        $serviceList = Service::active()->orderBy('title', 'asc')->get(['id', 'title', 'slug', 'icon']);

        return view('cms.banner.index', compact('banners', 'newsList', 'serviceList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:200',
            'link_type' => 'required|in:none,berita,pelayanan,external',
            'news_id' => 'required_if:link_type,berita|nullable|exists:news,id',
            'service_id' => 'required_if:link_type,pelayanan|nullable|exists:services,id',
            'external_url' => 'required_if:link_type,external|nullable|url|max:500',
            'image_source' => 'required|in:content,custom',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'is_active' => 'required|boolean',
        ], [
            'link_type.required' => 'Tautan tujuan wajib dipilih.',
            'news_id.required_if' => 'Berita tujuan wajib dipilih jika tautan bertipe Berita.',
            'news_id.exists' => 'Berita yang dipilih tidak valid atau tidak ditemukan.',
            'service_id.required_if' => 'Pelayanan tujuan wajib dipilih jika tautan bertipe Pelayanan.',
            'service_id.exists' => 'Pelayanan yang dipilih tidak valid atau tidak ditemukan.',
            'external_url.required_if' => 'URL eksternal wajib diisi jika tautan bertipe Link Eksternal.',
            'external_url.url' => 'Format URL eksternal tidak valid (harus menyertakan http:// atau https://).',
            'image.max' => 'Ukuran gambar banner maksimal 10MB.',
            'image.image' => 'File harus berupa gambar yang valid (jpeg, png, jpg, webp).',
            'is_active.required' => 'Status aktif banner wajib dipilih.',
            'is_active.boolean' => 'Status aktif banner harus bernilai valid (aktif/nonaktif).',
        ]);

        $linkType = $request->link_type;
        $newsId = ($linkType === 'berita') ? $request->news_id : null;
        $serviceId = ($linkType === 'pelayanan') ? $request->service_id : null;
        $externalUrl = ($linkType === 'external') ? $request->external_url : null;
        
        // Aturan image_source: external dan none selalu custom
        $imageSource = in_array($linkType, ['berita', 'pelayanan']) ? $request->image_source : 'custom';

        $imagePath = null;
        if ($imageSource === 'content') {
            if ($linkType === 'berita') {
                $news = News::find($newsId);
                if (!$news || !$news->thumbnail) {
                    return back()->withErrors(['image_source' => 'Berita yang dipilih tidak memiliki gambar/thumbnail. Silakan pilih opsi "Upload gambar banner khusus".'])->withInput();
                }
            } elseif ($linkType === 'pelayanan') {
                $service = Service::find($serviceId);
                if (!$service || !$service->icon) {
                    return back()->withErrors(['image_source' => 'Pelayanan yang dipilih tidak memiliki icon/gambar. Silakan pilih opsi "Upload gambar banner khusus".'])->withInput();
                }
            }
        } else {
            // custom image source
            if (!$request->hasFile('image')) {
                return back()->withErrors(['image' => 'Gambar banner wajib diunggah ketika menggunakan gambar banner khusus.'])->withInput();
            }
            $imagePath = $request->file('image')->store('banners', 'public');
        }

        $banner = DB::transaction(function () use ($request, $linkType, $newsId, $serviceId, $externalUrl, $imageSource, $imagePath) {
            // Geser seluruh urutan banner lama ke bawah (+1)
            Banner::query()->increment('order');

            // Simpan banner baru di posisi paling atas (order = 0)
            return Banner::create([
                'title' => $request->title,
                'link_type' => $linkType,
                'news_id' => $newsId,
                'service_id' => $serviceId,
                'external_url' => $externalUrl,
                'image_source' => $imageSource,
                'image' => $imagePath,
                'order' => 0,
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        ActivityLog::record('create_banner', 'Banner & Slider', "Menambahkan banner baru: " . ($banner->title ?? 'Banner #' . $banner->id));

        return redirect()->route('cms.banner.index')->with('success', 'Banner slider berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        // Normalisasi edit_is_active jika terkirim dari form lama / browser cache
        if (!$request->has('is_active') && $request->has('edit_is_active')) {
            $request->merge(['is_active' => $request->input('edit_is_active')]);
        }

        $request->validate([
            'title' => 'nullable|string|max:200',
            'link_type' => 'required|in:none,berita,pelayanan,external',
            'news_id' => 'required_if:link_type,berita|nullable|exists:news,id',
            'service_id' => 'required_if:link_type,pelayanan|nullable|exists:services,id',
            'external_url' => 'required_if:link_type,external|nullable|url|max:500',
            'image_source' => 'required|in:content,custom',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'is_active' => 'required|boolean',
        ], [
            'link_type.required' => 'Tautan tujuan wajib dipilih.',
            'news_id.required_if' => 'Berita tujuan wajib dipilih jika tautan bertipe Berita.',
            'news_id.exists' => 'Berita yang dipilih tidak valid atau tidak ditemukan.',
            'service_id.required_if' => 'Pelayanan tujuan wajib dipilih jika tautan bertipe Pelayanan.',
            'service_id.exists' => 'Pelayanan yang dipilih tidak valid atau tidak ditemukan.',
            'external_url.required_if' => 'URL eksternal wajib diisi jika tautan bertipe Link Eksternal.',
            'external_url.url' => 'Format URL eksternal tidak valid (harus menyertakan http:// atau https://).',
            'image.max' => 'Ukuran gambar banner maksimal 10MB.',
            'image.image' => 'File harus berupa gambar yang valid (jpeg, png, jpg, webp).',
            'is_active.required' => 'Status aktif banner wajib dipilih.',
            'is_active.boolean' => 'Status aktif banner harus bernilai valid (aktif/nonaktif).',
        ]);

        $linkType = $request->link_type;
        $newsId = ($linkType === 'berita') ? $request->news_id : null;
        $serviceId = ($linkType === 'pelayanan') ? $request->service_id : null;
        $externalUrl = ($linkType === 'external') ? $request->external_url : null;
        
        $imageSource = in_array($linkType, ['berita', 'pelayanan']) ? $request->image_source : 'custom';

        $oldImageToDelete = null;
        $newImagePath = $banner->image;

        if ($imageSource === 'content') {
            if ($linkType === 'berita') {
                $news = News::find($newsId);
                if (!$news || !$news->thumbnail) {
                    return back()->withErrors(['image_source' => 'Berita yang dipilih tidak memiliki gambar/thumbnail. Silakan pilih opsi "Upload gambar banner khusus".'])->withInput();
                }
            } elseif ($linkType === 'pelayanan') {
                $service = Service::find($serviceId);
                if (!$service || !$service->icon) {
                    return back()->withErrors(['image_source' => 'Pelayanan yang dipilih tidak memiliki icon/gambar. Silakan pilih opsi "Upload gambar banner khusus".'])->withInput();
                }
            }
            // Jika beralih ke content, jadwalkan penghapusan file custom lama jika ada
            if ($banner->image && str_starts_with($banner->image, 'banners/')) {
                $oldImageToDelete = $banner->image;
            }
            $newImagePath = null;
        } else {
            // custom image source
            if ($request->hasFile('image')) {
                if ($banner->image && str_starts_with($banner->image, 'banners/')) {
                    $oldImageToDelete = $banner->image;
                }
                $newImagePath = $request->file('image')->store('banners', 'public');
            } elseif (!$banner->image) {
                // Banner sebelumnya tidak punya custom image dan sekarang memilih custom tanpa upload file
                return back()->withErrors(['image' => 'File gambar banner wajib diunggah jika beralih ke gambar khusus.'])->withInput();
            }
        }

        $banner->title = $request->title;
        $banner->link_type = $linkType;
        $banner->news_id = $newsId;
        $banner->service_id = $serviceId;
        $banner->external_url = $externalUrl;
        $banner->image_source = $imageSource;
        $banner->image = $newImagePath;
        // Posisi order dipertahankan tanpa perubahan saat edit
        $banner->is_active = $request->boolean('is_active');
        $banner->save();

        // Hapus file lama hanya setelah database berhasil diperbarui
        if ($oldImageToDelete && Storage::disk('public')->exists($oldImageToDelete)) {
            Storage::disk('public')->delete($oldImageToDelete);
        }

        ActivityLog::record('update', 'Banner', "Mengubah banner ID #{$banner->id}");

        return back()->with('success', 'Banner slider berhasil diperbarui.');
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
        $imagePath = $banner->image;

        DB::transaction(function () use ($banner) {
            $banner->delete();

            // Rapikan urutan banner yang tersisa agar tidak ada gap (0, 1, 2, ...)
            $banners = Banner::orderBy('order', 'asc')->get();
            foreach ($banners as $index => $b) {
                if ($b->order !== $index) {
                    $b->update(['order' => $index]);
                }
            }
        });

        if ($imagePath && str_starts_with($imagePath, 'banners/') && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        ActivityLog::record('delete', 'Banner', "Menghapus banner ID #{$id}");

        return back()->with('success', 'Banner berhasil dihapus.');
    }
}
