@extends('layouts.app')

@section('subtitle', 'Inventário de Equipamentos')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    openModal: false, 
    categoriaSelecionada: 'Equipamentos',
    subcategoriaSelecionada: '',
    filaItens: [],
    
    // Configuração de Subcategorias
    opcoesSub: {
        'Equipamentos': ['Impressoras/Multifuncionais', 'Nobreaks/Transformadores/Estabilizadores', 'Perifericos', 'Computadores', 'Outros'],
        'Insumos': ['Toners/Tintas', 'Peças', 'Outros']
    },

    novoItem: {
        nome: '',
        tombo: '',
        serial: '',
        quantidade: 1,
        estoque_id: '',
        situacao: 'disponivel',
        cor: 'Não se Aplica',
        compativel_com: 'Não se Aplica',
        descricao: ''
    },

    adicionarAFila() {
        if(!this.novoItem.nome || !this.novoItem.estoque_id || !this.subcategoriaSelecionada) {
            alert('Por favor, preencha o nome, subcategoria e selecione o almoxarifado.');
            return;
        }

        // Se for equipamento, a quantidade é sempre 1
        let qtdFinal = this.categoriaSelecionada === 'Equipamentos' ? 1 : this.novoItem.quantidade;

        this.filaItens.push({
            nome: this.novoItem.nome,
            categoria: this.categoriaSelecionada,
            subcategoria: this.subcategoriaSelecionada,
            tombo: this.categoriaSelecionada === 'Equipamentos' ? this.novoItem.tombo : null, 
            serial: this.categoriaSelecionada === 'Equipamentos' ? this.novoItem.serial : null,
            quantidade: qtdFinal,
            cor: this.categoriaSelecionada === 'Insumos' ? this.novoItem.cor : 'Não se Aplica',
            compativel_com: this.categoriaSelecionada === 'Insumos' ? this.novoItem.compativel_com : 'Não se Aplica',
            descricao: this.novoItem.descricao,
            estoque_id: this.novoItem.estoque_id
        });

        // Reset campos mantendo o estoque_id para facilitar entradas em lote
        this.novoItem.nome = '';
        this.novoItem.tombo = '';
        this.novoItem.serial = '';
        this.novoItem.quantidade = 1;
        this.novoItem.descricao = '';
    },

    removerDaFila(index) {
        this.filaItens.splice(index, 1);
    }
}">

    {{-- Cards de Estatísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Itens</p>
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

    {{-- Barra de Busca --}}
    <form action="{{ route('equipamentos.index') }}" method="GET" class="relative w-full max-w-md mb-6">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar por nome ou tombo..."
            class="w-full pl-12 pr-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-red-500 outline-none transition-all shadow-sm">
    </form>

    {{-- Tabela de Inventário --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Categoria / Nome</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Identificação / Detalhes</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-center">Situação</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Localização Atual</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($equipamentos as $item)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase inline-block w-fit 
                                {{ $item->categoria === 'Equipamentos' ? 'bg-red-50 text-red-600' : 'bg-purple-50 text-purple-600' }}">
                                {{ $item->subcategoria }}
                            </span>
                            <span class="font-bold text-slate-700 block">{{ $item->nome }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-xs">
                        @if($item->categoria === 'Insumos')
                            <div class="flex flex-col gap-0.5">
                                <span class="text-slate-600"><strong>Cor:</strong> {{ $item->cor }}</span>
                                <span class="text-slate-400 italic font-medium">Qtd: {{ $item->quantidade_estoque }}</span>
                            </div>
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
                        <div class="flex items-center gap-2 text-sm font-bold text-slate-600">
                            <i class="ph ph-map-pin {{ $item->quantidade_estoque > 0 ? 'text-emerald-400' : 'text-red-400' }}"></i>
                            <span>{{ $item->estoque->nome ?? 'N/A' }}</span>
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

    {{-- Modal de Cadastro --}}
    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-cloak>
        <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[95vh]">
            
            <div class="bg-slate-900 p-6 text-white flex justify-between items-center shrink-0">
                <h3 class="font-black text-xl flex items-center gap-2 tracking-tight">
                    <i class="ph ph-package text-red-500"></i> Entrada de Materiais
                </h3>
                <button @click="openModal = false" class="hover:rotate-90 transition-transform duration-300">
                    <i class="ph ph-x text-2xl text-slate-400"></i>
                </button>
            </div>

            <form action="{{ route('equipamentos.store') }}" method="POST" class="p-8 space-y-6 overflow-y-auto">
                @csrf

                {{-- Seleção Categoria / Subcategoria --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Categoria Principal</label>
                        <select x-model="categoriaSelecionada" name="categoria" @change="subcategoriaSelecionada = ''"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-red-500/20 shadow-sm appearance-none">
                            <option value="Equipamentos">Equipamentos</option>
                            <option value="Insumos">Insumos</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Subcategoria</label>
                        <select x-model="subcategoriaSelecionada" name="subcategoria" required
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-red-500/20 shadow-sm appearance-none">
                            <option value="">Selecione...</option>
                            <template x-for="sub in opcoesSub[categoriaSelecionada]" :key="sub">
                                <option :value="sub" x-text="sub"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Nome/Modelo --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest" 
                           x-text="categoriaSelecionada === 'Insumos' ? 'Modelo do Toner/Item' : 'Nome do Equipamento'"></label>
                    <input type="text" x-model="novoItem.nome" name="nome" placeholder="Ex: HP LaserJet M1132 ou TK-5372"
                        class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 shadow-sm transition-all">
                </div>

                {{-- Campos Dinâmicos: Equipamentos --}}
                <div x-show="categoriaSelecionada === 'Equipamentos'" x-transition class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Patrimônio / Tombo (5 Dígitos)</label>
                        <input type="text" x-model="novoItem.tombo" name="tombo" maxlength="5" placeholder="88769"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Número de Série</label>
                        <input type="text" x-model="novoItem.serial" name="serial" placeholder="WDAS56485"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 shadow-sm">
                    </div>
                </div>

                {{-- Campos Dinâmicos: Insumos --}}
                <div x-show="categoriaSelecionada === 'Insumos'" x-transition class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Cor</label>
                        <select x-model="novoItem.cor" name="cor" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                            <option value="Preto">Preto</option>
                            <option value="Cyano">Cyano</option>
                            <option value="Magenta">Magenta</option>
                            <option value="Amarelo">Amarelo</option>
                            <option value="Mono">Mono</option>
                            <option value="Não se Aplica">Não se Aplica</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Compatível com</label>
                        <input type="text" x-model="novoItem.compativel_com" name="compativel_com" placeholder="MA5500"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Quantidade</label>
                        <input type="number" x-model="novoItem.quantidade" name="quantidade_estoque" min="1"
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold shadow-sm">
                    </div>
                </div>

                {{-- Localização --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Almoxarifado</label>
                        <select x-model="novoItem.estoque_id" name="estoque_id" required
                            class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-slate-700 appearance-none">
                            <option value="">Selecione...</option>
                            @foreach($estoques as $estoque)
                                <option value="{{ $estoque->id }}">{{ $estoque->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div x-show="categoriaSelecionada === 'Equipamentos'">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 tracking-widest">Condição</label>
                        <select name="condicao" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                            <option value="Disponivel">Disponivel</option>
                            <option value="Manutenção">Manutenção</option>
                            <option value="Devolução">Devolução</option>
                        </select>
                    </div>
                </div>

                <button type="button" @click="adicionarAFila()"
                    class="w-full py-4 rounded-2xl border-2 border-dashed border-red-200 bg-red-50/30 text-red-500 font-bold hover:bg-red-50 hover:border-red-500 transition-all flex items-center justify-center gap-2 group">
                    <i class="ph ph-plus-square text-xl group-hover:scale-110 transition-transform"></i>
                    Acrescentar outro na lista
                </button>

                {{-- Fila de Itens --}}
                <div x-show="filaItens.length > 0" x-transition class="pt-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 ml-1 tracking-widest">Itens para Salvar</label>
                    <div class="space-y-3 max-h-52 overflow-y-auto pr-2">
                        <template x-for="(item, index) in filaItens" :key="index">
                            <div class="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-700" x-text="item.nome"></span>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[9px] px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded font-black uppercase" x-text="item.subcategoria"></span>
                                        <span class="text-[10px] text-slate-400 font-bold" x-text="item.tombo ? 'T: ' + item.tombo : 'QTD: ' + item.quantidade"></span>
                                    </div>
                                </div>
                                <button type="button" @click="removerDaFila(index)" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all">
                                    <i class="ph ph-trash-simple font-bold"></i>
                                </button>
                                {{-- Inputs Hidden para envio via POST --}}
                                <input type="hidden" :name="'itens['+index+'][nome]'" :value="item.nome">
                                <input type="hidden" :name="'itens['+index+'][categoria]'" :value="item.categoria">
                                <input type="hidden" :name="'itens['+index+'][subcategoria]'" :value="item.subcategoria">
                                <input type="hidden" :name="'itens['+index+'][tombo]'" :value="item.tombo">
                                <input type="hidden" :name="'itens['+index+'][serial]'" :value="item.serial">
                                <input type="hidden" :name="'itens['+index+'][quantidade_estoque]'" :value="item.quantidade">
                                <input type="hidden" :name="'itens['+index+'][cor]'" :value="item.cor">
                                <input type="hidden" :name="'itens['+index+'][compativel_com]'" :value="item.compativel_com">
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
                        class="flex-[2] py-4 font-black text-white bg-red-600 rounded-2xl shadow-lg hover:bg-red-700 transition-all disabled:opacity-50">
                        Finalizar e Salvar <span x-show="filaItens.length > 0" x-text="'(' + filaItens.length + ')'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection