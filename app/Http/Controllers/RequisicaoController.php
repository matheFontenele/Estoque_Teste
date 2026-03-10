<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Equipamento;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequisicaoController extends Controller
{
    public function index()
    {
        $requisicoes = Requisicao::with(['cliente', 'equipamento', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('requisicoes.index', compact('requisicoes'));
    }

    public function create()
    {
        // IMPORTANTE: Verifique se existem registros nestas tabelas no banco
        $usuarios = User::all();
        $clientes = Cliente::all();
        $equipamentos = Equipamento::where('quantidade_estoque', '>', 0)->get();

        return view('requisicoes.create', compact('usuarios', 'clientes', 'equipamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'equipamento_id'   => 'required|exists:equipamentos,id',
            'quantidade'       => 'required|integer|min:1',
            'user_id'          => 'required|exists:users,id',
            'envio'            => 'required',
            'etiqueta'         => 'required',
            'data_solicitacao' => 'required|date',
            'previsao'         => 'required|date',
            'estado'           => 'required',
            'cidade'           => 'required',
        ]);

        try {
            DB::beginTransaction();

            $equipamento = Equipamento::findOrFail($request->equipamento_id);

            if ($equipamento->quantidade_estoque < $request->quantidade) {
                return back()->withErrors(['quantidade' => 'Estoque insuficiente.'])->withInput();
            }

            $equipamento->decrement('quantidade_estoque', $request->quantidade);

            $requisicao = new Requisicao();
            $requisicao->cliente_id          = $request->cliente_id;
            $requisicao->equipamento_id      = $request->equipamento_id;
            $requisicao->user_id             = $request->user_id;
            $requisicao->quantidade          = $request->quantidade;
            $requisicao->envio               = $request->envio;
            $requisicao->etiqueta            = $request->etiqueta;
            $requisicao->estado              = $request->estado;
            $requisicao->cidade              = $request->cidade;
            $requisicao->data_prevista       = $request->previsao;
            $requisicao->previsao            = $request->previsao;
            $requisicao->is_substituicao     = $request->is_substituicao ?? 0;
            $requisicao->patrimonio_anterior = $request->patrimonio_anterior;
            $requisicao->save();

            DB::commit();

            return redirect()->route('requisicoes.index')->with('success', 'Requisição gerada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro: ' . $e->getMessage()])->withInput();
        }
    }
}
