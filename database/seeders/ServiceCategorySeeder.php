<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Bidang Penganekaragaman dan Keamanan Pangan',
                'slug' => 'penganekaragaman-keamanan-pangan',
                'description' => 'Layanan registrasi, sertifikasi, dan pengujian pangan segar asal tumbuhan dan ikan untuk menjamin keamanan pangan masyarakat.',
                'order' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Bidang Ketersediaan dan Stabilisasi Pangan',
                'slug' => 'ketersediaan-stabilisasi-pangan',
                'description' => 'Layanan fasilitasi akses pangan murah bagi masyarakat sebagai upaya stabilisasi harga dan pengendalian inflasi komoditas pangan.',
                'order' => 2,
            ],
            [
                'id' => 3,
                'name' => 'Bidang Penanganan Kerawanan Pangan',
                'slug' => 'penanganan-kerawanan-pangan',
                'description' => 'Layanan intervensi dan penyaluran bantuan pangan bagi masyarakat di desa rentan pangan berdasarkan pemetaan FSVA dan SKPG.',
                'order' => 3,
            ],
            [
                'id' => 4,
                'name' => 'Bidang Perikanan',
                'slug' => 'perikanan',
                'description' => 'Layanan pembinaan, pendampingan, sertifikasi, dan pemeriksaan teknis bagi pembudidaya dan kelompok perikanan di Kabupaten Banyumas.',
                'order' => 4,
            ],
            [
                'id' => 5,
                'name' => 'UPTD Pembenihan dan Budidaya Air Tawar (PBAT)',
                'slug' => 'uptd-pbat',
                'description' => 'Layanan penjualan benih ikan berkualitas dari unit pembenihan milik pemerintah daerah secara langsung maupun melalui aplikasi SI-IKANMAS.',
                'order' => 5,
            ],
        ];

        foreach ($categories as $cat) {
            ServiceCategory::updateOrCreate(
                ['id' => $cat['id']],
                [
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'description' => $cat['description'],
                    'icon' => 'assets/images/service-cat-icon.png',
                    'order' => $cat['order'],
                ]
            );
        }
    }
}
