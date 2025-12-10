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
        Schema::create('eventi', function (Blueprint $table) {
            $table->id();

            // relazione 1:1 con prodotti (specifica_evento)
            $table->foreignId('prodotto_id')->constrained('prodotti')->onDelete('cascade')->unique();
            $table->date('data_evento');                 // data
            $table->time('ora_evento')->nullable();      // ora
            $table->string('luogo');
            $table->text('descrizione')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventi');
    }
};
