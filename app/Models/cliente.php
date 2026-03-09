<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nome', 'email', 'telefone'];

    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }

    public function requisicoes()
    {
        return $this->hasMany(Requisicao::class);
    }
}
