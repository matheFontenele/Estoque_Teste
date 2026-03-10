@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <form action="{{ route('clientes.index') }}" method="GET" class="w-full md:w-96">
        <div class="relative">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Buscar por nome ou CNPJ..."
                class="w-full rounded-2xl border-slate-200 bg-white p-3 pl-11 font-bold outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                <i class="ph ph-magnifying-glass font-bold"></i>
            </div>
        </div>
    </form>
</div>


<div class="max-w-6xl mx-auto py-8 px-4" x-data="{ openModal: false }">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Gerenciamento de Clientes</h1>
            <p class="text-slate-500 text-sm">Cadastre e visualize seus clientes ativos.</p>
        </div>
        <button @click="openModal = true" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-red-200">
            Novo Cliente
        </button>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Razão Social</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">CNPJ</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Localização</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($clientes as $cliente)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-sm text-slate-500">#{{ $cliente->id }}</td>
                    <td class="px-6 py-4 font-bold text-slate-700">{{ $cliente->nome }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $cliente->cnpj }}</td>
                    <td class="px-6 py-4 text-slate-500">
                        <span class="inline-flex items-center gap-1">
                            <i class="ph ph-map-pin text-red-500"></i>
                            {{ $cliente->cidade }} - {{ $cliente->estado }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <a href="{{ route('clientes.show', $cliente->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors">
                            <i class="ph ph-eye text-xl"></i>
                        </a>

                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" onsubmit="return confirm('Deseja excluir este cliente?');">
                            @csrf @method('DELETE')
                            <button class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                                <i class="ph ph-trash text-xl"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                        @if(request('search'))
                        Nenhum cliente encontrado para "{{ request('search') }}".
                        @else
                        Nenhum cliente cadastrado.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="bg-slate-900 p-6 text-white">
                <h3 class="font-black text-xl">Cadastrar Cliente</h3>
            </div>
            <form action="{{ route('clientes.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Razão Social / Nome</label>
                    <input type="text" name="nome" required placeholder="Ex: Supermercado Alvorada" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">CNPJ</label>
                    <input type="text" name="cnpj" required placeholder="00.000.000/0000-00" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1">
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">UF</label>
                        <input type="text" name="estado" required maxlength="2" placeholder="CE" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500 text-center uppercase">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">Cidade</label>
                        <input type="text" name="cidade" required placeholder="Fortaleza" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openModal = false" class="flex-1 py-3 font-bold text-slate-500">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 font-black text-white bg-red-600 rounded-xl shadow-lg shadow-red-200">Cadastrar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection