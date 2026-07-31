<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('movies');
        Schema::dropIfExists('genres');
    }

    public function down(): void
    {
        Schema::create('genres', function ($table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('movies', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('genre_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('director')->nullable();
            $table->integer('release_year')->nullable();
            $table->integer('duration')->nullable();
            $table->string('status')->default('plan_to_watch');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('poster')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
};
