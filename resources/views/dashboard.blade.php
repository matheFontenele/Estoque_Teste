@extends('layouts.app')
@section('subtitle', 'Painel de Controle')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl">
            <i class="ph ph-package"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Total Geral</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-2xl">
            <i class="ph ph-check-circle"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Disponíveis</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $stats['disponivel'] }}</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-2xl">
            <i class="ph ph-user-shared"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Com Clientes</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $stats['alocado'] }}</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center text-2xl">
            <i class="ph ph-wrench"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Em Reparo</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $stats['manutencao'] }}</h3>
        </div>
    </div>
</div>
@endsection