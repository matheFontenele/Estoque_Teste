<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }
}
