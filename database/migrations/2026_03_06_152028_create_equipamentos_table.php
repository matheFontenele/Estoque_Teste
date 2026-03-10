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
            $table->integer('tombo')->unique();
            $table->string('nome');
            $table->string('serial')->nullable()->unique();
            $table->enum('cor', ['Preto', 'Amarelo', 'Ciano', 'Magenta', 'N/A'])->default('N/A');
            $table->integer('quantidade_estoque')->default(0);
            $table->text('descricao')->nullable(); // Adicionado nullable para não dar erro se estiver vazio
            $table->enum('situacao', ['disponivel', 'alocado', 'manutencao', 'devolucao'])->default('disponivel');
            $table->foreignId('estoque_id')->nullable()->constrained('estoques');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
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
