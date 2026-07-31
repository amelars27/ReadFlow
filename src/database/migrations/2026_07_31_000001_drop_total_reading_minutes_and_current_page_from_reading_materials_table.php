<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reading_materials', function (Blueprint $table) {
            $table->dropColumn(['total_reading_minutes', 'current_page']);
        });
    }

    public function down(): void
    {
        Schema::table('reading_materials', function (Blueprint $table) {
            $table->unsignedInteger('current_page')->default(0)->after('total_pages');
            $table->integer('total_reading_minutes')->nullable()->after('current_page');
        });
    }
};
