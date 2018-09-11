<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $fillable = [
        'numero','inscripcion_id', 'persona_id'
    ];
}
