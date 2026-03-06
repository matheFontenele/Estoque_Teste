<?php

namespace App\Http\Controllers;

// Importação dos Models
use App\Models\Requisicao;
use App\Models\Equipamento;
use App\Models\Cliente;

// Importações de Suporte
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequisicaoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequisicaoController extends Controller
{
    /**
     * Lista todas as requisições (Histórico)
     */
    public function index()
    {
        $requisicoes = Requisicao::with(['cliente', 'user'])->latest()->get();
        return view('requisicoes.index', compact('requisicoes'));
    }

    /**
     * Abre o formulário de nova requisição
     */
    public function create()
    {
        $clientes = Cliente::all();
        
        // Só permite selecionar equipamentos que estão no estoque (disponíveis)
        $equipamentosDisponiveis = Equipamento::where('situacao', 'disponivel')->get();
        
        return view('requisicoes.create', compact('clientes', 'equipamentosDisponiveis'));
    }

    /**
     * Salva a requisição e altera o estado do estoque
     */
    public function store(StoreRequisicaoRequest $request) 
    {
        try {
            DB::beginTransaction();

            // 1. Criar o registro da Requisição
            $requisicao = Requisicao::create([
                'data_prevista' => $request->data_prevista,
                'etiqueta'      => $request->etiqueta,
                'cliente_id'    => $request->cliente_id,
                'tipo'          => $request->tipo,
                'user_id'       => Auth::id(), 
                'observacao'    => $request->observacao
            ]);

            // 2. Lógica de Substituição: Libera o equipamento antigo
            if ($request->tipo === 'substituicao' && $request->tombo_antigo) {
                Equipamento::where('tombo', $request->tombo_antigo)->update([
                    'situacao'   => 'manutencao',
                    'cliente_id' => null
                ]);
            }

            // 3. Atualiza o NOVO equipamento: Aloca para o cliente
            $novoEquipamento = Equipamento::findOrFail($request->equipamento_id);
            $novoEquipamento->update([
                'situacao'   => 'alocado',
                'cliente_id' => $request->cliente_id
            ]);

            DB::commit();
            return redirect()->route('requisicoes.index')->with('success', 'Requisição e movimentação de estoque realizadas!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Erro ao processar: ' . $e->getMessage())->withInput();
        }
    }

    // Os demais métodos (show, edit, update, destroy) podem ser implementados conforme a necessidade.
}