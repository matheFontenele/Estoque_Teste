<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipamento;
use App\Models\Estoque;

class EstoqueController extends Controller
{
    /**
     * Exibe a listagem do estoque
     */
    public function index(Request $request)
    {
        $todosEstoques = \App\Models\Estoque::all();

        // Usamos with('estoque') para carregar o nome da unidade sem fazer várias consultas ao banco
        $query = \App\Models\Equipamento::with('estoque');

        if ($request->filled('estoque_id')) {
            $query->where('estoque_id', $request->estoque_id);
        }

        $equipamentos = $query->get();

        return view('estoque.index', compact('equipamentos', 'todosEstoques'));
    }

    /**
     * Atualiza a quantidade (Adicionar ou Remover)
     */
    public function update(Request $request)
    {
        // Validação básica para garantir que os dados cheguem corretamente
        $request->validate([
            'id' => 'required|exists:equipamentos,id',
            'quantidade' => 'required|integer|min:1',
            'type' => 'required|in:add,remove'
        ]);

        $item = Equipamento::findOrFail($request->id);
        $quantidade = (int) $request->quantidade;

        if ($request->type === 'add') {
            $item->increment('quantidade_estoque', $quantidade);
        } else {
            // Evita estoque negativo
            if ($item->quantidade_estoque < $quantidade) {
                return back()->with('error', 'Saldo insuficiente no estoque!');
            }
            $item->decrement('quantidade_estoque', $quantidade);
        }

        return back()->with('success', 'Estoque atualizado com sucesso!');
    }
}
