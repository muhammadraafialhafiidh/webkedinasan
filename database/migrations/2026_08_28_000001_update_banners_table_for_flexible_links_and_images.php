<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('link_type', 20)->default('none')->after('title');
            $table->foreignId('news_id')->nullable()->after('link_type')->constrained('news')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->after('news_id')->constrained('services')->nullOnDelete();
            $table->string('external_url', 500)->nullable()->after('service_id');
            $table->string('image_source', 20)->default('custom')->after('external_url');
            $table->string('image', 255)->nullable()->change();
        });

        // Migrate existing legacy banner data safely
        DB::table('banners')->whereNotNull('link')->where('link', '!=', '')->update([
            'link_type' => 'external',
            'external_url' => DB::raw('link'),
            'image_source' => 'custom',
        ]);

        DB::table('banners')->where(function ($q) {
            $q->whereNull('link')->orWhere('link', '');
        })->update([
            'link_type' => 'none',
            'image_source' => 'custom',
        ]);
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropForeign(['news_id']);
            $table->dropForeign(['service_id']);
            $table->dropColumn([
                'link_type',
                'news_id',
                'service_id',
                'external_url',
                'image_source',
            ]);
            $table->string('image', 255)->nullable(false)->change();
        });
    }
};
