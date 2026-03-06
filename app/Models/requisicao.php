<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    protected $primaryKey = 'numero_requisicao'; // Como você definiu esse nome na migration

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function user() // Responsável pela separação
    {
        return $this->belongsTo(User::class);
    }
}
