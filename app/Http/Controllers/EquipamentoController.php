<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipamento;
use Illuminate\Support\Facades\DB;
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
        // 1. Validação básica (ajuste conforme sua necessidade)
        $request->validate([
            'itens' => 'required|array|min:1',
            'itens.*.nome' => 'required|string',
            'itens.*.categoria' => 'required|string',
            'itens.*.estoque_id' => 'required|exists:estoques,id',
        ]);

        try {
            // 2. Inicia uma transação para garantir que ou salva tudo ou nada
            DB::beginTransaction();

            foreach ($request->itens as $item) {
                // O Model Equipamento já cuidará de setar quantidade=1 
                // e campos 'N/A' via método booted que arrumamos.
                Equipamento::create($item);
            }

            DB::commit();

            return redirect()->route('equipamentos.index')
                ->with('success', count($request->itens) . ' itens cadastrados com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao salvar itens: ' . $e->getMessage());
        }
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
