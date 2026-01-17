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
            $table->foreignId('prodotto_id')
                ->constrained('prodotti')
                ->unique()
                ->onDelete('cascade');
            $table->unsignedSmallInteger('annata'); 
            $table->decimal('solfiti', 5, 2)->default(0); 
            $table->string('formato'); 
            $table->decimal('gradazione', 4, 1); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vini');
    }

};
