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
        Schema::create('prodotti', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['vino', 'evento', 'merch']);
            $table->decimal('prezzo');
            $table->string('immagine')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodotti');
    }
};
