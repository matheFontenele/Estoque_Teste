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
                    <td class="px-6 py-4 text-slate-500 font-mono text-sm">{{ $equipamento->tombo }}</td>
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

    <div x-show="openModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-cloak>

        <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden">
            <div class="bg-slate-900 p-6 text-white flex justify-between items-center">
                <h3 class="font-black text-xl flex items-center gap-2">
                    <i class="ph ph-desktop-tower"></i> Novo Equipamento
                </h3>
                <button @click="openModal = false" class="hover:rotate-90 transition-transform">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('equipamentos.store') }}" method="POST" class="p-8 space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2 ml-1">Descrição do Item</label>
                    <input type="text" name="nome" required placeholder="Ex: Notebook Dell Latitude"
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 p-3 font-bold outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2 ml-1">Patrimônio / SN</label>
                        <input type="text" name="tombo" required class="w-full rounded-2xl border-slate-200 bg-slate-50 p-3 font-bold outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2 ml-1">Qtd Inicial</label>
                        <input type="number" name="quantidade_estoque" value="1" min="0"
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 p-3 font-bold outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2 ml-1">Almoxarifado / Destino</label>
                    <div class="relative">
                        <select name="estoque_id" required
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 p-3 font-bold outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all appearance-none">
                            <option value="">Selecione a Unidade</option>
                            @foreach($estoques as $estoque)
                            <option value="{{ $estoque->id }}">{{ $estoque->nome }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <i class="ph ph-caret-down font-bold"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2 ml-1">Situação Inicial</label>
                    <div class="relative">
                        <select name="situacao" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-3 font-bold outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all appearance-none">
                            <option value="disponivel">Disponível</option>
                            <option value="alocado">Alocado</option>
                            <option value="manutencao">Manutenção</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <i class="ph ph-caret-down font-bold"></i>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openModal = false" class="flex-1 py-4 font-bold text-slate-500 hover:bg-slate-100 rounded-2xl transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 py-4 font-black text-white bg-red-600 rounded-2xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all active:scale-95">
                        Salvar Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection