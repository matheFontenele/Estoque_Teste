<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipamento extends Model
{
    // 1. Defina quais campos podem ser preenchidos em massa
    protected $fillable = [
        'nome',
        'categoria',
        'subcategoria',
        'tombo',
        'serial',
        'quantidade_estoque',
        'condicao',
        'cor',
        'compativel_com',
        'descricao',
        'estoque_id'
    ];

    protected static function booted()
    {
        static::saving(function ($item) {
            // Ajustamos para o nome correto da coluna: quantidade_estoque
            if ($item->categoria === 'Equipamentos') {
                $item->quantidade_estoque = 1; 
                $item->cor = 'Não se Aplica';
                $item->compativel_com = 'Não se Aplica';
            }
        });
    }

    // O equipamento está em um local de estoque (quando disponível)
    public function estoque(): BelongsTo
    {
        return $this->belongsTo(Estoque::class);
    }

    // Historico de requisições
    public function requisicoes(): HasMany
    {
        return $this->hasMany(Requisicao::class);
    }

    /**
     * Helper para pegar o cliente atual através da última requisição ativa
     */
    public function getClienteAtualAttribute()
    {
        return $this->requisicoes()->latest()->first()?->cliente;
    }
}