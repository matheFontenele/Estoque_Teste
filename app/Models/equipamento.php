<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    // O equipamento está em um cliente (quando alocado)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // O equipamento está em um local de estoque (quando disponível)
    public function estoque()
    {
        return $this->belongsTo(Estoque::class);
    }
}
