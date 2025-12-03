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
       Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('username')->unique();
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');

        // tipo utente (persona/azienda) – NON è il ruolo
        $table->enum('tipo_utente', ['persona', 'azienda'])->default('persona');
        $table->string('partita_iva', 11)->nullable(); // obbligatoria solo se azienda

        // altri dati anagrafici
        $table->string('codice_fiscale', 16)->nullable();
        $table->date('data_di_nascita')->nullable();
        $table->string('numero', 20)->nullable(); // telefono
        $table->enum('ruolo', ['utente', 'staff', 'admin'])
              ->default('utente');

        $table->rememberToken();
        $table->timestamps();
    });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
