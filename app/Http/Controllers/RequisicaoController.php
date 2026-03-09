<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Equipamento;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequisicaoController extends Controller
{
    public function index()
    {
        $requisicoes = Requisicao::with(['cliente', 'user', 'equipamento'])->latest()->get();
        return view('requisicoes.index', compact('requisicoes'));
    }

    public function create()
    {
        $usuarios = User::all();
        $clientes = Cliente::all();
        // Carrega equipamentos e sua quantidade disponível
        $equipamentos = Equipamento::where('situacao', 'disponivel')->get();

        return view('requisicoes.create', compact('usuarios', 'clientes', 'equipamentos'));
    }

    public function store(Request $request)
    {
        // Validação das regras de negócio
        $request->validate([
            'equipamento_id' => 'required|exists:equipamentos,id',
            'quantidade' => 'required|integer|min:1',
            'is_substituicao' => 'required|boolean',
            'patrimonio_anterior' => 'required_if:is_substituicao,1',
            'envio' => 'required|in:Rota,Transportadora,Correios',
            'etiqueta' => 'required|in:Alucom,Moreia,ZapLoc',
        ]);

        try {
            DB::beginTransaction();

            // 1. Validar estoque disponível (Regra: não exceder o total)
            $equipamento = Equipamento::findOrFail($request->equipamento_id);
            if ($request->quantidade > $equipamento->quantidade_estoque) {
                return back()->withErrors(['quantidade' => 'A quantidade excede o estoque disponível!'])->withInput();
            }

            // 2. Criar a Requisição
            $requisicao = Requisicao::create([
                'user_id'             => $request->user_id, // Solicitado
                'data_solicitacao'    => $request->data_solicitacao ?? now(),
                'previsao'            => $request->previsao,
                'envio'               => $request->envio,
                'estado'              => $request->estado,
                'cidade'              => $request->cidade,
                'cliente_id'          => $request->cliente_id,
                'etiqueta'            => $request->etiqueta,
                'quantidade'          => $request->quantidade,
                'equipamento_id'      => $request->equipamento_id,
                'is_substituicao'     => $request->is_substituicao,
                'patrimonio_anterior' => $request->patrimonio_anterior,
                'observacao'          => $request->observacao
            ]);

            // 3. Atualizar estoque ou situação
            $equipamento->decrement('quantidade_estoque', $request->quantidade);
            if($equipamento->quantidade_estoque <= 0) {
                $equipamento->update(['situacao' => 'alocado']);
            }

            DB::commit();
            return redirect()->route('requisicoes.index')->with('success', 'Requisição nº ' . $requisicao->id . ' criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Erro: ' . $e->getMessage())->withInput();
        }
    }
}