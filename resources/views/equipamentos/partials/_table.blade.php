<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Categoria / Nome</th>
                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Identificação / Detalhes</th>
                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-center">Situação</th>
                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase">Localização Atual</th>
                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($equipamentos as $item)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex flex-col gap-1">
                        <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase inline-block w-fit 
                            {{ $item->categoria === 'Equipamentos' ? 'bg-red-50 text-red-600' : 'bg-purple-50 text-purple-600' }}">
                            {{ $item->subcategoria }}
                        </span>
                        <span class="font-bold text-slate-700 block">{{ $item->nome }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-xs">
                    @if($item->categoria === 'Insumos')
                        <div class="flex flex-col gap-0.5">
                            <span class="text-slate-600"><strong>Cor:</strong> {{ $item->cor }}</span>
                            <span class="text-slate-400 italic font-medium">Qtd: {{ $item->quantidade_estoque }}</span>
                        </div>
                    @else
                        <div class="flex flex-col gap-0.5">
                            <span class="text-slate-600"><strong class="text-slate-400 uppercase text-[9px]">Tombo:</strong> {{ $item->tombo ?? '---' }}</span>
                            <span class="text-slate-600"><strong class="text-slate-400 uppercase text-[9px]">Série:</strong> {{ $item->serial ?? '---' }}</span>
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase border {{ $item->quantidade_estoque > 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                        {{ $item->quantidade_estoque > 0 ? 'Disponível' : 'Alocado' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-600">
                        <i class="ph ph-map-pin {{ $item->quantidade_estoque > 0 ? 'text-emerald-400' : 'text-red-400' }}"></i>
                        <span>{{ $item->estoque->nome ?? 'N/A' }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('equipamentos.show', $item->id) }}" class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-red-600 hover:text-white transition-all">
                        <i class="ph ph-eye font-bold"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>