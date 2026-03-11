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
        $table->string('nome');
        $table->string('categoria');    // Adicionado aqui
        $table->string('subcategoria'); // Adicionado aqui
        
        // Campos de Equipamento (nullable para Insumos não darem erro)
        $table->string('tombo', 5)->nullable()->unique();
        $table->string('serial')->nullable()->unique();
        $table->string('condicao')->nullable(); // Alugado, Disponivel, etc
        
        // Campos de Insumos
        $table->integer('quantidade_estoque')->default(0);
        $table->string('cor')->nullable();
        $table->string('compativel_com')->nullable();
        
        $table->text('descricao')->nullable();
        $table->foreignId('estoque_id')->constrained('estoques')->onDelete('cascade');
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
