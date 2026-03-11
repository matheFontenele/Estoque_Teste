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

    public function dashboard()
    {
        $stats = [
            'total_equipamentos' => Equipamento::sum('quantidade_estoque'),
            'total_clientes' => Cliente::count(),
            'estoque_baixo' => Equipamento::where('quantidade_estoque', '<', 5)->count(),
            'requisicoes_recentes' => Requisicao::with(['cliente', 'equipamento'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'total_requisicoes' => Requisicao::count(),
        ];

        return view('dashboard', compact('stats'));
    }

    public function index()
    {
        $requisicoes = Requisicao::with(['cliente', 'equipamento', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('requisicoes.index', compact('requisicoes'));
    }

    public function create()
    {
        $usuarios = User::all();
        $clientes = Cliente::all();

        $equipamentos = Equipamento::where('quantidade_estoque', '>', 0)
            ->where(function ($query) {
                $query->where('condicao', 'Disponivel')
                    ->orWhereNull('condicao');
            })
            ->get();

        return view('requisicoes.create', compact('usuarios', 'clientes', 'equipamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'cliente_id' => 'required|exists:clientes,id',
            'equipamento_nome' => 'required|string',
            'data_solicitacao' => 'required|date',
            'previsao' => 'required|date',
            'quantidade' => 'required|integer|min:1',
            'envio' => 'required|string',
            'etiqueta' => 'required|string',
            'is_substituicao' => 'required|boolean',
        ]);

        $equipamento = \App\Models\Equipamento::where('nome', $request->equipamento_nome)->first();
        $cliente = \App\Models\Cliente::find($request->cliente_id);

        \App\Models\Requisicao::create([
            'user_id' => $request->user_id,
            'cliente_id' => $request->cliente_id,
            'equipamento_id' => $equipamento ? $equipamento->id : null,
            'data_solicitacao' => $request->data_solicitacao,
            'data_prevista' => $request->previsao,
            'quantidade' => $request->quantidade,
            'envio' => $request->envio,
            // Segurança: Se o form enviar nulo, pegamos do cadastro do cliente
            'estado' => $request->estado ?? $cliente->estado,
            'cidade' => $request->cidade ?? $cliente->cidade,
            'etiqueta' => $request->etiqueta,
            'is_substituicao' => $request->is_substituicao,
            'patrimonio_anterior' => $request->patrimonio_anterior,
            'situacao' => 'Pendente', // Forçamos o início como Pendente
        ]);

        return redirect()->route('requisicoes.index')->with('success', 'Requisição gravada com sucesso!');
    }

    //Funções responsaveis por atendimento de requisições
    public function atender($id)
    {
        $requisicao = Requisicao::with(['cliente', 'equipamento'])->findOrFail($id);
        return view('requisicoes.atender', compact('requisicao'));
    }

    public function processarAtendimento(Request $request, $id)
    {
        $requisicao = Requisicao::findOrFail($id);

        // Atualiza a situação e outros campos (ideal ter esses campos na migration)
        $requisicao->update([
            'situacao' => $request->situacao,
            // Caso você adicione as colunas de separação na tabela:
            // 'quantidade_separada' => $request->quantidade_separada,
            // 'data_separacao' => $request->data_separacao,
        ]);

        // Lógica opcional: Se for 'Atendida', você pode dar baixa no estoque aqui
        if ($request->situacao == 'Atendida') {
            $requisicao->equipamento->decrement('quantidade_estoque', $request->quantidade_separada);
        }

        return redirect()->route('requisicoes.index')->with('success', 'Atendimento registrado com sucesso!');
    }

    public function show($id)
    {
        $requisicao = Requisicao::with(['cliente', 'equipamento', 'user'])->findOrFail($id);
        return view('requisicoes.show', compact('requisicao'));
    }
}
