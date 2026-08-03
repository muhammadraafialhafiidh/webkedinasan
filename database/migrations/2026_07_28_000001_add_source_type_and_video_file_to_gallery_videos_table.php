<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_videos', function (Blueprint $table) {
            $table->string('source_type', 20)->default('youtube')->after('title');
            $table->string('video_file', 255)->nullable()->after('url');
            $table->string('url', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('gallery_videos', function (Blueprint $table) {
            $table->dropColumn(['source_type', 'video_file']);
            $table->string('url', 500)->nullable(false)->change();
        });
    }
};
