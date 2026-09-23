<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->string('source_type', 20)->default('upload')->after('title');
            $table->string('image', 255)->nullable()->change();
            $table->string('external_url', 500)->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->dropColumn(['source_type', 'external_url']);
            $table->string('image', 255)->nullable(false)->change();
        });
    }
};
