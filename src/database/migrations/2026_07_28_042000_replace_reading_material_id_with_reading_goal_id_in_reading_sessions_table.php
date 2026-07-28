<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('reading_sessions')->truncate();

        Schema::table('reading_sessions', function (Blueprint $table) {
            $table->foreignId('reading_goal_id')->after('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('start_page')->nullable()->after('total_seconds');
            $table->unsignedInteger('end_page')->nullable()->after('start_page');
        });
    }

    public function down(): void
    {
        Schema::table('reading_sessions', function (Blueprint $table) {
            $table->dropForeign(['reading_goal_id']);
            $table->dropColumn(['reading_goal_id', 'start_page', 'end_page']);

            $table->foreignId('reading_material_id')->after('user_id')->constrained()->cascadeOnDelete();
            $table->integer('pages_read')->nullable()->after('duration_minutes');
        });
    }
};