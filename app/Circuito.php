<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Circuito extends Model
{
    const ACTIVO ='1';
    const INACTIVO ='0';


    protected $fillable = [
        'circuito','title'
    ];

    protected $guarded = [
        'status'
    ];

    public function getCircuitoAttribute($value)
    {
        return  mb_strtoupper($value);
    }

    public function setCircuitoAttribute($value)
    {
        $this->attributes['circuito']=mb_strtolower($value);
    }

    public function getTitleAttribute($value)
    {
        return  mb_strtoupper($value);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title']=mb_strtolower($value);
    }

    /**
     * Relaciones
     */
    //un circuito puede estar en varios productos
    public function productos()
    {
        return $this->hasMany('App\Producto');
    }

    //las categorias que pertenencen al circuito
    public function categorias()
    {
        return $this->belongsToMany('App\Categoria');
    }
}
