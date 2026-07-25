<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reading_sessions', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('session_date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->text('notes')->nullable()->after('pages_read');
        });
    }

    public function down(): void
    {
        Schema::table('reading_sessions', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time', 'notes']);
        });
    }
};
