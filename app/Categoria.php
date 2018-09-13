<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    const ACTIVO = '1';
    const INACTIVO = '0';


    protected $fillable = [
        'categoria', 'edad_start', 'edad_end'
    ];

    protected $guarded = [
        'status'
    ];

    public function getCategoriaAttribute($value)
    {
        return  mb_strtoupper($value);
    }

    public function setCategoriaAttribute($value)
    {
        $this->attributes['categoria'] = mb_strtolower($value);
    }


    /**
     * Relaciones
     */
    //una categoria puede estar en varios productos
    public function productos()
    {
        return $this->hasMany('App\Producto');
    }

    //los circuitos que pertenencen a la categoria
    public function circuitos()
    {
        return $this->belongsToMany('App\Circuito');
    }

}