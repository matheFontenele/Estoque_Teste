<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\EstoqueController;

// Rotas Principais
Route::get('/', [App\Http\Controllers\RequisicaoController::class, 'index'])->name('dashboard');
Route::resource('clientes', ClienteController::class);
Route::resource('requisicoes', RequisicaoController::class);
Route::resource('equipamentos', EquipamentoController::class);

// Rotas de estoque
Route::group(['prefix' => 'gestao-estoque'], function() {
    Route::get('/', [EstoqueController::class, 'index'])->name('estoque.index');
    Route::post('/update', [EstoqueController::class, 'update'])->name('estoque.update');
});