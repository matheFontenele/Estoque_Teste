<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipamento;

class EquipamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. BUSCAR A LISTA
        $equipamentos = Equipamento::all();

        // 2. DEFINIR AS ESTATÍSTICAS
        $stats = [
            'total_clientes' => \App\Models\Cliente::count(),
            'total_equipamentos' => Equipamento::count(),
            'disponivel' => Equipamento::where('situacao', 'disponivel')->count(),
            'alocado' => Equipamento::where('situacao', 'alocado')->count(),
            'manutencao' => Equipamento::where('situacao', 'manutencao')->count(),
            'estoque_baixo' => Equipamento::where('quantidade_estoque', '<=', 5)->count(),
        ];

        // Enviando tanto a lista quanto as estatísticas
        return view('equipamentos.index', compact('equipamentos', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'patrimonio' => 'required|string|unique:equipamentos,patrimonio',
            'quantidade_estoque' => 'required|integer|min:0',
        ]);

        Equipamento::create($request->all());

        return back()->with('success', 'Equipamento cadastrado com sucesso!');
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