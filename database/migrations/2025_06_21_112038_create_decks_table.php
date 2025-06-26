<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('decks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Kunci asing ke tabel users
            $table->foreignId('collection_id')
                  ->nullable() // Bisa NULL jika deck tidak punya collection
                  ->constrained('collections') // Merujuk ke tabel 'collections'
                  ->onDelete('SET NULL'); // Jika collection dihapus, collection_id di deck jadi NULL
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decks');
    }
};