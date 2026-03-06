@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Histórico de Requisições</h2>
    <a href="{{ route('requisicoes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
        + Nova Requisição
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-3 px-4 text-left">Data Prevista</th>
                <th class="py-3 px-4 text-left">Cliente</th>
                <th class="py-3 px-4 text-left">Etiqueta</th>
                <th class="py-3 px-4 text-left">Tipo</th>
                <th class="py-3 px-4 text-left">Responsável</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @forelse($requisicoes as $req)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($req->data_prevista)->format('d/m/Y') }}</td>
                <td class="py-3 px-4">{{ $req->cliente->nome }}</td>
                <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded text-xs font-bold 
                        {{ $req->etiqueta == 'ZapLoc' ? 'bg-green-200 text-green-800' : 'bg-blue-200 text-blue-800' }}">
                        {{ $req->etiqueta }}
                    </span>
                </td>
                <td class="py-3 px-4 capitalize">{{ $req->tipo }}</td>
                <td class="py-3 px-4">{{ $req->user->name ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 text-center text-gray-500">Nenhuma requisição encontrada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection