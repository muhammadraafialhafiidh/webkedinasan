<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            [
                'name' => 'Budi Hartono',
                'email' => 'budi@email.com',
                'phone' => '081234567890',
                'subject' => 'Pertanyaan tentang Program Bantuan Benih',
                'message' => 'Halo, saya ingin menanyakan syarat dan ketentuan untuk mendaftar sebagai penerima bantuan benih ikan lele bagi pokdakan baru di wilayah Banyumas.',
                'is_read' => false,
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari@email.com',
                'phone' => '081987654321',
                'subject' => 'Prosedur Pengajuan Izin Usaha Perikanan',
                'message' => 'Selamat pagi admin, apakah pengajuan rekomendasi sertifikasi kelayakan pengolahan (SKP) bisa diwakilkan atau harus dilakukan langsung oleh pemilik UPI?',
                'is_read' => true,
                'reply' => 'Selamat pagi Ibu Sari, pengajuan rekomendasi SKP dapat dilakukan secara daring melalui sistem OSS dengan menyertakan dokumen legalitas NIB.',
                'replied_at' => now()->subDays(1),
            ],
            [
                'name' => 'Wahyu Pratama',
                'email' => 'wahyu@email.com',
                'phone' => '085678901234',
                'subject' => 'Jadwal Pelatihan Budidaya Lele',
                'message' => 'Apakah ada jadwal pelatihan budidaya ikan bioflok terdekat bulan depan? Mohon informasi tanggal dan lokasi pastinya.',
                'is_read' => false,
            ],
            [
                'name' => 'Ningsih',
                'email' => 'ningsih@email.com',
                'phone' => '087712345678',
                'subject' => 'Informasi Pokdakan Wilayah Utara',
                'message' => 'Terima kasih atas bantuan fasilitasi benih ikan yang telah disalurkan kepada kelompok kami minggu lalu.',
                'is_read' => true,
            ],
        ];

        foreach ($messages as $msg) {
            Contact::updateOrCreate(
                ['email' => $msg['email'], 'subject' => $msg['subject']],
                [
                    'name' => $msg['name'],
                    'phone' => $msg['phone'],
                    'message' => $msg['message'],
                    'is_read' => $msg['is_read'],
                    'reply' => $msg['reply'] ?? null,
                    'replied_at' => $msg['replied_at'] ?? null,
                ]
            );
        }
    }
}
