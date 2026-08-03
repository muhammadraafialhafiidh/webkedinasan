<?php

namespace Database\Seeders;

use App\Models\GalleryAlbum;
use Illuminate\Database\Seeder;

class GalleryAlbumSeeder extends Seeder
{
    public function run(): void
    {
        $albums = [
            ['id' => 1, 'name' => 'Kegiatan Budidaya 2024', 'description' => 'Dokumentasi kegiatan budidaya ikan selama tahun 2024'],
            ['id' => 2, 'name' => 'Pelatihan & Sosialisasi', 'description' => 'Foto-foto kegiatan pelatihan dan sosialisasi bersama pokdakan'],
            ['id' => 3, 'name' => 'Kunjungan Lapangan', 'description' => 'Dokumentasi kunjungan ke lokasi sentra perikanan'],
            ['id' => 4, 'name' => 'Panen Raya', 'description' => 'Momen panen raya bersama kelompok pembudidaya'],
        ];

        foreach ($albums as $album) {
            GalleryAlbum::updateOrCreate(
                ['id' => $album['id']],
                [
                    'name' => $album['name'],
                    'description' => $album['description'],
                    'cover' => 'assets/images/placeholder-gallery.jpg',
                ]
            );
        }
    }
}
