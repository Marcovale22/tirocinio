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
        Schema::create('evento_vini', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventi')->cascadeOnDelete();
            $table->foreignId('vino_id')->constrained('vini')->cascadeOnDelete();
            $table->unsignedInteger('quantita');
            $table->timestamps();

            $table->unique(['evento_id', 'vino_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_vini');
    }
};
