@extends('layouts.app')

@section('content')
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
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase">Nome</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase">E-mail</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase text-center">Telefone</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($clientes as $cliente)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-700">{{ $cliente->nome }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $cliente->email ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-center text-slate-500">{{ $cliente->telefone ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-slate-400 italic">Nenhum cliente cadastrado.</td>
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
            <form action="{{ route('clientes.store') }}" method="POST" class="p-8 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Nome</label>
                    <input type="text" name="nome" required class="w-full rounded-xl border-slate-200 p-3 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">E-mail</label>
                    <input type="email" name="email" class="w-full rounded-xl border-slate-200 p-3 bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase mb-2">Telefone</label>
                    <input type="text" name="telefone" class="w-full rounded-xl border-slate-200 p-3 bg-slate-50">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openModal = false" class="flex-1 py-3 font-bold text-slate-500">Cancelar</button>
                    <button type="submit" class="flex-1 py-3 font-black text-white bg-red-600 rounded-xl">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection