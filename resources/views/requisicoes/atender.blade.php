@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="bg-slate-800 p-6 text-white">
        <h2 class="text-xl font-bold">Atendimento da Requisição #{{ $requisicao->numero_requisicao }}</h2>
        <p class="text-slate-400 text-sm">Cliente: {{ $requisicao->cliente->nome }} | Item: {{ $requisicao->equipamento->nome }}</p>
    </div>

    <form action="{{ route('requisicoes.processar', $requisicao->numero_requisicao) }}" method="POST" class="p-8 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-black uppercase text-slate-400 mb-2">Qtd. Separada</label>
                <input type="number" name="quantidade_separada" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3" value="{{ $requisicao->quantidade }}">
            </div>
            <div>
                <label class="block text-xs font-black uppercase text-slate-400 mb-2">Data Separação</label>
                <input type="date" name="data_separacao" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3" value="{{ date('Y-m-d') }}">
            </div>
            <div>
                <label class="block text-xs font-black uppercase text-slate-400 mb-2">Separado Por</label>
                <input type="text" name="separado_por" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3" placeholder="Nome do responsável">
            </div>
        </div>

        <div>
            <label class="block text-xs font-black uppercase text-slate-400 mb-2">Situação do Atendimento</label>
            <div class="flex gap-4">
                @foreach(['Pendente', 'Parcial', 'Atendida', 'Sem Estoque'] as $status)
                <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-4 py-2 rounded-lg border border-slate-200">
                    <input type="radio" name="situacao" value="{{ $status }}" {{ $requisicao->situacao == $status ? 'checked' : '' }}>
                    <span class="text-sm font-bold text-slate-600">{{ $status }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-xs font-black uppercase text-slate-400 mb-2">Observação</label>
            <textarea name="observacao" rows="3" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3"></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t">
            <a href="{{ route('requisicoes.index') }}" class="px-6 py-2.5 rounded-xl font-bold text-slate-500">Cancelar</a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-green-100">
                Finalizar Atendimento
            </button>
        </div>
    </form>
</div>
@endsection