<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reading_notes', function (Blueprint $table) {
            $table->text('insight')->after('summary');
            $table->text('favorite_quote')->nullable()->after('insight');
            $table->tinyInteger('rating')->nullable()->after('favorite_quote');
        });
    }

    public function down(): void
    {
        Schema::table('reading_notes', function (Blueprint $table) {
            $table->dropColumn(['insight', 'favorite_quote', 'rating']);
        });
    }
};
