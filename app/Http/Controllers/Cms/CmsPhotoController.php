<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Services\MediaSourceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsPhotoController extends Controller
{
    public function showAlbumPhotos($albumId)
    {
        $album = GalleryAlbum::with('photos')->findOrFail($albumId);
        return view('cms.gallery.photo', compact('album'));
    }

    public function store(Request $request, $albumId)
    {
        $album = GalleryAlbum::findOrFail($albumId);
        $sourceType = $request->input('source_type', 'upload');

        if ($sourceType === 'instagram') {
            $request->validate([
                'title' => 'nullable|string|max:200',
                'instagram_url' => 'required|url|max:500',
                'description' => 'nullable|string',
            ], [
                'instagram_url.required' => 'URL postingan Instagram wajib diisi.',
                'instagram_url.url' => 'Format URL Instagram tidak valid.',
            ]);

            if (!MediaSourceService::isValidInstagramUrl($request->instagram_url)) {
                return back()->withErrors(['instagram_url' => 'URL postingan Instagram tidak valid.'])->withInput();
            }

            if (GalleryPhoto::where('gallery_album_id', $album->id)->where('external_url', $request->instagram_url)->exists()) {
                return back()->withErrors(['instagram_url' => 'URL postingan Instagram ini sudah ada di dalam album ini.'])->withInput();
            }

            GalleryPhoto::create([
                'gallery_album_id' => $album->id,
                'title' => $request->title ?? "Foto {$album->name}",
                'source_type' => 'instagram',
                'image' => null,
                'external_url' => $request->instagram_url,
                'description' => $request->description ?? null,
            ]);

            ActivityLog::record('create', 'Galeri Foto', "Menambahkan foto Instagram ke album '{$album->name}'");

            return back()->with('success', 'Foto Instagram berhasil ditambahkan ke album.');
        }

        // Default: Upload Foto Lokal (Mekanisme Existing)
        $request->validate([
            'title' => 'nullable|string|max:200',
            'photos' => 'required|array',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'photos.required' => 'Pilih setidaknya satu berkas foto.',
            'photos.*.image' => 'File harus berupa foto gambar (JPG, PNG, WEBP).',
            'photos.*.max' => 'Ukuran foto maksimal 10MB per file.',
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store("gallery/album-{$album->id}", 'public');
                GalleryPhoto::create([
                    'gallery_album_id' => $album->id,
                    'title' => $request->title ?? "Foto {$album->name}",
                    'source_type' => 'upload',
                    'image' => $path,
                    'external_url' => null,
                    'description' => $request->description ?? null,
                ]);
            }
        }

        ActivityLog::record('create', 'Galeri Foto', "Mengunggah foto ke album '{$album->name}'");

        return back()->with('success', 'Foto berhasil diunggah ke album.');
    }

    public function update(Request $request, $id)
    {
        $photo = GalleryPhoto::findOrFail($id);
        $sourceType = $request->input('source_type', $photo->source_type ?? 'upload');

        $rules = [
            'title' => 'nullable|string|max:200',
            'source_type' => 'required|in:upload,instagram',
            'description' => 'nullable|string',
        ];

        $oldImageToDelete = null;

        if ($sourceType === 'instagram') {
            $rules['instagram_url'] = 'required|url|max:500';
            $request->validate($rules, [
                'instagram_url.required' => 'URL postingan Instagram wajib diisi.',
                'instagram_url.url' => 'Format URL Instagram tidak valid.',
            ]);

            if (!MediaSourceService::isValidInstagramUrl($request->instagram_url)) {
                return back()->withErrors(['instagram_url' => 'URL postingan Instagram tidak valid.'])->withInput();
            }

            if (GalleryPhoto::where('gallery_album_id', $photo->gallery_album_id)->where('external_url', $request->instagram_url)->where('id', '!=', $photo->id)->exists()) {
                return back()->withErrors(['instagram_url' => 'URL postingan Instagram ini sudah digunakan pada foto lain di album ini.'])->withInput();
            }

            // Jika sebelumnya upload lokal, simpan path untuk dibersihkan setelah save
            if (($photo->source_type ?? 'upload') === 'upload' && $photo->image) {
                $oldImageToDelete = $photo->image;
                $photo->image = null;
            }

            $photo->external_url = $request->instagram_url;
        } else {
            // source_type === 'upload'
            if ($photo->source_type === 'instagram' && empty($photo->image)) {
                // Wajib upload file jika beralih dari Instagram ke upload
                $rules['photo'] = 'required|image|mimes:jpeg,png,jpg,webp|max:10240';
            } else {
                // Opsional upload ulang jika sudah ada foto sebelumnya
                $rules['photo'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240';
            }

            $request->validate($rules, [
                'photo.required' => 'File foto wajib diunggah ketika memilih sumber Upload.',
                'photo.image' => 'File harus berupa gambar (JPG, PNG, WEBP).',
                'photo.max' => 'Ukuran foto maksimal 10MB.',
            ]);

            if ($request->hasFile('photo')) {
                if ($photo->image && $photo->image !== 'assets/images/placeholder-gallery.jpg' && Storage::disk('public')->exists($photo->image)) {
                    $oldImageToDelete = $photo->image;
                }
                $photo->image = $request->file('photo')->store("gallery/album-{$photo->gallery_album_id}", 'public');
            }

            $photo->external_url = null;
        }

        $photo->title = $request->title ?? $photo->title;
        $photo->source_type = $sourceType;
        $photo->description = $request->description;
        $photo->save();

        if ($oldImageToDelete && $oldImageToDelete !== 'assets/images/placeholder-gallery.jpg' && Storage::disk('public')->exists($oldImageToDelete)) {
            Storage::disk('public')->delete($oldImageToDelete);
        }

        ActivityLog::record('update', 'Galeri Foto', "Mengubah foto ID #{$photo->id} di album '{$photo->album->name}'");

        return back()->with('success', 'Data foto berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $photo = GalleryPhoto::findOrFail($id);

        if (($photo->source_type ?? 'upload') === 'upload' && $photo->image && $photo->image !== 'assets/images/placeholder-gallery.jpg' && Storage::disk('public')->exists($photo->image)) {
            Storage::disk('public')->delete($photo->image);
        }

        $photo->delete();

        ActivityLog::record('delete', 'Galeri Foto', "Menghapus foto ID #{$id}");

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
