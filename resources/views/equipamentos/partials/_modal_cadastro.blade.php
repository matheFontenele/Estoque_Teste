<div x-show="openModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    style="display: none;" x-cloak>

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
                    <select x-model="categoriaSelecionada" name="categoria" @change="subcategoriaSelecionada = ''; novoItem.nome = ''" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold outline-none focus:ring-2 focus:ring-red-500/20">
                        <option value="Equipamentos">Equipamentos</option>
                        <option value="Insumos">Insumos</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Subcategoria</label>
                    <select x-model="subcategoriaSelecionada" name="subcategoria" required class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold outline-none focus:ring-2 focus:ring-red-500/20">
                        <option value="">Selecione...</option>
                        <template x-for="sub in opcoesSub[categoriaSelecionada]" :key="sub">
                            <option :value="sub" x-text="sub"></option>
                        </template>
                    </select>
                </div>
            </div>

            {{-- Campo Nome com Lista Baseada no Guia ADI --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest"
                    x-text="categoriaSelecionada === 'Insumos' ? 'Modelo do Toner (Guia ADI)' : 'Nome do Equipamento (Guia ADI)'"></label>

                <input type="text"
                    x-model="novoItem.nome"
                    name="nome"
                    {{-- CORREÇÃO AQUI: o nome no x-model deve bater com as IDs do datalist --}}
                    :list="categoriaSelecionada === 'Equipamentos' ? 'lista-maquinas-adi' : 'lista-toners-adi'"
                    placeholder="Digite para pesquisar..."
                    class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold focus:ring-2 focus:ring-red-500/20 outline-none transition-all">

                <datalist id="lista-maquinas-adi">
                    @foreach($modelosMaquinas as $maquina) <option value="{{ $maquina }}"> @endforeach
                </datalist>

                <datalist id="lista-toners-adi">
                    @foreach($modelosToners as $toner) <option value="{{ $toner }}"> @endforeach
                </datalist>
            </div>

            {{-- Campos para Equipamentos --}}
            <div x-show="categoriaSelecionada === 'Equipamentos'" x-transition class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase ml-1">Tombo</label>
                    <input type="text" x-model="novoItem.tombo" placeholder="00000" maxlength="5" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase ml-1">Nº de Série</label>
                    <input type="text" x-model="novoItem.serial" placeholder="SN123..." class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                </div>
            </div>

            {{-- Campos para Insumos --}}
            <div x-show="categoriaSelecionada === 'Insumos'" x-transition class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Cor</label>
                    <select x-model="novoItem.cor" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                        <option value="Mono">Mono</option>
                        <option value="Preto">Preto</option>
                        <option value="Cyano">Cyano</option>
                        <option value="Magenta">Magenta</option>
                        <option value="Amarelo">Amarelo</option>
                        <option value="Amarelo">Não se Aplica</option>

                    </select>
                </div>
                <div class="col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Qtd</label>
                    <input type="number" x-model="novoItem.quantidade" min="1" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Uso em:</label>
                    <input type="text" x-model="novoItem.compativel_com" placeholder="M1132..." class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Almoxarifado Destino</label>
                    <select x-model="novoItem.estoque_id" name="estoque_id" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                        <option value="">Selecione...</option>
                        @foreach($estoques as $estoque) <option value="{{ $estoque->id }}">{{ $estoque->nome }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Condição</label>
                    <select name="condicao" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold">
                        <option value="Disponivel">Disponível</option>
                        <option value="Manutenção">Manutenção</option>
                    </select>
                </div>
            </div>

            <button type="button" @click="adicionarAFila()" class="w-full py-4 rounded-2xl border-2 border-dashed border-red-200 text-red-500 font-bold hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                <i class="ph ph-plus-circle text-xl"></i> Adicionar à fila de processamento
            </button>

            {{-- Listagem da Fila --}}
            <div x-show="filaItens.length > 0" x-transition class="pt-4 border-t border-slate-100">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-3 tracking-widest">Itens para Salvar</label>
                <div class="space-y-2 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="(item, index) in filaItens" :key="index">
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div>
                                <p class="text-sm font-black text-slate-700" x-text="item.nome"></p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">
                                    <span x-text="item.subcategoria"></span>
                                    <span x-text="item.tombo ? ' | Tombo: '+item.tombo : ' | Qtd: '+item.quantidade"></span>
                                    <span x-show="item.cor !== 'Mono'" x-text="' | ' + item.cor"></span>
                                </p>
                            </div>
                            <button type="button" @click="removerDaFila(index)" class="w-8 h-8 flex items-center justify-center text-red-400 hover:bg-red-50 rounded-full transition-colors">
                                <i class="ph ph-trash"></i>
                            </button>

                            {{-- Inputs Ocultos --}}
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
            </div>

            <div class="flex gap-4 pt-4 sticky bottom-0 bg-white">
                <button type="button" @click="openModal = false" class="flex-1 py-4 font-bold text-slate-500 rounded-2xl hover:bg-slate-50 transition-colors">Cancelar</button>
                <button type="submit" class="flex-[2] py-4 font-black text-white bg-red-600 rounded-2xl shadow-lg hover:bg-red-700 transition-all disabled:opacity-50"
                    :disabled="filaItens.length === 0 && !novoItem.nome">
                    Gravar <span x-show="filaItens.length > 0" x-text="filaItens.length + (filaItens.length > 1 ? ' Itens' : ' Item')"></span>
                </button>
            </div>
        </form>
    </div>
</div>