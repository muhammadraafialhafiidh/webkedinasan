<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Informasi', 'slug' => 'informasi'],
            ['name' => 'Kegiatan', 'slug' => 'kegiatan'],
            ['name' => 'Pengumuman', 'slug' => 'pengumuman'],
            ['name' => 'Program', 'slug' => 'program'],
            ['name' => 'Berita Dinas', 'slug' => 'berita-dinas'],
        ];

        foreach ($categories as $cat) {
            NewsCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name']]
            );
        }
    }
}
