@extends('layouts.app')

@section('subtitle', 'Painel de Controle')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                    <i class="ph ph-users text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Clientes</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $stats['total_clientes'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center">
                    <i class="ph ph-devices text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Equipamentos</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $stats['total_equipamentos'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                    <i class="ph ph-arrows-left-right text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Requisições</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $stats['total_requisicoes'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 {{ $stats['estoque_baixo'] > 0 ? 'bg-red-50 text-red-600' : 'bg-slate-50 text-slate-400' }} rounded-2xl flex items-center justify-center">
                    <i class="ph ph-warning-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Estoque Baixo</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $stats['estoque_baixo'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-black text-slate-800">Últimas Requisições</h3>
            <a href="{{ route('requisicoes.index') }}" class="text-sm font-bold text-red-600 hover:underline">Ver todas</a>
        </div>
        <table class="w-full text-left">
            <tbody class="divide-y divide-slate-100">
                @forelse($stats['requisicoes_recentes'] as $req)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-700">{{ $req->cliente->nome }}</span>
                        <p class="text-xs text-slate-400">{{ $req->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-sm">
                        {{ $req->equipamento->nome }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-black uppercase">
                            {{ $req->status ?? 'Processado' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-8 text-center text-slate-400 italic">Nenhuma movimentação recente.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection