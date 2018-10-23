<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const PENDIENTE = '0';
    const CONFIRMADA = '1';
    const CANCELADA = '2';
    const RECHAZADA = '4';
    /**
     * Relaciones con los modelos
     **/
    //un pago es realizado por un usuario
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
