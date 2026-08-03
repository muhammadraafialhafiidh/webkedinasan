<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SettingSeeder::class,
            ProfileContentSeeder::class,
            OrganizationMemberSeeder::class,
            NewsCategorySeeder::class,
            NewsSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            PenanggungJawabSeeder::class,
            GalleryAlbumSeeder::class,
            GalleryPhotoSeeder::class,
            GalleryVideoSeeder::class,
            DocumentSeeder::class,
            BannerSeeder::class,
            ContactSeeder::class,
        ]);
    }
}
