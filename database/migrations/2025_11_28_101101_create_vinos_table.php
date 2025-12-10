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
        Schema::create('vini', function (Blueprint $table) {
            $table->id();

            // relazione 1:1 con prodotti
            $table->foreignId('prodotto_id')
                ->constrained('prodotti')
                ->unique()
                ->onDelete('cascade');

            $table->unsignedSmallInteger('annata'); // es: 2020, 2018 ecc.
            
            $table->decimal('solfiti', 5, 2)->default(0); // es: 12.50 mg/l

            $table->string('formato'); // es: 0.75L, 1.5L

            $table->decimal('gradazione', 4, 1); // es: 13.5

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vini');
    }

};
