@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Nova Requisição</h2>

    <form action="{{ route('requisicoes.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Cliente</label>
                <select name="cliente_id" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-400 outline-none">
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Etiqueta</label>
                <select name="etiqueta" class="w-full p-2 border rounded">
                    <option value="Moreia">Moreia</option>
                    <option value="Alucom">Alucom</option>
                    <option value="ZapLoc">ZapLoc</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Equipamento (Tombo Disponível)</label>
            <select name="equipamento_id" class="w-full p-2 border rounded">
                @foreach($equipamentosDisponiveis as $eq)
                    <option value="{{ $eq->id }}">{{ $eq->tombo }} - {{ $eq->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
             <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Tipo de Operação</label>
                <select name="tipo" id="tipo" class="w-full p-2 border rounded" onchange="toggleSubstituicao()">
                    <option value="novo">Novo</option>
                    <option value="substituicao">Substituição</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Data Prevista</label>
                <input type="date" name="data_prevista" class="w-full p-2 border rounded" required>
            </div>
        </div>

        <div id="campo_substituicao" class="hidden mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
            <label class="block text-gray-700 font-bold mb-2">Tombo do Equipamento Antigo (que será recolhido)</label>
            <input type="text" name="tombo_antigo" class="w-full p-2 border border-yellow-400 rounded" placeholder="Ex: TM-1234">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Observação</label>
            <textarea name="observacao" rows="3" class="w-full p-2 border rounded"></textarea>
        </div>

        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded hover:bg-green-700 transition">
            Finalizar e Atualizar Estoque
        </button>
    </form>
</div>

<script>
    function toggleSubstituicao() {
        const tipo = document.getElementById('tipo').value;
        const div = document.getElementById('campo_substituicao');
        div.classList.toggle('hidden', tipo !== 'substituicao');
    }
</script>
@endsection