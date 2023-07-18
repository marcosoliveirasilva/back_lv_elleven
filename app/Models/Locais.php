<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locais extends Model
{
    protected $fillable = [
        'descricao',
        'latitude',
        'longitude',
        'poligono',
        'raio',
    ];
}
