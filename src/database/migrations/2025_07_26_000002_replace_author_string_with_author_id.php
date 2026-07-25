<?php

use App\Models\Author;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reading_materials', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable()->after('source_type');
        });

        DB::table('reading_materials')->orderBy('id')->each(function ($material) {
            if ($material->author) {
                $author = Author::firstOrCreate(
                    ['name' => $material->author],
                    ['biography' => null],
                );

                DB::table('reading_materials')
                    ->where('id', $material->id)
                    ->update(['author_id' => $author->id]);
            }
        });

        Schema::table('reading_materials', function (Blueprint $table) {
            $table->foreign('author_id')->references('id')->on('authors')->cascadeOnDelete();

            $table->dropColumn('author');
        });
    }

    public function down(): void
    {
        Schema::table('reading_materials', function (Blueprint $table) {
            $table->string('author')->nullable()->after('title');
        });

        DB::table('reading_materials')->orderBy('id')->each(function ($material) {
            $author = DB::table('authors')->where('id', $material->author_id)->first();

            if ($author) {
                DB::table('reading_materials')
                    ->where('id', $material->id)
                    ->update(['author' => $author->name]);
            }
        });

        Schema::table('reading_materials', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn('author_id');
        });
    }
};
