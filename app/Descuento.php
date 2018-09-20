<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    const ACTIVO ='1';
    const INACTIVO ='0';

    public $timestamps=false;

    protected $fillable=[
        'nombre','porciento','status'
    ];


    public function getNombreAttribute()
    {
        return  mb_strtoupper($this->attributes['nombre']);
    }

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre']=mb_strtolower($value);
    }

    public function appDescuento($valor)
    {
        return  $valor*($this->attributes['porciento']/100);
    }

}
