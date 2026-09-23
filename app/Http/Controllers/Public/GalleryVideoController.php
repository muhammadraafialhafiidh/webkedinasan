<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryVideo;

class GalleryVideoController extends Controller
{
    public function index()
    {
        $videos = GalleryVideo::orderBy('created_at', 'desc')->paginate(12);

        $breadcrumbs = [
            ['label' => 'Galeri', 'url' => route('gallery.photo')],
            ['label' => 'Galeri Video', 'url' => null],
        ];

        return view('public.gallery.video', compact('videos', 'breadcrumbs'));
    }
}
