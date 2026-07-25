<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reading_materials', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn('author_id');
            $table->string('author')->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('reading_materials', function (Blueprint $table) {
            $table->dropColumn('author');
            $table->foreignId('author_id')->after('category_id')->constrained()->cascadeOnDelete();
        });
    }
};
