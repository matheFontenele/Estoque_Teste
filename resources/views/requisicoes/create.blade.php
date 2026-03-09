@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<div class="max-w-5xl mx-auto py-8 px-4" x-data="{ isSubstituicao: false }">
    <form action="{{ route('requisicoes.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white shadow-xl rounded-3xl border border-slate-200 overflow-hidden">
            <div class="bg-red-600 p-4">
                <h3 class="text-white font-bold flex items-center gap-2">
                    <i class="ph ph-file-plus text-xl"></i> Nova Requisição de Material
                </h3>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-400 uppercase">Nº Requisição</label>
                    <input type="text" value="AUTO" disabled class="w-full rounded-xl bg-slate-50 border-slate-200 text-slate-400 font-bold">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Solicitado por</label>
                    <select name="user_id" class="w-full rounded-xl border-slate-200 focus:ring-red-500">
                        @foreach($usuarios as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Data Solicitação</label>
                    <input type="date" name="data_solicitacao" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Previsão</label>
                    <input type="date" name="previsao" class="w-full rounded-xl border-slate-200 focus:ring-red-500">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Meio de Envio</label>
                    <select name="envio" class="w-full rounded-xl border-slate-200">
                        <option value="Rota">Rota</option>
                        <option value="Transportadora">Transportadora</option>
                        <option value="Correios">Correios</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Etiquetas</label>
                    <select name="etiqueta" class="w-full rounded-xl border-slate-200 focus:ring-red-500">
                        <option value="Alucom">Alucom</option>
                        <option value="Moreia">Moreia</option>
                        <option value="ZapLoc">ZapLoc</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Estado</label>
                    <input type="text" name="estado" placeholder="Ex: DF" class="w-full rounded-xl border-slate-200">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Cidade</label>
                    <input type="text" name="cidade" placeholder="Digite a cidade" class="w-full rounded-xl border-slate-200">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Cliente</label>
                    <select name="cliente_id" class="w-full rounded-xl border-slate-200">
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Equipamento | Insumos</label>
                    <select name="equipamento_id" id="equipamento_id" class="w-full rounded-xl border-slate-200">
                        <option value="">Selecione um item...</option>
                        @foreach($equipamentos as $eq)
                        <option value="{{ $eq->id }}" data-estoque="{{ $eq->quantidade_estoque }}">
                            {{ $eq->tombo }} - {{ $eq->nome }} (Estoque: {{ $eq->quantidade_estoque }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Quantidade</label>
                    <input type="number" name="quantidade" id="quantidade" min="1" value="1" class="w-full rounded-xl border-slate-200">
                    <p class="text-[10px] text-slate-400 mt-1" id="info_estoque">Estoque não verificado</p>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Tipo de Pedido</label>
                    <div class="flex gap-4 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_substituicao" value="0"
                                @click="isSubstituicao = false"
                                class="text-red-600 focus:ring-red-500" checked>
                            <span class="text-sm font-bold text-slate-600">Novo</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_substituicao" value="1"
                                @click="isSubstituicao = true"
                                class="text-red-600 focus:ring-red-500">
                            <span class="text-sm font-bold text-slate-600">Substituição</span>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-1" x-show="isSubstituicao" x-transition.opacity x-cloak>
                    <label class="block text-xs font-black text-red-600 uppercase">Patrimônio Anterior</label>
                    <input type="text" name="patrimonio_anterior"
                        placeholder="Informe o tombo do equipamento antigo"
                        class="w-full rounded-xl border-red-200 bg-red-50 focus:border-red-600 focus:ring-red-600">
                </div>

            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-black py-4 px-12 rounded-2xl shadow-xl shadow-red-200 transition-all flex items-center gap-2 transform active:scale-95">
                    <i class="ph ph-check-circle text-xl"></i> Gravar Requisição
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Mantenha apenas a lógica de estoque em JS puro para não conflitar
    document.getElementById('equipamento_id').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const estoque = option.getAttribute('data-estoque');
        const inputQtd = document.getElementById('quantidade');
        const info = document.getElementById('info_estoque');

        if (estoque) {
            inputQtd.max = estoque;
            info.innerText = "Disponível em estoque: " + estoque;
            info.classList.replace('text-slate-400', 'text-emerald-600');
        } else {
            info.innerText = "Selecione um item";
            info.classList.replace('text-emerald-600', 'text-slate-400');
        }
    });
</script>

<style>
    /* Evita que o campo pisque ao carregar a página */
    [x-cloak] {
        display: none !important;
    }
</style>
@endsection