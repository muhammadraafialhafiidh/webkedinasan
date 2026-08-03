<?php

namespace Database\Seeders;

use App\Models\GalleryVideo;
use Illuminate\Database\Seeder;

class GalleryVideoSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            [
                'title' => 'Cara Budidaya Lele yang Benar dan Menguntungkan',
                'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'Panduan lengkap budidaya lele untuk pemula',
            ],
            [
                'title' => 'Sosialisasi Program Bantuan Bibit Ikan 2024',
                'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'Video dokumentasi sosialisasi program bantuan',
            ],
            [
                'title' => 'Profil Dinas Perikanan',
                'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'Video profil dan pengenalan Dinas Perikanan',
            ],
        ];

        foreach ($videos as $video) {
            GalleryVideo::updateOrCreate(
                ['title' => $video['title']],
                [
                    'url' => $video['url'],
                    'thumbnail' => null,
                    'description' => $video['description'],
                ]
            );
        }
    }
}
