<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('richieste_vigneto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vigneto_id')->constrained('vigneti')->onDelete('cascade');
            $table->unsignedSmallInteger('annata'); 
            $table->enum('stato', [
                'in_attesa',
                'confermato',
                'rifiutato',
                'annullato'
            ])->default('in_attesa');
            $table->decimal('prezzo_annuo', 10, 2);
            $table->unsignedInteger('bottiglie_stimate')->nullable();
            $table->unique(['user_id', 'vigneto_id', 'annata']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('richieste_vigneto');
    }
};
