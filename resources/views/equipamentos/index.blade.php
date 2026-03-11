@extends('layouts.app')

@section('subtitle', 'Inventário de Equipamentos')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    openModal: false, 
    categoriaSelecionada: 'Equipamentos',
    subcategoriaSelecionada: '',
    filaItens: [],
    opcoesSub: {
        'Equipamentos': ['Impressora', 'Micros', 'Perifericos', 'Estabilizadores/Nobreaks', 'Outros'],
        'Insumos': ['Toner', 'Peças', 'Outros']
    },
    novoItem: {
        nome: '',
        tombo: '',
        serial: '',
        quantidade: 1,
        estoque_id: '',
        cor: 'Mono',
        compativel_com: ''
    },
    adicionarAFila() {
        if(!this.novoItem.nome || !this.novoItem.estoque_id || !this.subcategoriaSelecionada) {
            alert('Por favor, preencha o nome, categoria e almoxarifado.');
            return;
        }

        this.filaItens.push({
            nome: this.novoItem.nome,
            categoria: this.categoriaSelecionada,
            subcategoria: this.subcategoriaSelecionada,
            tombo: this.categoriaSelecionada === 'Equipamentos' ? this.novoItem.tombo : null, 
            serial: this.novoItem.serial,
            quantidade: this.categoriaSelecionada === 'Equipamentos' ? 1 : this.novoItem.quantidade,
            cor: this.novoItem.cor,
            compativel_com: this.novoItem.compativel_com,
            estoque_id: this.novoItem.estoque_id
        });

        // Limpa apenas os campos de identificação única
        this.novoItem.nome = '';
        this.novoItem.tombo = '';
        this.novoItem.serial = '';
    },
    removerDaFila(index) {
        this.filaItens.splice(index, 1);
    }
}">

    {{-- Incluindo os componentes --}}
    @include('equipamentos.partials._stats') <form action="{{ route('equipamentos.index') }}" method="GET" class="relative w-full max-w-md mb-6">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar por nome ou patrimônio..."
            class="w-full pl-12 pr-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-red-500 outline-none transition-all shadow-sm">
    </form>

    {{-- Tabela de Itens --}}
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
                        <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase {{ $item->categoria === 'Insumos' ? 'bg-purple-50 text-purple-600' : 'bg-red-50 text-red-600' }}">
                            {{ $item->subcategoria ?? $item->categoria }}
                        </span>
                        <span class="font-bold text-slate-700 block mt-1">{{ $item->nome }}</span>
                    </td>
                    <td class="px-6 py-4 text-xs">
                        @if($item->categoria === 'Insumos')
                        <span class="text-slate-400 italic">Qtd: {{ $item->quantidade_estoque }} | Cor: {{ $item->cor }}</span>
                        @else
                        <div class="flex flex-col gap-0.5">
                            <span class="text-slate-600"><strong>T:</strong> {{ $item->tombo ?? '---' }}</span>
                            <span class="text-slate-600"><strong>S:</strong> {{ $item->serial ?? '---' }}</span>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 {{ $item->quantidade_estoque > 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }} rounded-full text-[10px] font-black uppercase border">
                            {{ $item->quantidade_estoque > 0 ? 'Disponível' : 'Alocado' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-bold text-slate-600">
                            {{ $item->quantidade_estoque > 0 ? ($item->estoque->nome ?? 'Estoque Central') : ($item->requisicoes->first()?->cliente->razao_social ?? 'Destino') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('equipamentos.show', $item->id) }}" class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-red-600 hover:text-white">
                            <i class="ph ph-eye font-bold"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('equipamentos.partials._modal_cadastro')
</div>
@endsection