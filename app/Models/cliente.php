<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nome', 'cnpj', 'estado', 'cidade', 'endereco', 'sla', 'contrato'];
    // Isso converte o JSON do banco para Array no PHP automaticamente
    protected $casts = [
        'sla' => 'array',
    ];
    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }

    public function requisicoes()
    {
        return $this->hasMany(Requisicao::class);
    }
}
