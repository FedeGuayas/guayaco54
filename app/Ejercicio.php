<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ejercicio extends Model
{
    const ACTIVO ='1';
    const INACTIVO ='0';

    public $timestamps=false;

    protected $fillable=[
        'year', 'status'
    ];

    //un ejercicio puede estar en varios productos
    public function productos()
    {
        return $this->hasMany('App\Producto');
    }
}
