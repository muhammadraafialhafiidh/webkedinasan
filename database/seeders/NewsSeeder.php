<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first() ?? User::first();
        $catKegiatan = NewsCategory::where('slug', 'kegiatan')->first();
        $catProgram = NewsCategory::where('slug', 'program')->first();
        $catInformasi = NewsCategory::where('slug', 'informasi')->first();
        $catPengumuman = NewsCategory::where('slug', 'pengumuman')->first();
        $catBeritaDinas = NewsCategory::where('slug', 'berita-dinas')->first();

        $newsList = [
            [
                'title' => 'Dinas Perikanan Gelar Pelatihan Budidaya Lele untuk Pokdakan',
                'category_id' => $catKegiatan?->id ?? 1,
                'status' => 'published',
                'days_ago' => 2,
            ],
            [
                'title' => 'Program Bantuan Bibit Ikan Gratis Dibuka untuk Kelompok Pembudidaya',
                'category_id' => $catProgram?->id ?? 2,
                'status' => 'published',
                'days_ago' => 5,
            ],
            [
                'title' => 'Capaian Produksi Perikanan Melampaui Target Tahun Ini',
                'category_id' => $catInformasi?->id ?? 3,
                'status' => 'published',
                'days_ago' => 8,
            ],
            [
                'title' => 'Kunjungan Kerja Bupati ke Sentra Budidaya Udang Vaname',
                'category_id' => $catKegiatan?->id ?? 1,
                'status' => 'published',
                'days_ago' => 12,
            ],
            [
                'title' => 'Sosialisasi Larangan Penggunaan Alat Tangkap Tidak Ramah Lingkungan',
                'category_id' => $catPengumuman?->id ?? 4,
                'status' => 'published',
                'days_ago' => 18,
            ],
            [
                'title' => 'Dinas Perikanan Raih Penghargaan Inovasi Pelayanan Publik',
                'category_id' => $catBeritaDinas?->id ?? 5,
                'status' => 'published',
                'days_ago' => 25,
            ],
            [
                'title' => 'Peluncuran Aplikasi e-Surat Izin Usaha Perikanan',
                'category_id' => $catBeritaDinas?->id ?? 5,
                'status' => 'draft',
                'days_ago' => 0,
            ],
        ];

        foreach ($newsList as $item) {
            $slug = Str::slug($item['title']);
            News::updateOrCreate(
                ['slug' => $slug],
                [
                    'news_category_id' => $item['category_id'],
                    'user_id' => $superAdmin->id,
                    'title' => $item['title'],
                    'thumbnail' => 'assets/images/placeholder-news.jpg',
                    'content' => '<p>Dinas Perikanan berkomitmen untuk terus meningkatkan pelayanan publik dan mendukung peningkatan kesejahteraan kelompok pembudidaya ikan (Pokdakan) serta nelayan di seluruh wilayah daerah.</p><p>Melalui berbagai macam program strategis dan pendampingan teknis yang berkelanjutan, diharapkan kapasitas produksi perikanan dapat tumbuh pesat secara ramah lingkungan dan berkelanjutan.</p><p>Masyarakat dapat memperoleh informasi lebih lengkap dengan mendatangi kantor dinas terdekat atau mengakses layanan informasi resmi secara daring melalui portal ini.</p>',
                    'status' => $item['status'],
                    'published_at' => $item['status'] === 'published' ? now()->subDays($item['days_ago']) : null,
                ]
            );
        }
    }
}
