<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipamento;
use App\Models\Estoque;

class EquipamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pegamos todos os equipamentos e todos os estoques
        $equipamentos = \App\Models\Equipamento::with('estoque')->get();
        $estoques = \App\Models\Estoque::all();

        // Criamos o array de estatísticas que a View está pedindo
        $stats = [
            'total_equipamentos' => $equipamentos->count(),
            'disponivel' => $equipamentos->where('situacao', 'disponivel')->count(),
            'alocado' => $equipamentos->where('situacao', 'alocado')->count(),
        ];

        // Passamos tudo para a view
        return view('equipamentos.index', compact('equipamentos', 'estoques', 'stats'));
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
     * Remove the specified resource from storage.
     */
    public function destroy(Equipamento $equipamento)
    {
        $equipamento->delete();
        return back()->with('success', 'Equipamento removido!');
    }
}
