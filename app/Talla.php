<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talla extends Model
{
    const ACTIVO ='1'; //Disponible
    const INACTIVO ='0';

    public $timestamps=false;

    protected $fillable = [
        'talla','stock','color'
    ];

    protected $guarded=[
        'status'
    ];


    public function getColorAttribute($value)
    {
        return mb_strtoupper($value);
    }
    public function setColorAttribute($value)
    {
        $this->attributes['color'] =mb_strtolower($value);
    }

//    public function getColorAttribute($value)
//    {
//        if ($value==Talla::NEGRA){
//            return  'NEGRA';
//        }elseif ($this->attributes['color']==Talla::BLANCA){
//            return  'BLANCA';
//        }
//    }
//    public function setColorAttribute($value)
//    {
//        $color=strtolower($value);
//        if ($color=='negra' || $color=='n' ){
//            $this->attributes['color']=Talla::NEGRA;
//        }
//        if ($color=='blanca' || $color=='b'){
//            $this->attributes['color']=Talla::BLANCA;
//        }
//    }


    //una tallapuede estar en muchas inscripciones
    public function inscripciones(){
        return $this->hasMany('App\Inscripcion');
    }
}
