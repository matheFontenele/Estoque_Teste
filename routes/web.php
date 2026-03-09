<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\RequisicaoController;

Route::get('/', [EquipamentoController::class, 'index'])->name('dashboard');

Route::resource('clientes', ClienteController::class);
Route::resource('estoque', EquipamentoController::class);
Route::resource('requisicoes', RequisicaoController::class);