<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'nama_website' => 'Portal Informasi Dinas Perikanan',
            'tagline' => 'Melayani dengan Profesional, Membangun Perikanan Berkelanjutan',
            'deskripsi' => 'Website resmi Dinas Perikanan yang menyediakan informasi layanan, program, dan kegiatan dinas.',
            'email' => 'info@perikanan.go.id',
            'telepon' => '(0281) 123456',
            'fax' => '(0281) 123457',
            'alamat' => 'Jl. Merdeka No. 1, Purwokerto, Jawa Tengah 53111',
            'jam_operasional' => 'Senin–Jumat: 08.00–16.00 WIB',
            'logo' => 'assets/images/logo-default.png',
            'favicon' => 'assets/images/favicon.ico',
            'facebook_url' => 'https://facebook.com/dinasperikanan',
            'instagram_url' => 'https://instagram.com/dinasperikanan',
            'youtube_url' => 'https://youtube.com/@dinasperikanan',
            'twitter_url' => 'https://twitter.com/dinasperikanan',
            'teks_footer' => '© 2025 Dinas Perikanan. Seluruh hak cipta dilindungi undang-undang.',
            'google_maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.2735234567!2d109.234567!3d-7.423456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMjUnMjQuNCJTIDEwOcKwMTQnMDQuNCJF!5e0!3m2!1sid!2sid!4v1620000000000!5m2!1sid!2sid',
            'statistik_nelayan' => '1.240',
            'statistik_produksi' => '8.500 Ton',
            'statistik_pokdakan' => '320 Kelompok',
            'statistik_layanan' => '12 Layanan',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
