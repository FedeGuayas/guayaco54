<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escenario extends Model
{
    const ACTIVO ='1';
    const INACTIVO ='0';


    protected $fillable = [
        'escenario','status'
    ];

    //obtener valores de la bbdd y mostrarlos en MAYUSCULAS
    public function getEscenarioAttribute($value)
    {
        return  mb_strtoupper($value);
    }

    //guardar valores de la bbdd en minusculas
    public function setEscenarioAttribute($value)
    {
        $this->attributes['escenario']=mb_strtolower($value);
    }

    /**
     *Relaciones
     */
    //en un escenario trabajan e inscriben muchos usuarios
    public function users(){
        return $this->hasMany('App\User');
    }


}
