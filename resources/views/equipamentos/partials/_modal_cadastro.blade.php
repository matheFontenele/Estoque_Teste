<div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-cloak>
    <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[95vh]">
        
        <div class="bg-slate-900 p-6 text-white flex justify-between items-center shrink-0">
            <h3 class="font-black text-xl flex items-center gap-2 tracking-tight">
                <i class="ph ph-package text-red-500"></i> Entrada de Materiais
            </h3>
            <button @click="openModal = false" class="hover:rotate-90 transition-transform duration-300">
                <i class="ph ph-x text-2xl text-slate-400"></i>
            </button>
        </div>

        <form action="{{ route('equipamentos.store') }}" method="POST" class="p-8 space-y-6 overflow-y-auto custom-scrollbar">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Categoria Principal</label>
                    <select x-model="categoriaSelecionada" name="categoria" @change="subcategoriaSelecionada = ''" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold appearance-none">
                        <option value="Equipamentos">Equipamentos</option>
                        <option value="Insumos">Insumos</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Subcategoria</label>
                    <select x-model="subcategoriaSelecionada" name="subcategoria" required class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold appearance-none">
                        <option value="">Selecione...</option>
                        <template x-for="sub in opcoesSub[categoriaSelecionada]" :key="sub">
                            <option :value="sub" x-text="sub"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest" x-text="categoriaSelecionada === 'Insumos' ? 'Modelo/Nome do Item' : 'Nome do Equipamento'"></label>
                <input type="text" x-model="novoItem.nome" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
            </div>

            <div x-show="categoriaSelecionada === 'Equipamentos'" x-transition class="grid grid-cols-2 gap-4">
                <input type="text" x-model="novoItem.tombo" placeholder="Tombo (5 Dígitos)" maxlength="5" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                <input type="text" x-model="novoItem.serial" placeholder="Nº de Série" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
            </div>

            <div x-show="categoriaSelecionada === 'Insumos'" x-transition class="grid grid-cols-3 gap-4">
                <select x-model="novoItem.cor" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                    <option value="Preto">Preto</option><option value="Mono">Mono</option><option value="Não se Aplica">N/A</option>
                </select>
                <input type="text" x-model="novoItem.compativel_com" placeholder="Compatível com..." class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                <input type="number" x-model="novoItem.quantidade" placeholder="Qtd" min="1" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <select x-model="novoItem.estoque_id" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                    <option value="">Almoxarifado...</option>
                    @foreach($estoques as $estoque) <option value="{{ $estoque->id }}">{{ $estoque->nome }}</option> @endforeach
                </select>
                <select name="condicao" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                    <option value="Disponivel">Disponivel</option><option value="Manutenção">Manutenção</option>
                </select>
            </div>

            <button type="button" @click="adicionarAFila()" class="w-full py-4 rounded-2xl border-2 border-dashed border-red-200 text-red-500 font-bold hover:bg-red-50 transition-all">
                + Adicionar à fila de processamento
            </button>

            <div x-show="filaItens.length > 0" class="space-y-2">
                <template x-for="(item, index) in filaItens" :key="index">
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <div>
                            <p class="text-sm font-black text-slate-700" x-text="item.nome"></p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase" x-text="item.subcategoria + (item.tombo ? ' | T: '+item.tombo : ' | Q: '+item.quantidade)"></p>
                        </div>
                        <button type="button" @click="removerDaFila(index)" class="text-red-400 hover:text-red-600"><i class="ph ph-trash"></i></button>
                        
                        <input type="hidden" :name="'itens['+index+'][nome]'" :value="item.nome">
                        <input type="hidden" :name="'itens['+index+'][categoria]'" :value="item.categoria">
                        <input type="hidden" :name="'itens['+index+'][subcategoria]'" :value="item.subcategoria">
                        <input type="hidden" :name="'itens['+index+'][tombo]'" :value="item.tombo">
                        <input type="hidden" :name="'itens['+index+'][serial]'" :value="item.serial">
                        <input type="hidden" :name="'itens['+index+'][quantidade_estoque]'" :value="item.quantidade">
                        <input type="hidden" :name="'itens['+index+'][cor]'" :value="item.cor">
                        <input type="hidden" :name="'itens['+index+'][compativel_com]'" :value="item.compativel_com">
                        <input type="hidden" :name="'itens['+index+'][estoque_id]'" :value="item.estoque_id">
                    </div>
                </template>
            </div>

            <div class="flex gap-4 pt-4 sticky bottom-0 bg-white">
                <button type="button" @click="openModal = false" class="flex-1 py-4 font-bold text-slate-500 rounded-2xl">Cancelar</button>
                <button type="submit" class="flex-[2] py-4 font-black text-white bg-red-600 rounded-2xl shadow-lg hover:bg-red-700 transition-all">
                    Finalizar e Gravar Tudo <span x-show="filaItens.length > 0" x-text="'(' + filaItens.length + ')'"></span>
                </button>
            </div>
        </form>
    </div>
</div>