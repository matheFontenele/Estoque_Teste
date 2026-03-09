<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nome', 'cnpj', 'estado', 'cidade'];
    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }

    public function requisicoes()
    {
        return $this->hasMany(Requisicao::class);
    }
}
