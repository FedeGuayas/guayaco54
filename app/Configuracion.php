<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    public $timestamps=false;

    protected $fillable=[
        'ejercicio_id', 'status'
    ];
}
