<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // Bidang Penganekaragaman dan Keamanan Pangan (category_id = 1)
            [
                'service_category_id' => 1,
                'title' => 'Registrasi Pangan Segar Asal Tumbuhan Produksi Dalam Negeri Usaha Kecil (PSAT-PDUK)',
                'slug' => 'registrasi-psat-pduk',
                'description' => 'Layanan registrasi bagi pelaku usaha kecil yang memproduksi pangan segar asal tumbuhan untuk mendapatkan rekomendasi PB-UMKU melalui sistem OSS.',
                'requirements' => '<ul><li>Nomor Induk Berusaha (NIB)</li><li>Surat Permohonan</li><li>Surat Informasi Produk</li><li>Surat Pernyataan Komitmen (dibubuhi materai)</li></ul>',
                'procedure' => '<ol><li>Pemohon datang ke kantor DKPP Banyumas untuk konsultasi</li><li>Pemohon mengunduh formulir di website oss.go.id</li><li>Pemohon mencetak formulir kemudian mengunggah scan dokumen persyaratan di oss.go.id</li><li>Pemohon menunggu hasil verifikasi dokumen</li><li>Jika memenuhi persyaratan maka diproses; jika tidak, dikembalikan kepada pemohon</li><li>Pemohon mengunduh PB-UMKU registrasi PSAT-PDUK melalui oss.go.id</li></ol>',
                'duration' => '14 Hari Kerja',
                'cost' => 'Tidak ada biaya/tarif',
                'product' => 'Surat Rekomendasi Pengajuan PB-UMKU Registrasi PSAT-PDUK',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'service_category_id' => 1,
                'title' => 'Penerbitan Rekomendasi Sertifikasi Kelayakan Pengolahan (SKP)',
                'slug' => 'penerbitan-rekomendasi-skp',
                'description' => 'Layanan penerbitan rekomendasi sertifikasi kelayakan pengolahan bagi Unit Pengolah Ikan (UPI) yang telah memenuhi standar GMP dan SSOP.',
                'requirements' => '<ul><li>Nomor Induk Berusaha (NIB)</li></ul>',
                'procedure' => '<ol><li>UPI/Pelaku Usaha mengajukan permohonan SKP melalui akun SKP online di web OSS dan mengisi formulir pengajuan</li><li>Kepala Dinas menerima pengajuan dan membuat disposisi pelaksanaan pembinaan</li><li>Pembina Mutu melakukan pembinaan Pra SKP</li><li>Pembina Mutu melakukan pengecekan kelengkapan persyaratan SKP</li><li>Jika persyaratan terpenuhi, Pembina Mutu melakukan pembinaan dan pengecekan penerapan GMP dan SSOP di UPI</li><li>Pembina Mutu memberikan saran perbaikan serta melakukan verifikasi tindak lanjut perbaikan UPI</li><li>Pengiriman dokumen Rekomendasi Penerbitan SKP ke Dirjen PDSPKP melalui web OSS</li></ol>',
                'duration' => 'Maksimal 60 Hari Kerja',
                'cost' => 'Tidak ada biaya/tarif',
                'product' => 'Surat Rekomendasi SKP',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'service_category_id' => 1,
                'title' => 'Uji Pangan Segar Asal Tumbuhan (PSAT-PDUK) dan Pangan Segar Asal Ikan (PSAI)',
                'slug' => 'uji-psat-pduk-psai',
                'description' => 'Layanan pengujian laboratorium untuk pangan segar asal tumbuhan dan pangan segar asal ikan guna memastikan keamanan pangan yang beredar di masyarakat.',
                'requirements' => '<ul><li>Isian Formulir Permohonan Pengujian (disediakan di loket atau via aplikasi)</li><li>Fotokopi KTP/Identitas Pemohon</li><li>Fotokopi NIB atau Surat Keterangan Usaha (khusus untuk PSAT-PDUK)</li><li>Surat Pengantar Sampel (jika dikirim via ekspedisi)</li><li><strong>Sampel PSAT-PDUK:</strong> Segar (beras, buah, sayur) dalam kondisi baik, dikemas rapi, minimal 500 gram s.d. 1 kg</li><li><strong>Sampel PSAI:</strong> Ikan/hasil perikanan segar/beku dalam cool box + es/gel (suhu &lt;4°C), minimal 500 gram</li></ul>',
                'procedure' => '<ol><li>Pemohon membawa/mengirim sampel ke Loket Pelayanan; petugas memeriksa kelengkapan administrasi dan kondisi teknis sampel</li><li>Petugas melakukan registrasi dan menerbitkan Kode Sampel</li><li>Sampel yang memenuhi syarat didistribusikan ke laboratorium teknis untuk pengujian</li><li>Manajer Teknis memvalidasi data mentah dan hasil uji</li><li>Kepala Laboratorium/Pejabat berwenang menandatangani Laporan Hasil Uji</li><li>Petugas loket menyerahkan LHU asli kepada pemohon</li></ol>',
                'duration' => '5–7 Hari Kerja (reguler); 7–10 Hari Kerja (kompleks: residu pestisida, logam berat, formalin)',
                'cost' => 'Tidak ada biaya',
                'product' => 'Laporan Hasil Uji (LHU) / Certificate of Analysis',
                'is_active' => true,
                'order' => 3,
            ],

            // Bidang Ketersediaan dan Stabilisasi Pangan (category_id = 2)
            [
                'service_category_id' => 2,
                'title' => 'Gerakan Pangan Murah (GPM)',
                'slug' => 'gerakan-pangan-murah',
                'description' => 'Fasilitasi pasar pangan murah bagi masyarakat sebagai upaya pengendalian inflasi harga komoditas pangan strategis di Kabupaten Banyumas.',
                'requirements' => '<ul><li>Surat Permohonan dari Desa/Kelurahan/Lembaga Masyarakat/Perusahaan</li><li>Atau berdasarkan Hasil Rapat Koordinasi Pengendalian Inflasi</li><li>Atau berdasarkan Hasil Analisa Peringatan Dini Kewaspadaan Pangan dan Gizi (SKPG)</li></ul>',
                'procedure' => '<ol><li>Pemohon datang ke Kantor DKPP Kab. Banyumas dan menyerahkan surat permohonan</li><li>Pemohon menunggu hasil koordinasi dan pemeriksaan lapang</li><li>Jika memenuhi persyaratan, akan diproses lebih lanjut; jika tidak, disampaikan kepada pemohon</li><li>Pemohon memperoleh fasilitasi Gerakan Pangan Murah</li></ol>',
                'duration' => '7 Hari Kerja',
                'cost' => 'Tidak ada biaya',
                'product' => 'Fasilitasi Gerakan Pangan Murah (GPM)',
                'is_active' => true,
                'order' => 4,
            ],

            // Bidang Penanganan Kerawanan Pangan (category_id = 3)
            [
                'service_category_id' => 3,
                'title' => 'Intervensi Kewaspadaan Pangan dan Gizi',
                'slug' => 'intervensi-kewaspadaan-pangan-gizi',
                'description' => 'Penyaluran bantuan komoditas pangan bergizi kepada masyarakat di desa rentan pangan prioritas berdasarkan hasil pemetaan FSVA dan SKPG.',
                'requirements' => '<ul><li>Penerima bantuan wajib terdaftar dalam desa rentan pangan prioritas 1, 2, dan 3 sesuai hasil peta rawan pangan (FSVA)</li><li>Sesuai hasil peta situasi kewaspadaan pangan dan gizi (SKPG)</li></ul>',
                'procedure' => '<ol><li>Pengumpulan data untuk menentukan sasaran penerima bantuan</li><li>Analisis data kewaspadaan pangan dan gizi</li><li>Verifikasi data penerima</li><li>Penetapan sasaran penerima bantuan</li><li>Penyaluran bantuan dilakukan setelah penetapan data sasaran dari desa prioritas 1 dan 2 sesuai pemetaan FSVA dan SKPG</li><li>Monitoring, Evaluasi, dan Pelaporan</li></ol>',
                'duration' => '±1 bulan; penyaluran dilakukan secara periodik 2 kali',
                'cost' => 'Tidak ada biaya/tarif',
                'product' => 'Bantuan komoditas pangan yang bermutu, bergizi tinggi, dan aman dikonsumsi',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'service_category_id' => 3,
                'title' => 'Penyaluran Beras Cadangan Pangan Pemerintah Daerah (CPPD)',
                'slug' => 'penyaluran-beras-cppd',
                'description' => 'Penyaluran bantuan beras dari cadangan pangan pemerintah daerah kepada masyarakat terdampak bencana atau kerawanan pangan atas dasar disposisi Bupati.',
                'requirements' => '<ul><li>Surat Permintaan dari Kades/Lurah mengetahui Camat, atau Surat Permintaan Kepala BPBD, atau SK Tanggap Darurat dari Bupati</li><li>Surat Permintaan kepada Bupati (tembusan Kepala DKPP) dilampiri By Name By Address (BNBA) penerima bantuan</li><li>Disposisi dari Bupati untuk menindaklanjuti penyaluran CPPD</li></ul>',
                'procedure' => '<ol><li>Pemohon menyampaikan surat permintaan bantuan CPPD kepada Bupati (tembusan Kepala DKPP) dilampiri jumlah penerima, BNBA, dan jumlah CPPD yang diminta</li><li>Jika disposisi Bupati "ditindaklanjuti" maka bantuan CPPD akan disalurkan</li><li>Masyarakat menerima bantuan CPPD berupa beras</li></ol>',
                'duration' => '1 Hari Kerja',
                'cost' => 'Tidak ada biaya/tarif',
                'product' => 'Bantuan Beras Cadangan Pangan Pemerintah Daerah Kabupaten Banyumas',
                'is_active' => true,
                'order' => 6,
            ],

            // Bidang Perikanan (category_id = 4)
            [
                'service_category_id' => 4,
                'title' => 'Pendampingan Cara Pembenihan Ikan yang Baik (CPIB)',
                'slug' => 'pendampingan-cpib',
                'description' => 'Layanan pendampingan dan pembinaan bagi pembudidaya ikan aktif di bidang pembenihan untuk memenuhi standar Cara Pembenihan Ikan yang Baik (CPIB).',
                'requirements' => '<ul><li>Pembudidaya Ikan Aktif (Pembenihan Ikan)</li><li>Memenuhi Persyaratan Teknis CPIB</li><li>Memenuhi Persyaratan Administrasi CPIB</li></ul>',
                'procedure' => '<ol><li>Pemohon melakukan pendaftaran secara online maupun offline</li><li>Pemeriksaan dokumen pemohon oleh petugas</li></ol>',
                'duration' => 'Kondisional',
                'cost' => 'Tidak dipungut biaya',
                'product' => 'Laporan Pembinaan CPIB',
                'is_active' => true,
                'order' => 7,
            ],
            [
                'service_category_id' => 4,
                'title' => 'Pendampingan Cara Budidaya Ikan yang Baik (CBIB)',
                'slug' => 'pendampingan-cbib',
                'description' => 'Layanan pendampingan dan pembinaan bagi pembudidaya ikan aktif di bidang pembesaran untuk memenuhi standar Cara Budidaya Ikan yang Baik (CBIB).',
                'requirements' => '<ul><li>Pembudidaya Ikan Aktif (Pembesaran)</li><li>Memenuhi Persyaratan Teknis CBIB</li><li>Memenuhi Persyaratan Administrasi CBIB</li></ul>',
                'procedure' => '<ol><li>Pemohon melakukan pendaftaran secara online maupun offline</li><li>Pemeriksaan dokumen pemohon oleh petugas</li></ol>',
                'duration' => 'Kondisional',
                'cost' => 'Tidak dipungut biaya',
                'product' => 'Laporan Pembinaan CBIB',
                'is_active' => true,
                'order' => 8,
            ],
            [
                'service_category_id' => 4,
                'title' => 'Penerbitan Surat Tanda Daftar Kelompok Perikanan (STDK)',
                'slug' => 'penerbitan-stdk',
                'description' => 'Layanan penerbitan surat tanda daftar resmi bagi kelompok pembudidaya ikan yang telah memenuhi persyaratan administrasi dan kelembagaan.',
                'requirements' => '<ul><li>Surat permohonan STDK ditandatangani ketua kelompok</li><li>Fotokopi Surat Pengesahan/SK Pembentukan Kelompok Pembudidaya Ikan</li><li>Fotokopi Berita Acara Pembentukan Kelompok</li><li>Fotokopi AD-ART Kelompok</li><li>Fotokopi KTP seluruh pengurus dan anggota kelompok</li></ul>',
                'procedure' => '<ol><li>Pemohon menyampaikan surat permohonan penerbitan STDK dengan melampirkan seluruh dokumen persyaratan</li><li>Petugas melakukan pemeriksaan kelengkapan dokumen persyaratan</li><li>Penerbitan STDK oleh pejabat berwenang</li><li>Penyerahan STDK kepada pemohon</li></ol>',
                'duration' => 'Maksimal 3 Hari Kerja',
                'cost' => 'Tidak dikenakan biaya',
                'product' => 'Surat Tanda Daftar Kelompok Perikanan (STDK)',
                'is_active' => true,
                'order' => 9,
            ],
            [
                'service_category_id' => 4,
                'title' => 'Pemeriksaan Kualitas Air Perikanan',
                'slug' => 'pemeriksaan-kualitas-air',
                'description' => 'Layanan pemeriksaan kualitas air kolam budidaya oleh petugas POSIKANDU secara langsung ke lokasi, disertai pemberian saran dan rekomendasi untuk mendukung keberhasilan budidaya.',
                'requirements' => '<ul><li>Kelompok Pembudidaya Ikan (Pokdakan)</li><li>Pembudidaya Perorangan</li></ul>',
                'procedure' => '<ol><li>Pemohon mengajukan permohonan uji kualitas air kolam melalui telepon / datang langsung / surat</li><li>Petugas POSIKANDU menentukan jadwal pemeriksaan ke lapangan</li><li>Petugas POSIKANDU mendatangi lokasi dan melakukan pemeriksaan kualitas air kolam budidaya</li><li>Petugas POSIKANDU menyampaikan hasil uji kualitas air dan memberikan saran serta rekomendasi untuk kegiatan budidaya</li></ol>',
                'duration' => 'Maksimal 2 Hari',
                'cost' => 'Gratis',
                'product' => 'Form Hasil Uji Kualitas Air Budidaya Perikanan',
                'is_active' => true,
                'order' => 10,
            ],
            [
                'service_category_id' => 4,
                'title' => 'Pemeriksaan Hama dan Penyakit Ikan',
                'slug' => 'pemeriksaan-hama-penyakit-ikan',
                'description' => 'Layanan pemeriksaan hama dan penyakit ikan pada kolam budidaya oleh petugas POSIKANDU secara langsung ke lokasi, disertai saran penanganan dan rekomendasi budidaya.',
                'requirements' => '<ul><li>Kelompok Pembudidaya Ikan (Pokdakan)</li><li>Pembudidaya Perorangan</li></ul>',
                'procedure' => '<ol><li>Pemohon mengajukan permohonan pemeriksaan hama dan penyakit ikan melalui telepon / datang langsung / surat</li><li>Petugas POSIKANDU menentukan jadwal pemeriksaan ke lapangan</li><li>Petugas POSIKANDU mendatangi lokasi dan melakukan pemeriksaan hama dan kualitas air kolam budidaya</li><li>Petugas POSIKANDU menyampaikan hasil pemeriksaan dan memberikan saran serta rekomendasi untuk kegiatan budidaya</li></ol>',
                'duration' => 'Maksimal 2 Hari',
                'cost' => 'Tidak dipungut biaya',
                'product' => 'Lembar Form Hasil Pemeriksaan Hama dan Penyakit Ikan',
                'is_active' => true,
                'order' => 11,
            ],

            // UPTD PBAT (category_id = 5)
            [
                'service_category_id' => 5,
                'title' => 'Penjualan Benih Ikan',
                'slug' => 'penjualan-benih-ikan',
                'description' => 'Layanan penjualan benih ikan berkualitas dari UPTD PBAT secara langsung maupun melalui aplikasi SI-IKANMAS, disertai Surat Keterangan Asal Ikan.',
                'requirements' => '<ul><li>Permohonan pelayanan penjualan benih ikan</li><li>Identitas pemohon (KTP)</li></ul>',
                'procedure' => '<p><strong>Pembelian Langsung:</strong></p><ol><li>Pembeli datang langsung ke UPTD PBAT (Senin–Kamis 07.30–15.30 WIB, Jumat 07.30–15.15 WIB)</li><li>Pembeli diantar ke kolam penampungan untuk melihat stok ikan</li><li>Pembeli melakukan pemesanan jumlah dan waktu pengambilan</li><li>Melakukan pembayaran tunai</li><li>Ikan dipacking dan diberikan kepada pembeli</li><li>Menerima Surat Keterangan Asal Ikan</li></ol><p><strong>Pembelian Online (Aplikasi SI-IKANMAS):</strong></p><ol><li>Unduh Aplikasi SI-IKANMAS dari Playstore</li><li>Daftarkan akun dengan data diri (KTP, nomor telepon, e-mail)</li><li>Lakukan pemesanan ikan di aplikasi</li><li>Lakukan pembayaran di lokasi pengambilan ikan</li><li>Ambil ikan yang sudah dipacking</li><li>Menerima Surat Keterangan Asal Ikan</li></ol>',
                'duration' => '1 Hari',
                'cost' => 'Sesuai Perda Kabupaten Banyumas Nomor 1 Tahun 2025',
                'product' => 'Benih Ikan + Surat Keterangan Asal Ikan',
                'is_active' => true,
                'order' => 12,
            ],
        ];

        foreach ($services as $srv) {
            Service::updateOrCreate(
                ['slug' => $srv['slug']],
                [
                    'service_category_id' => $srv['service_category_id'],
                    'title' => $srv['title'],
                    'description' => $srv['description'],
                    'requirements' => $srv['requirements'],
                    'procedure' => $srv['procedure'],
                    'duration' => $srv['duration'],
                    'cost' => $srv['cost'],
                    'product' => $srv['product'],
                    'icon' => 'assets/images/service-icon-default.png',
                    'is_active' => $srv['is_active'],
                    'order' => $srv['order'],
                ]
            );
        }
    }
}
