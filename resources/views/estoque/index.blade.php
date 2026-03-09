@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4" x-data="{ openModal: false, selectedItem: {}, type: 'add' }">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Controle de Estoque</h1>
            <p class="text-slate-500 text-sm">Gerencie as quantidades disponíveis em tempo real.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <!--Tabela de filtro de estoque-->
        <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-6">
            <div class="w-full md:w-72">
                <label class="block text-xs font-black text-slate-400 uppercase mb-2 ml-1">Filtrar por Unidade / Estoque</label>
                <div class="relative">
                    <select onchange="window.location.href='{{ route('estoque.index') }}?estoque_id=' + this.value"
                        class="w-full rounded-2xl border-slate-200 bg-white p-3 font-bold outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all">
                        <option value="">Todos os Estoques</option>
                        @foreach($todosEstoques as $e)
                        <option value="{{ $e->id }}" {{ request('estoque_id') == $e->id ? 'selected' : '' }}>
                            {{ $e->nome }}
                        </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                        <i class="ph ph-caret-down font-bold"></i>
                    </div>
                </div>
            </div>

            <div class="bg-slate-100 px-4 py-2 rounded-xl border border-slate-200">
                <span class="text-xs font-bold text-slate-500 uppercase">Itens mostrados: </span>
                <span class="text-sm font-black text-slate-800">{{ $equipamentos->count() }}</span>
            </div>
        </div>
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase">Item / Equipamento</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase text-center">Status</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase text-center">Qtd Atual</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase text-right">Ações Rápidas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($equipamentos as $item)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-700">{{ $item->nome }}</div>
                        <div class="text-xs text-slate-400">Tombo: {{ $item->tombo }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->quantidade_estoque > 5)
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[10px] font-black uppercase">Disponível</span>
                        @elseif($item->quantidade_estoque > 0)
                        <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-full text-[10px] font-black uppercase">Baixo</span>
                        @else
                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-[10px] font-black uppercase">Esgotado</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center font-black text-slate-700 text-lg">
                        {{ $item->quantidade_estoque }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button @click="selectedItem = {{ $item }}; type = 'add'; openModal = true" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all">
                                <i class="ph ph-plus-circle text-xl"></i>
                            </button>
                            <button @click="selectedItem = {{ $item }}; type = 'remove'; openModal = true" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all">
                                <i class="ph ph-minus-circle text-xl"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all" x-transition>
            <div :class="type === 'add' ? 'bg-emerald-600' : 'bg-red-600'" class="p-6 text-white">
                <h3 class="font-black text-xl flex items-center gap-2">
                    <i :class="type === 'add' ? 'ph ph-trend-up' : 'ph ph-trend-down'"></i>
                    Ajustar Estoque
                </h3>
                <p class="opacity-80 text-sm" x-text="selectedItem.nome"></p>
            </div>

            <form action="{{ route('estoque.update') }}" method="POST" class="p-8 space-y-4"> @csrf
                <input type="hidden" name="id" :value="selectedItem.id">
                <input type="hidden" name="type" :value="type">

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Quantidade para <span x-text="type === 'add' ? 'Adicionar' : 'Remover'"></span></label>
                    <input type="number" name="quantidade" min="1" required class="w-full rounded-2xl border-slate-200 text-2xl font-black text-center focus:ring-slate-500 focus:border-slate-500">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openModal = false" class="flex-1 py-3 font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" :class="type === 'add' ? 'bg-emerald-600 shadow-emerald-200' : 'bg-red-600 shadow-red-200'" class="flex-1 py-3 font-black text-white rounded-xl shadow-lg transition-all active:scale-95">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection