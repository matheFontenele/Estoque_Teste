@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-5xl mx-auto py-8 px-4"
    x-data="{ 
        isSubstituicao: false, 
        estado: '', 
        cidade: '',
        preencherLocalizacao(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            this.estado = selectedOption.getAttribute('data-estado') || '';
            this.cidade = selectedOption.getAttribute('data-cidade') || '';
        }
    }">

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
        <ul class="list-disc list-inside text-sm font-bold">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

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
                    <label class="block text-xs font-black text-slate-700 uppercase">Solicitado por</label>
                    <select name="user_id" class="w-full rounded-xl border-slate-200">
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
                    <label class="block text-xs font-black text-slate-700 uppercase">Previsão Entrega</label>
                    <input type="date" name="previsao" required class="w-full rounded-xl border-slate-200">
                </div>

                <div class="md:col-span-2 space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-widest">Cliente</label>
                    <select name="cliente_id" 
                            @change="preencherLocalizacao($event)"
                            required
                            class="w-full rounded-xl border-slate-200 bg-white p-3 font-bold text-slate-700 focus:ring-red-500">
                        <option value="">Selecione o Cliente...</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" 
                                    data-estado="{{ $cliente->estado }}" 
                                    data-cidade="{{ $cliente->cidade }}">
                                {{ $cliente->razao_social }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 md:col-span-1">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase">Estado</label>
                        <input type="text" name="estado" x-model="estado" readonly class="w-full rounded-xl border-slate-100 bg-slate-50 p-3 font-bold text-slate-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase">Cidade</label>
                        <input type="text" name="cidade" x-model="cidade" readonly class="w-full rounded-xl border-slate-100 bg-slate-50 p-3 font-bold text-slate-500">
                    </div>
                </div>

                <div class="md:col-span-2 space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Equipamento | Insumo</label>
                    <select name="equipamento_id" required class="w-full rounded-xl border-slate-200 font-bold">
                        <option value="">Selecione o Item...</option>
                        @foreach($equipamentos as $equipamento)
                            <option value="{{ $equipamento->id }}">
                                {{ $equipamento->tombo ?? 'S/T' }} - {{ $equipamento->nome }} (Estoque: {{ $equipamento->quantidade_estoque }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Quantidade</label>
                    <input type="number" name="quantidade" min="1" value="1" class="w-full rounded-xl border-slate-200">
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
                    <select name="etiqueta" class="w-full rounded-xl border-slate-200">
                        <option value="Alucom">Alucom</option>
                        <option value="Moreia">Moreia</option>
                        <option value="ZapLoc">ZapLoc</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-black text-slate-700 uppercase">Tipo de Pedido</label>
                    <div class="flex gap-4 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_substituicao" value="0" @click="isSubstituicao = false" checked>
                            <span class="text-sm font-bold text-slate-600">Novo</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_substituicao" value="1" @click="isSubstituicao = true">
                            <span class="text-sm font-bold text-slate-600">Substituição</span>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-1" x-show="isSubstituicao" x-transition x-cloak>
                    <label class="block text-xs font-black text-red-600 uppercase">Patrimônio Anterior</label>
                    <input type="text" name="patrimonio_anterior" placeholder="Informe o tombo antigo" class="w-full rounded-xl border-red-200 bg-red-50">
                </div>
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-black py-4 px-12 rounded-2xl shadow-xl flex items-center gap-2">
                    <i class="ph ph-check-circle text-xl"></i> Gravar Requisição
                </button>
            </div>
        </div>
    </form>
</div>

<style> [x-cloak] { display: none !important; } </style>
@endsection