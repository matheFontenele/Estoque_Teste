<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\GuiaAdiController;

// Rotas Principais
Route::get('/', [RequisicaoController::class, 'dashboard'])->name('dashboard');
Route::resource('clientes', ClienteController::class);
Route::resource('requisicoes', RequisicaoController::class);
Route::resource('equipamentos', EquipamentoController::class);

// Rotas de estoque
Route::get('/estoque', [EstoqueController::class, 'index'])->name('estoque.index');
Route::post('/estoque/update', [EstoqueController::class, 'update'])->name('estoque.update');

//Rotas para informações de clientes
Route::get('/clientes/{cliente}', [App\Http\Controllers\ClienteController::class, 'show'])->name('clientes.show');

//Rota para Guia de Impressoras
Route::resource('guia-adi', GuiaAdiController::class);

// Rota customizada para o atendimento
Route::get('requisicoes/{id}/atender', [RequisicaoController::class, 'atender'])->name('requisicoes.atender');
Route::post('requisicoes/{id}/processar-atendimento', [RequisicaoController::class, 'processarAtendimento'])->name('requisicoes.processar');