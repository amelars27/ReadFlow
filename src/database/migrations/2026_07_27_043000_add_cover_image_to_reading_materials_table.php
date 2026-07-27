<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('reading_materials', 'cover_image')) {
            Schema::table('reading_materials', function (Blueprint $table) {
                $table->string('cover_image', 2048)->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reading_materials', 'cover_image')) {
            Schema::table('reading_materials', function (Blueprint $table) {
                $table->dropColumn('cover_image');
            });
        }
    }
};