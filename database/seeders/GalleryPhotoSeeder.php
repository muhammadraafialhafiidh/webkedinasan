<?php

namespace Database\Seeders;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;

class GalleryPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $albums = GalleryAlbum::all();

        foreach ($albums as $album) {
            for ($i = 1; $i <= 3; $i++) {
                GalleryPhoto::create([
                    'gallery_album_id' => $album->id,
                    'title' => "Foto Kegiatan {$album->name} #{$i}",
                    'image' => 'assets/images/placeholder-gallery.jpg',
                    'description' => 'Dokumentasi kegiatan dinas perikanan dan pembinaan kelompok pembudidaya.',
                ]);
            }
        }
    }
}
