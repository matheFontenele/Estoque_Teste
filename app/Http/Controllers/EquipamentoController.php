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

        $equipamentos = Equipamento::query()
            ->with(['requisicoes' => function ($q) {
                $q->latest()->with('cliente');
            }])
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', "%{$search}%")
                    ->orWhere('tombo', 'like', "%{$search}%") // Corrigido para tombo
                    ->orWhere('categoria', 'like', "%{$search}%");
            })
            ->latest() // Traz os mais novos primeiro
            ->get();

        $estoques = Estoque::all();

        $stats = [
            'total_equipamentos' => Equipamento::count(),
            'disponivel'         => Equipamento::where('quantidade_estoque', '>', 0)->count(),
            'alocado'            => \App\Models\Requisicao::count(),
        ];

        return view('equipamentos.index', compact('equipamentos', 'stats', 'estoques'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Se vierem itens da fila (múltiplos)
        if ($request->has('itens')) {
            foreach ($request->itens as $item) {
                \App\Models\Equipamento::create([
                    'nome'               => $item['nome'],
                    'categoria'          => $item['categoria'],
                    'tombo'              => $item['tombo'], // Alterado de patrimonio para tombo
                    'quantidade_estoque' => $item['quantidade_estoque'] ?? 1,
                    'estoque_id'         => $item['estoque_id'],
                    'situacao'           => 'disponivel',
                ]);
            }
        }
        // 2. Se for um cadastro único (clicou em salvar sem adicionar à fila)
        else {
            $request->validate([
                'nome' => 'required|string|max:255',
                'estoque_id' => 'required|exists:estoques,id',
            ]);

            \App\Models\Equipamento::create([
                'nome'               => $request->nome,
                'categoria'          => $request->categoria ?? 'equipamento',
                'tombo'              => $request->tombo, // Alterado de patrimonio para tombo
                'quantidade_estoque' => $request->quantidade_estoque ?? 1,
                'estoque_id'         => $request->estoque_id,
                'situacao'           => 'disponivel',
            ]);
        }

        return redirect()->route('equipamentos.index')->with('success', 'Itens cadastrados com sucesso!');
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
