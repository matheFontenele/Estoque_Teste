@extends('layouts.app')
@section('subtitle', 'Histórico de Movimentações')

@section('content')
<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Requisições</h1>
        <p class="text-slate-500">Gerencie e acompanhe o fluxo de equipamentos.</p>
    </div>
    <a href="{{ route('requisicoes.create') }}" class="bg-red-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl flex items-center gap-2 transition-all shadow-lg shadow-blue-200">
        <i class="ph ph-plus-circle text-xl"></i> Nova Requisição
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left text-slate-500">
        <thead class="text-xs text-slate-700 uppercase bg-slate-50">
            <tr>
                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Solicitação</th>
                <th class="px-6 py-3">Envio</th>
                <th class="px-6 py-3">Cliente</th>
                <th class="px-6 py-3">Item</th>
                <th class="px-6 py-3 text-center">Situação</th>
                <th class="px-6 py-3">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requisicoes as $req)
            <tr class="bg-white border-b hover:bg-slate-50">
                <td class="px-6 py-4 font-bold">#{{ $req->numero_requisicao }}</td>
                <td class="px-6 py-4">{{ $req->data_solicitacao->format('d/m/Y') }}</td>
                <td class="px-6 py-4">{{ $req->envio }}</td>
                <td class="px-6 py-4">{{ $req->cliente->nome }}</td>
                <td class="px-6 py-4 font-semibold text-slate-700">
                    {{ $req->equipamento->nome ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 text-center">
                    @php
                    $badgeStyle = match($req->situacao) {
                    'Atendida' => 'bg-green-100 text-green-700 border-green-200',
                    'Parcial' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'Sem Estoque' => 'bg-orange-100 text-orange-700 border-orange-200',
                    'Cancelada' => 'bg-red-100 text-red-700 border-red-200',
                    'Pendente' => 'bg-slate-100 text-slate-700 border-slate-200',
                    default => 'bg-gray-100 text-gray-700'
                    };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeStyle }}">
                        {{ $req->situacao }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('requisicoes.show', $req->numero_requisicao) }}" class="text-blue-500 hover:text-blue-700" title="Detalhes">
                            <i class="ph ph-eye text-xl"></i>
                        </a>

                        @if(auth()->id() == $req->user_id)
                        <a href="{{ route('requisicoes.edit', $req->numero_requisicao) }}" class="text-amber-500 hover:text-amber-700" title="Editar">
                            <i class="ph ph-pencil-line text-xl"></i>
                        </a>
                        @endif

                        <a href="{{ route('requisicoes.atender', $req->numero_requisicao) }}" class="text-green-500 hover:text-green-700" title="Atender Requisição">
                            <i class="ph ph-check-circle text-xl"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection