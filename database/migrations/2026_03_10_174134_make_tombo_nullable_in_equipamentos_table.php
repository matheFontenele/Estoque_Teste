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
    Schema::table('equipamentos', function (Blueprint $table) {
        // Torna o tombo opcional para permitir cadastrar insumos/peças
        $table->string('tombo')->nullable()->change();
        
        // Garante que a categoria exista (caso não tenha rodado a anterior)
        if (!Schema::hasColumn('equipamentos', 'categoria')) {
            $table->string('categoria')->after('nome')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            //
        });
    }
};
