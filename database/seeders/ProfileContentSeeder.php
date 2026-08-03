<?php

namespace Database\Seeders;

use App\Models\ProfileContent;
use Illuminate\Database\Seeder;

class ProfileContentSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            'sejarah' => '<p>Dinas Ketahanan Pangan dan Perikanan (DKPP) dibentuk sebagai bentuk komitmen pemerintah daerah dalam mengelola dan mengembangkan potensi sumber daya perikanan serta memastikan ketahanan dan keamanan pangan bagi seluruh masyarakat.</p><p>Seiring berjalannya waktu, instansi ini terus berinovasi dalam memberikan pelayanan publik prima, pendampingan kelompok pembudidaya ikan, serta pemenuhan standar mutu pangan segar berkualitas tinggi.</p>',
            'visi' => 'Terwujudnya sektor perikanan yang maju, mandiri, dan berdaya saing untuk kemakmuran masyarakat.',
            'misi' => '<ul><li>Meningkatkan produksi dan produktivitas perikanan budidaya dan tangkap berwawasan lingkungan.</li><li>Mengembangkan sumber daya manusia dan kelembagaan pembudidaya serta nelayan.</li><li>Memperkuat kelembagaan ketahanan pangan dan ketersediaan komoditas strategis.</li><li>Meningkatkan kualitas pelayanan publik dan pengawasan keamanan pangan segar.</li></ul>',
            'tupoksi' => '<p><strong>Tugas Pokok:</strong> Melaksanakan urusan pemerintahan daerah di bidang perikanan dan ketahanan pangan berdasarkan asas otonomi dan tugas pembantuan.</p><p><strong>Fungsi Utama:</strong></p><ul><li>Perumusan kebijakan teknis di bidang ketahanan pangan, ketersediaan pangan, dan perikanan.</li><li>Pelaksanaan pelayanan publik, sertifikasi kelayakan, dan pengawasan mutu perikanan.</li><li>Pembinaan dan fasilitasi kelompok pembudidaya ikan (Pokdakan) serta pelaku usaha pangan.</li><li>Evaluasi dan pelaporan pelaksanaan program bidang ketahanan pangan dan perikanan.</li></ul>',
        ];

        foreach ($contents as $key => $value) {
            ProfileContent::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
