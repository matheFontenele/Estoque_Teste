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
        Schema::create('equipamentos', function (Blueprint $table) {
            $table->id();
            $table->string('tombo')->unique();
            $table->string('nome');
            $table->string('serie')->nullable()->unique();
            $table->enum('cor', ['Preto', 'Amarelo', 'Ciano', 'Magenta', 'Não se aplica']);
            $table->text('descricao');
            $table->enum('situacao', ['Disponivel', 'Alugado', 'Manutencao', 'Devolução']);
            $table->foreignId('cliente_id')->nullable()->constrained(); // Onde ele está agora
            $table->foreignId('estoque_id')->nullable()->constrained('estoques');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipamentos');
    }
};
