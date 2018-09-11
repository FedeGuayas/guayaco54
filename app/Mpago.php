<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mpago extends Model
{
    const ACTIVO ='1';
    const INACTIVO ='0';

    public $timestamps=false;

    protected $fillable = [
        'nombre','descripcion','status'
    ];


    public function setNombreAttribute($value)
    {
        $this->attributes['nombre']=strtolower($value);
    }

    public function getNombreAttribute()
    {
        return strtoupper($this->attributes['nombre']);

    }

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion']=strtolower($value);
    }

    public function getDescripcionAttribute()
    {
        return strtoupper($this->attributes['descripcion']);

    }

    /**
     *Relaciones
     */
    //un medio de pago puede estar en varias facturas
    public function facturas(){
        return $this->hasMany('App\Factura');
    }



}
