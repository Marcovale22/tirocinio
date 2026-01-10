<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /**
         * TABELLA ORDINI
         */
        Schema::create('ordini', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->decimal('totale', 10, 2)->default(0);

            $table->enum('stato', [
                'in_attesa',
                'confermato',
                'spedito',
                'consegnato',
                'annullato'
            ])->default('in_attesa');

            $table->timestamps();
        });

        /**
         * TABELLA ORDINE ITEMS
         */
        Schema::create('ordine_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ordine_id')
                  ->constrained('ordini')
                  ->onDelete('cascade');

            $table->foreignId('prodotto_id')
                  ->constrained('prodotti')
                  ->onDelete('cascade');

            $table->unsignedInteger('quantita');

            $table->decimal('prezzo_unitario', 10, 2);
            $table->decimal('subtotale', 10, 2);

            $table->timestamps();

            $table->unique(['ordine_id', 'prodotto_id']);
        });
    }

    public function down(): void
    {
        // ordine corretto per FK
        Schema::dropIfExists('ordine_items');
        Schema::dropIfExists('ordini');
    }
};
