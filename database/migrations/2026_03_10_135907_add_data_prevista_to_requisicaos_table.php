<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requisicaos', function (Blueprint $table) {
            // Adiciona a coluna que o erro apontou como faltante
            $table->date('data_prevista')->nullable()->after('cidade');
        });
    }

    public function down(): void
    {
        Schema::table('requisicaos', function (Blueprint $table) {
            // Caso precise reverter, removemos a coluna
            $table->dropColumn('data_prevista');
        });
    }
};
