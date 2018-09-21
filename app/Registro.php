<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $fillable = [
        'numero','inscripcion_id', 'persona_id'
    ];

    //inscripcion a la que pertenece el registro
    public function inscripcion()
    {
        return $this->belongsTo('App\Inscripcion');
    }

    //persona a la que pertenece el registro
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
}
