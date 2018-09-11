<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asociado extends Model
{

    //un asociado pertenece a un usuario
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    //a un asociado pertenece un perfil
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }

}
