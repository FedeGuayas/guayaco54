<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    const ATIVO='1';
    const INATIVO='0';

    public $timestamps=false;

    protected $fillable=[
        'ejercicio_id','impuesto_id','empresa','telefonos','ruc','email','direccion','nombre_contacto','status'
    ];

    protected $hidden = [
        'client_app_code', 'client_app_key','server_app_code','server_app_key'
    ];

    public function getEmpresaAttribute($value)
    {
        return  mb_strtoupper($value);
    }

    public function setEmpresaAttribute($value)
    {
        $this->attributes['empresa']=mb_strtolower($value);
    }


    public function setEmailAttribute($value)
    {
        $this->attributes['email']=strtolower($value);
    }

    public function getDireccionAttribute($value)
    {
        return  mb_strtoupper($value);
    }

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion']=mb_strtolower($value);
    }

    public function getNombreContactoAttribute($value)
    {
        return  mb_strtoupper($value);
    }

    public function setNombreContactoAttribute($value)
    {
        $this->attributes['nombre_contacto']=mb_strtolower($value);
    }

    /**
     * Relaciones
     */

    public function ejercicio()
    {
        return $this->belongsTo('App\Ejercicio');
    }

    public function impuesto()
    {
        return $this->belongsTo('App\Impuesto');
    }

}
