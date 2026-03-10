<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            // Adiciona a coluna categoria após o nome
            $table->string('categoria')->nullable()->after('nome');
        });
    }

    public function down()
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};
