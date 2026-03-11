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
            $table->id('numero_requisicao'); //
            $table->foreignId('user_id')->constrained('users'); //
            $table->foreignId('cliente_id')->constrained('clientes'); //
            $table->foreignId('equipamento_id')->constrained('equipamentos'); //

            $table->date('data_solicitacao')->default(now()); //
            $table->date('data_prevista')->nullable(); //

            $table->enum('envio', ['Rota', 'Transportadora', 'Correios']); //
            $table->string('estado'); //
            $table->string('cidade'); //
            $table->enum('etiqueta', ['Alucom', 'Moreia', 'ZapLoc']); //

            $table->integer('quantidade')->default(1); //
            $table->boolean('is_substituicao')->default(false); //
            $table->string('patrimonio_anterior')->nullable(); //

            $table->enum('situacao', ['Atendida', 'Parcial', 'Sem Estoque', 'Cancelada', 'Pendente'])
                ->default('Pendente');

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
