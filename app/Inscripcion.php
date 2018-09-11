<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{

    const RESERVADA = 'r';
    const PAGADA = 'p';
    const CANCELADA = 'c';

    const KIT_ENTREGADO = '1';

    protected $fillable = [
        'escenario_id', 'producto_id', 'persona_id', 'user_id', 'user_edit', 'deporte_id', 'factura_id', 'fecha', 'num_corredor', 'kit', 'talla_id', 'costo', 'ejercicio_id', 'status'
    ];

    /**
     * Relaciones
     **/
    //una inscripcion la realiza un empleado sino es inscripcion online
    public function user()
    {
        if (!isNull($this->attributes['user_id'])) { //user_id=>empleado
            return $this->belongsTo('App\User');
        }
    }
    //en una inscripcion hay un producto
    public function producto()
    {
        return $this->belongsTo('App\User');
    }

    //en una inscripcion hay una persona
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
    //en una inscripcion hay una talla
    public function talla()
    {
        return $this->belongsTo('App\Persona');
    }

}
