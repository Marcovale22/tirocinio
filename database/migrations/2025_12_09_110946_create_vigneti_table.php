<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vigneti', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descrizione')->nullable();
            $table->unsignedInteger('disponibilita')->nullable(); 
            $table->decimal('prezzo_annuo');
            $table->unsignedInteger('bottiglie_stimate')->nullable();
            $table->enum('tipo_vino', ['rosso', 'bianco', 'rosato'])->nullable();
            $table->enum('fase_produzione', [
                'potatura',
                'germogliamento',
                'fioritura',
                'invaiatura',
                'vendemmia',
                'vinificazione',
                'affinamento',
                'imbottigliamento',
                'pronto'
            ])->nullable();
            $table->string('immagine')->nullable();
            $table->boolean('visibile')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vigneti');
    }
};
