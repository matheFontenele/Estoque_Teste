<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    protected $primaryKey = 'numero_requisicao';

    public $incrementing = true;

    protected $fillable = [
        'cliente_id', 
        'equipamento_id', 
        'user_id', 
        'quantidade', 
        'envio', 
        'etiqueta', 
        'estado', 
        'cidade', 
        'data_prevista', 
        'is_substituicao', 
        'patrimonio_anterior'
    ];

    protected $casts = [
        'is_substituicao' => 'boolean',
        'data_prevista' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}