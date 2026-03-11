<?php

namespace App\Http\Controllers;

use App\Models\GuiaAdi;
use Illuminate\Http\Request;

class GuiaAdiController extends Controller
{
    // Mostra a lista de impressoras (Cards)
    public function index()
    {
        $guias = GuiaAdi::all();
        return view('guia_adi.index', compact('guias'));
    }

    // Mostra o formulário para cadastrar uma nova impressora
    public function create()
    {
        return view('guia_adi.create');
    }

    // Salva a nova impressora no banco
    public function store(Request $request)
    {
        // Aqui faremos a validação e o salvamento depois
    }

    // Mostra os detalhes de uma impressora específica
    public function show(GuiaAdi $guiaAdi)
    {
        return view('guia_adi.show', compact('guiaAdi'));
    }

    // Mostra o formulário de edição
    public function edit(GuiaAdi $guiaAdi)
    {
        return view('guia_adi.edit', compact('guiaAdi'));
    }

    // Atualiza a impressora no banco
    public function update(Request $request, GuiaAdi $guiaAdi)
    {
        // Lógica de atualização
    }

    // Deleta a impressora
    public function destroy(GuiaAdi $guiaAdi)
    {
        // Lógica de exclusão
    }
}
