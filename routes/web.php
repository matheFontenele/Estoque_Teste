<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\EstoqueController;

// Rotas Principais
Route::get('/', [RequisicaoController::class, 'dashboard'])->name('dashboard');
Route::resource('clientes', ClienteController::class);
Route::resource('requisicoes', RequisicaoController::class);
Route::resource('equipamentos', EquipamentoController::class);

// Rotas de estoque
Route::get('/estoque', [EstoqueController::class, 'index'])->name('estoque.index');
Route::post('/estoque/update', [EstoqueController::class, 'update'])->name('estoque.update');
