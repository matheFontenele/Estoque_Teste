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
        Schema::create('requisicaos', function (Blueprint $table) {
            $table->id();
            $table->date('data_prevista');
            $table->enum('tipo_envio', ['Rota', 'Transportadora', 'Correios', 'Coleta']);
            $table->enum('etiqueta', ['Alucom', 'Moreia', 'ZapLoc']);
            $table->enum('tipo', ['Novo', 'Substituição']);
            $table->string('tombo_substituido')->nullable();
            $table->foreignId('user_id'); // Responsável pela separação
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisicaos');
    }
};
