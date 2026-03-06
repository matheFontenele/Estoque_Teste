<div class="space-y-1">
    <label class="text-sm font-bold text-slate-700">Selecione o Cliente</label>
    <div class="relative">
        <select name="cliente_id" class="appearance-none w-full bg-slate-50 border border-slate-200 text-slate-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all">
            @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
            <i class="ph ph-caret-down"></i>
        </div>
    </div>
</div>