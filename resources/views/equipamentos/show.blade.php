@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('equipamentos.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 transition-all">
                <i class="ph ph-caret-left font-bold"></i>
            </a>
            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Detalhes do Item</h2>
        </div>
        <span class="px-4 py-2 bg-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase">
            Patrimônio: {{ $equipamento->patrimonio ?? 'S/N' }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-8">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-black text-red-600 uppercase mb-1">Equipamento</p>
                    <h1 class="text-3xl font-black text-slate-800">{{ $equipamento->nome }}</h1>
                </div>
                <div class="text-right">
                    <p class="text-xs font-black text-slate-400 uppercase mb-1">Situação Atual</p>
                    <span class="px-3 py-1 {{ $equipamento->quantidade_estoque > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} rounded-full text-xs font-black uppercase">
                        {{ $equipamento->quantidade_estoque > 0 ? 'Disponível' : 'Em Uso / Alocado' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 pt-6 border-t border-slate-100">
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase mb-2">Número de Série</p>
                    <p class="font-bold text-slate-700">{{ $equipamento->numero_serie ?? 'Não informado' }}</p>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase mb-2">Tombo / Patrimônio</p>
                    <p class="font-bold text-slate-700">{{ $equipamento->patrimonio }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-600 p-8 rounded-3xl shadow-xl shadow-red-100 text-white space-y-6">
            <div class="w-12 h-12 bg-red-500 rounded-2xl flex items-center justify-center">
                <i class="ph ph-map-pin text-2xl font-bold"></i>
            </div>
            
            <div>
                <p class="text-xs font-black text-red-200 uppercase mb-1">Localização Atual</p>
                <h3 class="text-xl font-black italic">
                    {{ $ultimaMovimentacao ? $ultimaMovimentacao->cliente->nome : 'No Estoque Central' }}
                </h3>
            </div>

            <div class="pt-4 border-t border-red-500">
                <p class="text-xs font-black text-red-200 uppercase mb-1">Movimentado em</p>
                <p class="font-bold text-lg">
                    {{ $ultimaMovimentacao ? $ultimaMovimentacao->created_at->format('d/m/Y') : $equipamento->created_at->format('d/m/Y') }}
                </p>
                <p class="text-xs text-red-100 mt-1 italic">
                    {{ $ultimaMovimentacao ? $ultimaMovimentacao->created_at->diffForHumans() : 'Item nunca saiu do estoque' }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-black text-slate-800 flex items-center gap-2">
                <i class="ph ph-clock-counter-clockwise"></i>
                Histórico de Movimentações
            </h3>
        </div>
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Destino / Cliente</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-center">Data Saída</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Técnico</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($equipamento->requisicoes as $historico)
                <tr>
                    <td class="px-6 py-4 font-bold text-slate-700">{{ $historico->cliente->nome }}</td>
                    <td class="px-6 py-4 text-slate-500 text-center">{{ $historico->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-right text-slate-400 font-medium">{{ $historico->user->name ?? 'Sistema' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-slate-400 italic">Este item ainda não possui movimentações registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection