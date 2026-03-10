<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipamento;
use App\Models\Requisicao;
use App\Models\Estoque;

class EquipamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // 1. Busca os equipamentos com o filtro
        $equipamentos = Equipamento::query()
            ->with(['requisicoes' => function ($q) {
                $q->latest()->with('cliente');
            }])
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', "%{$search}%")
                    ->orWhere('patrimonio', 'like', "%{$search}%")
                    ->orWhere('categoria', 'like', "%{$search}%");
            })
            ->get();
        // 2. Busca a lista de estoques/unidades para o Modal de cadastro
        $estoques = Estoque::all();

        // 3. Organiza as estatísticas para os cards
        $stats = [
            'total_equipamentos' => Equipamento::count(),
            'disponivel'         => Equipamento::where('quantidade_estoque', '>', 0)->count(),
            'alocado'            => \App\Models\Requisicao::count(),
        ];

        // 4. Envia tudo para a View
        return view('equipamentos.index', compact('equipamentos', 'stats', 'estoques'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tombo' => 'required|string|unique:equipamentos,tombo', // Corrigido aqui
            'quantidade_estoque' => 'required|integer|min:0',
            'estoque_id' => 'required|exists:estoques,id',
            'situacao' => 'required|string'
        ]);

        \App\Models\Equipamento::create($request->all());

        return redirect()->route('equipamentos.index')->with('success', 'Item cadastrado com sucesso!');
    }
    /**
     * Apresentar detalhes dos itens
     */

    public function show($id)
    {
        // Carrega o equipamento e o histórico de requisições (para saber onde ele está)
        $equipamento = Equipamento::with(['requisicoes.cliente'])->findOrFail($id);

        // Pegamos a última movimentação para saber o local atual e a data
        $ultimaMovimentacao = $equipamento->requisicoes()->latest()->first();

        return view('equipamentos.show', compact('equipamento', 'ultimaMovimentacao'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipamento $equipamento)
    {
        $equipamento->delete();
        return back()->with('success', 'Equipamento removido!');
    }
}
