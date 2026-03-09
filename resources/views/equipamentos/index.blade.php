@extends('layouts.app')

@section('subtitle', 'Inventário de Equipamentos')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ openModal: false }">
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-xs font-black text-slate-400 uppercase tracking-wider">Total</p>
            <h4 class="text-xl font-black text-slate-800">{{ $stats['total_equipamentos'] }}</h4>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-xs font-black text-emerald-500 uppercase tracking-wider">Disponíveis</p>
            <h4 class="text-xl font-black text-slate-800">{{ $stats['disponivel'] }}</h4>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-xs font-black text-amber-500 uppercase tracking-wider">Alocados</p>
            <h4 class="text-xl font-black text-slate-800">{{ $stats['alocado'] }}</h4>
        </div>
        <button @click="openModal = true" class="bg-red-600 hover:bg-red-700 text-white rounded-2xl font-bold flex items-center justify-center gap-2 transition-all active:scale-95 shadow-lg shadow-red-200">
            <i class="ph ph-plus-circle text-xl"></i>
            Novo Item
        </button>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Equipamento</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Patrimônio</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider text-center">Qtd. Estoque</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Situação</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($equipamentos as $equipamento)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-700">{{ $equipamento->nome }}</td>
                    <td class="px-6 py-4 text-slate-500 font-mono text-sm">{{ $equipamento->patrimonio }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-black {{ $equipamento->quantidade_estoque <= 5 ? 'text-red-600' : 'text-slate-700' }}">
                            {{ $equipamento->quantidade_estoque }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusClasses = [
                                'disponivel' => 'bg-emerald-100 text-emerald-700',
                                'alocado' => 'bg-blue-100 text-blue-700',
                                'manutencao' => 'bg-amber-100 text-amber-700'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $statusClasses[$equipamento->situacao] ?? 'bg-slate-100' }}">
                            {{ $equipamento->situacao }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('equipamentos.destroy', $equipamento->id) }}" method="POST" onsubmit="return confirm('Excluir este equipamento?');">
                            @csrf @method('DELETE')
                            <button class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                                <i class="ph ph-trash text-xl"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Nenhum equipamento cadastrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden">
            <div class="bg-slate-900 p-6 text-white flex justify-between">
                <h3 class="font-black text-xl tracking-tight">Novo Equipamento</h3>
                <button @click="openModal = false"><i class="ph ph-x text-2xl"></i></button>
            </div>

            <form action="{{ route('equipamentos.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Descrição do Item</label>
                    <input type="text" name="nome" required placeholder="Ex: Notebook Dell Latitude" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">Patrimônio / SN</label>
                        <input type="text" name="patrimonio" required class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">Qtd Inicial</label>
                        <input type="number" name="quantidade_estoque" value="1" min="0" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Situação Inicial</label>
                    <select name="situacao" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none appearance-none">
                        <option value="disponivel">Disponível</option>
                        <option value="alocado">Alocado</option>
                        <option value="manutencao">Manutenção</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openModal = false" class="flex-1 py-3 font-bold text-slate-500">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 font-black text-white bg-red-600 rounded-xl shadow-lg shadow-red-200">Salvar Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection