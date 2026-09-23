<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $keys = [
            'statistik_nelayan',
            'statistik_produksi',
            'statistik_pokdakan',
            'statistik_layanan',
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();

        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->insert([
            ['key' => 'statistik_nelayan', 'value' => '1.240', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'statistik_produksi', 'value' => '8.500 Ton', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'statistik_pokdakan', 'value' => '320 Kelompok', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'statistik_layanan', 'value' => '12 Layanan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
};
