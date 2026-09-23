<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            ['title' => 'Selamat Datang di Portal Dinas Perikanan', 'order' => 1],
            ['title' => 'Program Bantuan Benih Ikan 2024', 'order' => 2],
            ['title' => 'Pelatihan Budidaya Ikan — Daftar Sekarang', 'order' => 3],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['order' => $banner['order']],
                [
                    'title' => $banner['title'],
                    'link_type' => 'none',
                    'news_id' => null,
                    'service_id' => null,
                    'external_url' => null,
                    'image_source' => 'custom',
                    'image' => null,
                    'link' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
