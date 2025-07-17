<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_genre', function (Blueprint $table) {
            $table->uuid('book_id');
            $table->uuid('genre_id');
            $table->primary(['book_id', 'genre_id']);
            $table->foreign('book_id')->references('id')->on('books');
            $table->foreign('genre_id')->references('id')->on('genres');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_genre');
    }
}; 