<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;

class GalleryPhotoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('album')) {
            return $this->show($request->input('album'));
        }

        $albums = GalleryAlbum::withCount('photos')
            ->with('photos')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $breadcrumbs = [
            ['label' => 'Galeri Foto', 'url' => null],
        ];

        return view('public.gallery.photo', compact('albums', 'breadcrumbs'));
    }

    public function show($id)
    {
        $album = GalleryAlbum::with('photos')->findOrFail($id);

        $otherAlbums = GalleryAlbum::withCount('photos')
            ->with('photos')
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $breadcrumbs = [
            ['label' => 'Galeri', 'url' => route('gallery.photo')],
            ['label' => $album->name, 'url' => null],
        ];

        return view('public.gallery.photo-show', compact('album', 'otherAlbums', 'breadcrumbs'));
    }
}
