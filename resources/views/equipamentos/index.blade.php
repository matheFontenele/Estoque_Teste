@extends('layouts.app')

@section('subtitle', 'Inventário de Equipamentos')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    openModal: false, 
    categoriaSelecionada: 'equipamento',
    filaItens: [],
    novoItem: {
        nome: '',
        patrimonio: '',
        serial: '',
        quantidade: 1,
        estoque_id: '',
        situacao: 'disponivel'
    },
adicionarAFila() {
    if(!this.novoItem.nome || !this.novoItem.estoque_id) {
        alert('Por favor, preencha o nome e selecione o almoxarifado.');
        return;
    }

    this.filaItens.push({
        nome: this.novoItem.nome,
        // Mudamos o nome da chave para 'tombo' para facilitar o mapeamento
        tombo: this.categoriaSelecionada === 'equipamento' ? this.novoItem.patrimonio : null, 
        quantidade: this.categoriaSelecionada === 'equipamento' ? 1 : this.novoItem.quantidade,
        categoria: this.categoriaSelecionada,
        estoque_id: this.novoItem.estoque_id
    });

    this.novoItem.nome = '';
    this.novoItem.patrimonio = '';
    this.novoItem.serial = '';
    this.novoItem.quantidade = 1;
},
    removerDaFila(index) {
        this.filaItens.splice(index, 1);
    }
}">

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

    <form action="{{ route('equipamentos.index') }}" method="GET" class="relative w-full max-w-md mb-6">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar por nome ou patrimônio..."
            class="w-full pl-12 pr-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-red-500 outline-none transition-all shadow-sm">
    </form>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Categoria / Nome</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Identificação</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-center">Situação</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Localização Atual</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($equipamentos as $item)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        @php
                        $corBadge = match(strtolower($item->categoria ?? '')) {
                        'insumo', 'insumos', 'toners' => 'bg-purple-50 text-purple-600',
                        'peça', 'peças' => 'bg-blue-50 text-blue-600',
                        default => 'bg-red-50 text-red-600',
                        };
                        @endphp
                        <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase {{ $corBadge }}">
                            {{ $item->categoria ?? 'Equipamento' }}
                        </span>
                        <span class="font-bold text-slate-700 block mt-1">{{ $item->nome }}</span>
                    </td>

                    <td class="px-6 py-4 text-xs">
                        @if(in_array(strtolower($item->categoria ?? ''), ['insumo', 'insumos', 'toners', 'peça', 'peças']))
                        <span class="text-slate-400 italic font-medium">Consumível / Qtd: {{ $item->quantidade_estoque }}</span>
                        @else
                        <div class="flex flex-col gap-0.5">
                            <span class="text-slate-600"><strong class="text-slate-400 uppercase text-[9px]">Tombo:</strong> {{ $item->tombo ?? '---' }}</span>
                            <span class="text-slate-600"><strong class="text-slate-400 uppercase text-[9px]">Série:</strong> {{ $item->serial ?? '---' }}</span>
                        </div>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-center">
                        @if($item->quantidade_estoque > 0)
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase border border-emerald-100">Disponível</span>
                        @else
                        <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase border border-amber-100">Alocado</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i class="ph ph-map-pin {{ $item->quantidade_estoque > 0 ? 'text-emerald-400' : 'text-red-400' }}"></i>
                            <span class="text-sm font-bold text-slate-600">
                                @if($item->quantidade_estoque > 0)
                                Estoque Central
                                @else
                                {{ $item->requisicoes->first()?->cliente->razao_social ?? 'Destino não registrado' }}
                                @endif
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('equipamentos.show', $item->id) }}" class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-red-600 hover:text-white transition-all">
                            <i class="ph ph-eye font-bold"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="openModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        style="display: none;" x-cloak>

        <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl max-w-xl w-full overflow-hidden flex flex-col max-h-[90vh]">

            <div class="bg-slate-900 p-6 text-white flex justify-between items-center shrink-0">
                <h3 class="font-black text-xl flex items-center gap-2 tracking-tight">
                    <i class="ph ph-package text-red-500"></i> Entrada de Materiais
                </h3>
                <button @click="openModal = false" class="hover:rotate-90 transition-transform duration-300">
                    <i class="ph ph-x text-2xl text-slate-400"></i>
                </button>
            </div>

            <form action="{{ route('equipamentos.store') }}" method="POST" class="p-8 space-y-6 overflow-y-auto custom-scrollbar">
                @csrf

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Tipo do Item</label>
                    <div class="relative">
                        <select x-model="categoriaSelecionada" name="categoria"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-red-500/20 shadow-sm appearance-none transition-all">
                            <option value="equipamento">Equipamento</option>
                            <option value="insumo">Insumo</option>
                            <option value="peça">Peça para Manutenção</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Descrição do Item</label>
                    <input type="text" x-model="novoItem.nome" name="nome" placeholder="Ex: HP LaserJet M1132"
                        class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-red-500/20 shadow-sm transition-all">
                </div>
                <!--Caso seja um insumo ou peça-->

                <div class="grid grid-cols-2 gap-4">

                    <div x-show="categoriaSelecionada === 'equipamento'" x-transition>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Patrimônio / Tombo</label>
                        <input type="text" x-model="novoItem.patrimonio" name="tombo"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-red-500/20 shadow-sm">
                    </div>

                    <div x-show="categoriaSelecionada === 'equipamento'" x-transition>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Número de Série</label>
                        <input type="text" x-model="novoItem.serial" name="serial"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-red-500/20 shadow-sm">
                    </div>

                    <div x-show="categoriaSelecionada !== 'equipamento'" x-transition>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Quantidade</label>
                        <input type="number" x-model="novoItem.quantidade" name="quantidade_estoque" min="1"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-red-500/20 shadow-sm">
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Almoxarifado</label>
                        <select x-model="novoItem.estoque_id" name="estoque_id"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-red-500/20 shadow-sm appearance-none">
                            <option value="">Selecione...</option>
                            @foreach($estoques as $estoque)
                            <option value="{{ $estoque->id }}">{{ $estoque->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="button" @click="adicionarAFila()"
                    class="w-full py-4 rounded-2xl border-2 border-dashed border-red-200 bg-red-50/30 text-red-500 font-bold hover:bg-red-50 hover:border-red-500 transition-all flex items-center justify-center gap-2 group">
                    <i class="ph ph-plus-square text-xl group-hover:scale-110 transition-transform"></i>
                    Acrescentar outro na lista
                </button>

                <div x-show="filaItens.length > 0" x-transition class="pt-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 ml-1 tracking-widest">Itens para Salvar</label>
                    <div class="space-y-3 max-h-52 overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="(item, index) in filaItens" :key="index">
                            <div class="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-slate-200 transition-all">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-700" x-text="item.nome"></span>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[9px] px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded font-black uppercase" x-text="item.categoria"></span>
                                        <span class="text-[10px] text-slate-400 font-bold" x-text="item.patrimonio ? 'SN: ' + item.patrimonio : 'QTD: ' + item.quantidade"></span>
                                    </div>
                                </div>
                                <button type="button" @click="removerDaFila(index)" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all">
                                    <i class="ph ph-trash-simple font-bold"></i>
                                </button>

                                <input type="hidden" :name="'itens['+index+'][nome]'" :value="item.nome">
                                <input type="hidden" :name="'itens['+index+'][categoria]'" :value="item.categoria">
                                <input type="hidden" :name="'itens['+index+'][tombo]'" :value="item.tombo"> <input type="hidden" :name="'itens['+index+'][quantidade_estoque]'" :value="item.quantidade">
                                <input type="hidden" :name="'itens['+index+'][estoque_id]'" :value="item.estoque_id">
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex gap-4 pt-4 sticky bottom-0 bg-white">
                    <button type="button" @click="openModal = false" class="flex-1 py-4 font-bold text-slate-500 hover:bg-slate-100 rounded-2xl transition-all">
                        Cancelar
                    </button>
                    <button type="submit" :disabled="filaItens.length === 0 && !novoItem.nome"
                        class="flex-[2] py-4 font-black text-white bg-red-600 rounded-2xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all active:scale-95 disabled:opacity-50 disabled:grayscale">
                        Finalizar e Salvar <span x-show="filaItens.length > 0" x-text="'(' + filaItens.length + ')'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection