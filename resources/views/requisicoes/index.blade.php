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
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Cliente / Destino</th>
                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Equipamento</th>
                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Etiqueta</th>
                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Data Prevista</th>
                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($requisicoes as $req)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="p-4">
                    <div class="font-semibold text-slate-700">{{ $req->cliente->nome }}</div>
                    <div class="text-xs text-slate-400 capitalize">{{ $req->tipo }}</div>
                </td>
                <td class="p-4 text-slate-600 text-sm">
                    <code class="bg-slate-100 px-2 py-1 rounded text-blue-600 font-mono">{{ $req->equipamento->tombo ?? 'N/A' }}</code>
                </td>
                <td class="p-4">
                    @php
                        $colors = [
                            'ZapLoc' => 'bg-green-100 text-green-700 border-green-200',
                            'Moreia' => 'bg-purple-100 text-purple-700 border-purple-200',
                            'Alucom' => 'bg-orange-100 text-orange-700 border-orange-200',
                        ];
                        $badgeClass = $colors[$req->etiqueta] ?? 'bg-slate-100 text-slate-600';
                    @endphp
                    <span class="{{ $badgeClass }} border px-3 py-1 rounded-full text-xs font-bold">
                        {{ $req->etiqueta }}
                    </span>
                </td>
                <td class="p-4 text-sm text-slate-500 italic">
                    {{ \Carbon\Carbon::parse($req->data_prevista)->diffForHumans() }}
                </td>
                <td class="p-4 text-right">
                    <button class="text-slate-400 hover:text-blue-600 transition p-2">
                        <i class="ph ph-printer text-xl"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection