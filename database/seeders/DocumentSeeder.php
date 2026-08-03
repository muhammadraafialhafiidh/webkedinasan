<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $docs = [
            ['title' => 'Laporan Kinerja Dinas Perikanan Tahun 2024', 'category' => 'Laporan', 'description' => 'Laporan kinerja tahunan dinas perikanan'],
            ['title' => 'Laporan Produksi Perikanan Semester I 2024', 'category' => 'Laporan', 'description' => 'Data produksi ikan periode Januari–Juni 2024'],
            ['title' => 'Formulir Permohonan Izin Usaha Perikanan', 'category' => 'Formulir', 'description' => 'Formulir resmi pengajuan IUP'],
            ['title' => 'Formulir Pendaftaran Kelompok Pembudidaya Ikan', 'category' => 'Formulir', 'description' => 'Formulir registrasi pokdakan baru'],
            ['title' => 'Panduan Budidaya Ikan Lele Intensif', 'category' => 'Panduan', 'description' => 'Juknis budidaya lele sistem bioflok'],
            ['title' => 'Panduan Cara Budidaya Ikan yang Baik (CBIB)', 'category' => 'Panduan', 'description' => 'Buku panduan standar CBIB dari KKP'],
            ['title' => 'Brosur Program Bantuan Benih Ikan 2024', 'category' => 'Brosur', 'description' => 'Informasi program bantuan benih gratis'],
            ['title' => 'Data Statistik Perikanan Kabupaten 2023', 'category' => 'Laporan', 'description' => 'Kompilasi data statistik sektor perikanan'],
        ];

        foreach ($docs as $doc) {
            Document::updateOrCreate(
                ['title' => $doc['title']],
                [
                    'category' => $doc['category'],
                    'description' => $doc['description'],
                    'file' => 'assets/documents/sample-document.pdf',
                ]
            );
        }
    }
}
