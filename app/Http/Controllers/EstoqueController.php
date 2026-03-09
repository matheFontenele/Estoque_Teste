<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipamento; // Adicionado o ponto e vírgula aqui

class EstoqueController extends Controller
{
    /**
     * Exibe a listagem do estoque
     */
    public function index()
    {
        // Busca todos os equipamentos para listar na tabela de estoque
        $equipamentos = Equipamento::all();
        return view('estoque.index', compact('equipamentos'));
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
