@extends('layouts.app')

@section('content')
<div class="px-6 py-8">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Painel de Controle</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Geral</p>
                <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['total'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <i class="ph ph-package text-2xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Disponíveis</p>
                <h3 class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['disponivel'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <i class="ph ph-check-circle text-2xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Com Clientes</p>
                <h3 class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['alocado'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <i class="ph ph-user-shared text-2xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Em Reparo</p>
                <h3 class="text-3xl font-bold text-rose-600 mt-1">{{ $stats['manutencao'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center">
                <i class="ph ph-wrench text-2xl"></i>
            </div>
        </div>

    </div>
</div>
@endsection