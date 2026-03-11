<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    // Indique o nome da chave primária definido na migration
    protected $primaryKey = 'numero_requisicao';

    protected $casts = [
        'data_solicitacao' => 'date',
        'data_prevista' => 'date',
    ];

    protected $fillable = [
        'user_id', 
        'cliente_id', 
        'equipamento_id', 
        'data_solicitacao', 
        'data_prevista', 
        'envio', 
        'estado', 
        'cidade', 
        'etiqueta', 
        'quantidade', 
        'is_substituicao', 
        'patrimonio_anterior', 
        'situacao'
    ];

    // Relacionamentos
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function cliente() { return $this->belongsTo(Cliente::class, 'cliente_id'); }
    public function equipamento() { return $this->belongsTo(Equipamento::class, 'equipamento_id'); }
}