<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deporte extends Model
{
    const ACTIVO ='1';
    const INACTIVO ='0';

    public $timestamps=false;

    protected $fillable=[
        'deporte', 'status'
    ];

    public function getDeporteAttribute($value)
    {
        return  mb_strtoupper($value);
    }

    public function setDeporteAttribute($value)
    {
        $this->attributes['deporte']=mb_strtolower($value);
    }

}
