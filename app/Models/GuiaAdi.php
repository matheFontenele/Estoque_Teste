<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuiaAdi extends Model
{
    use HasFactory;

    protected $fillable = [
        'fabricante',
        'marca_modelo',
        'familia',
        'foto',
        'toner',
        'rendimento',
        'ppm',
        'papel',
        'voltagem',
        'funcoes',
        'resolucao',
        'memoria',
        'hdd',
        'duplex',
        'capacidade_papel',
        'pecas',
        'cartao_sd',
        'ndd',
        'obs'
    ];

    // Isso converte automaticamente o array do formulário para JSON no banco e vice-versa
    protected $casts = [
        'funcoes' => 'array',
    ];
}
