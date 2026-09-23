<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_visitor_pageviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_visitor_id')
                  ->constrained('website_visitors')
                  ->onDelete('cascade');
            $table->string('url', 255)->index();
            $table->string('page_name', 255)->index();
            $table->timestamp('visited_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_visitor_pageviews');
    }
};
