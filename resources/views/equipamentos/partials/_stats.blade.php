<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Itens</p>
        <h4 class="text-xl font-black text-slate-800">{{ $stats['total_equipamentos'] }}</h4>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-xs font-black text-emerald-500 uppercase tracking-wider">Disponíveis</p>
        <h4 class="text-xl font-black text-slate-800">{{ $stats['disponivel'] }}</h4>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-xs font-black text-amber-500 uppercase tracking-wider">Alocados</p>
        <h4 class="text-xl font-black text-slate-800">{{ $stats['alocado'] }}</h4>
    </div>
    <button @click="openModal = true" class="bg-red-600 hover:bg-red-700 text-white rounded-2xl font-bold flex items-center justify-center gap-2 transition-all active:scale-95 shadow-lg shadow-red-200">
        <i class="ph ph-plus-circle text-xl"></i>
        Novo Item
    </button>
</div>