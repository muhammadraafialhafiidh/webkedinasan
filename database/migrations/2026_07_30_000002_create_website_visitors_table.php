<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index();
            $table->text('user_agent')->nullable();
            $table->string('session_id', 100)->nullable()->index();
            $table->string('browser', 50)->default('Lainnya')->index();
            $table->string('device', 50)->default('Desktop')->index();
            $table->string('operating_system', 50)->default('Lainnya')->index();
            $table->string('url', 255)->index();
            $table->string('page_name', 255)->nullable();
            $table->string('referer', 255)->nullable();
            $table->timestamp('visited_at')->index();
            $table->timestamps();

            $table->index(['ip_address', 'session_id', 'visited_at'], 'idx_visitor_throttle');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_visitors');
    }
};
