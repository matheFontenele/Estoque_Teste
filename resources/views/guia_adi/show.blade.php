@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    {{-- Cabeçalho com Botão Voltar --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('guia-adi.index') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm hover:bg-slate-50 transition-all">
            <i class="ph ph-caret-left font-bold"></i>
        </a>
        <div>
            <span class="text-xs font-black text-red-600 uppercase tracking-widest">{{ $guiaAdi->fabricante }}</span>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $guiaAdi->marca_modelo }}</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Coluna da Foto (Placeholder) --}}
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 aspect-square flex items-center justify-center">
                @if($guiaAdi->foto)
                    <img src="{{ asset('storage/' . $guiaAdi->foto) }}" alt="{{ $guiaAdi->marca_modelo }}" class="max-w-full h-auto">
                @else
                    <div class="text-center">
                        <i class="ph ph-printer text-6xl text-slate-200"></i>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mt-2">Sem imagem</p>
                    </div>
                @endif
            </div>
            
            {{-- Card Rápido de Insumo --}}
            <div class="mt-4 bg-slate-900 rounded-3xl p-6 text-white">
                <p class="text-[10px] font-black uppercase text-slate-400 mb-4 tracking-widest">Insumo Principal</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                        <i class="ph ph-drop text-red-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400">Toner</p>
                        <p class="font-black text-lg">{{ $guiaAdi->toner }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detalhes Técnicos --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 px-8 py-4 border-b border-slate-100">
                    <h3 class="font-black text-slate-700 uppercase text-xs tracking-widest">Especificações Completas</h3>
                </div>
                
                <div class="p-8 grid grid-cols-2 gap-y-6 gap-x-12">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Velocidade</p>
                        <p class="font-bold text-slate-700">{{ $guiaAdi->ppm }} PPM</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rendimento Toner</p>
                        <p class="font-bold text-slate-700">{{ $guiaAdi->rendimento }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Duplex Nativo</p>
                        <p class="font-bold text-slate-700">{{ $guiaAdi->duplex }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Resolução</p>
                        <p class="font-bold text-slate-700">{{ $guiaAdi->resolucao }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Memória</p>
                        <p class="font-bold text-slate-700">{{ $guiaAdi->memoria }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Voltagem</p>
                        <p class="font-bold text-slate-700">{{ $guiaAdi->voltagem }}</p>
                    </div>
                </div>

                <div class="px-8 pb-8">
                     <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Funções</p>
                     <div class="flex flex-wrap gap-2">
                        @foreach($guiaAdi->funcoes as $funcao)
                            <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-black uppercase">{{ $funcao }}</span>
                        @endforeach
                     </div>
                </div>
            </div>

            {{-- Observações --}}
            @if($guiaAdi->obs)
            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-6">
                <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2">Observação Técnica</p>
                <p class="text-sm text-amber-900 leading-relaxed font-medium italic">"{{ $guiaAdi->obs }}"</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection