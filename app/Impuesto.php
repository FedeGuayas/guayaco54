<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    const ACTIVO ='1';
    const INACTIVO ='0';

    public $timestamps=false;

    protected $fillable=[
        'nombre','porciento','divisor','status'
    ];

    public function getNombreAttribute()
    {
        return  strtoupper($this->attributes['nombre']);
    }

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre']=strtolower($value);
    }

//    public function getDivisorAttribute()
//    {
//        return  $this->attributes['divisor']/100;
//    }
//
//    public function setDivisorAttribute($value)
//    {
//        //1.14  1.12 * 100 = 114 112
//        $this->attributes['divisor']=$value*100;
//    }

}
