<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
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

        $request->validate([
            'title' => 'nullable|string|max:200',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'photos.*.image' => 'File harus berupa foto gambar (JPG, PNG, WEBP).',
            'photos.*.max' => 'Ukuran foto maksimal 10MB per file.',
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store("gallery/album-{$album->id}", 'public');
                GalleryPhoto::create([
                    'gallery_album_id' => $album->id,
                    'title' => $request->title ?? "Foto {$album->name}",
                    'image' => $path,
                    'description' => $request->description ?? null,
                ]);
            }
        }

        ActivityLog::record('create', 'Galeri Foto', "Mengunggah foto ke album '{$album->name}'");

        return back()->with('success', 'Foto berhasil diunggah ke album.');
    }

    public function destroy($id)
    {
        $photo = GalleryPhoto::findOrFail($id);

        if ($photo->image && $photo->image !== 'assets/images/placeholder-gallery.jpg' && Storage::disk('public')->exists($photo->image)) {
            Storage::disk('public')->delete($photo->image);
        }

        $photo->delete();

        ActivityLog::record('delete', 'Galeri Foto', "Menghapus foto ID #{$id}");

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
