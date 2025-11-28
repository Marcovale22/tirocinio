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
            $table->unsignedBigInteger('prodotto_id')->unique();
            $table->integer('disponibilita')->default('0');
            $table->integer('annata');
            $table->decimal('solfiti')->default('0');
            $table->String('formato');
            $table->decimal('gradazione');
            $table->timestamps();

            $table->foreign('prodotto_id')->references('id')->on('prodotti')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vini');
    }
};
