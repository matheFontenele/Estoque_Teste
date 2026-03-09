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
            $table->id('numero_requisicao'); // Auto-incremento (Nº Requisição)
            $table->foreignId('user_id')->constrained('users'); // Solicitado
            $table->date('data_solicitacao')->default(now()); // Data Atual
            $table->date('previsao');
            $table->enum('envio', ['Rota', 'Transportadora', 'Correios']);
            $table->string('estado');
            $table->string('cidade');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->enum('etiqueta', ['Alucom', 'Moreia', 'ZapLoc']);
            $table->integer('quantidade')->default(1);
            $table->foreignId('equipamento_id')->constrained('equipamentos');
            $table->boolean('is_substituicao')->default(false);
            $table->string('patrimonio_anterior')->nullable(); // Para substituição
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
