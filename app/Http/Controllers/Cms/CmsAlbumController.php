<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsAlbumController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::withCount('photos')->orderBy('created_at', 'desc')->get();
        return view('cms.gallery.album', compact('albums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('gallery/covers', 'public');
        }

        $album = GalleryAlbum::create([
            'name' => $request->name,
            'description' => $request->description,
            'cover' => $coverPath ?? 'assets/images/placeholder-gallery.jpg',
        ]);

        ActivityLog::record('create', 'Galeri Album', "Menambahkan album foto: {$album->name}");

        return back()->with('success', 'Album foto berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $album = GalleryAlbum::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        if ($request->hasFile('cover')) {
            if ($album->cover && $album->cover !== 'assets/images/placeholder-gallery.jpg' && Storage::disk('public')->exists($album->cover)) {
                Storage::disk('public')->delete($album->cover);
            }
            $album->cover = $request->file('cover')->store('gallery/covers', 'public');
        }

        $album->name = $request->name;
        $album->description = $request->description;
        $album->save();

        ActivityLog::record('update', 'Galeri Album', "Mengubah album ID #{$album->id}: {$album->name}");

        return back()->with('success', 'Album foto berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $album = GalleryAlbum::with('photos')->findOrFail($id);
        $name = $album->name;

        foreach ($album->photos as $photo) {
            if ($photo->image && $photo->image !== 'assets/images/placeholder-gallery.jpg' && Storage::disk('public')->exists($photo->image)) {
                Storage::disk('public')->delete($photo->image);
            }
        }

        if ($album->cover && $album->cover !== 'assets/images/placeholder-gallery.jpg' && Storage::disk('public')->exists($album->cover)) {
            Storage::disk('public')->delete($album->cover);
        }

        $album->delete();

        ActivityLog::record('delete', 'Galeri Album', "Menghapus album foto beserta aksinya: {$name}");

        return back()->with('success', 'Album foto beserta foto didalamnya berhasil dihapus.');
    }
}
