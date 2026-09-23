<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_visitors', function (Blueprint $table) {
            if (!Schema::hasColumn('website_visitors', 'last_activity')) {
                $table->timestamp('last_activity')->nullable()->after('visited_at')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_visitors', function (Blueprint $table) {
            if (Schema::hasColumn('website_visitors', 'last_activity')) {
                $table->dropColumn('last_activity');
            }
        });
    }
};
