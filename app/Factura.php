<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    const ACTIVA = '1';
    const CANCELADA = '0';

    protected $fillable = [
        'numero','fecha_edit','descuento','subtotal','total','user_id','persona_id','nombre','email','direccion','telefono','identificacion','mpago_id','payment_id','status'
    ];

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre']=mb_strtolower($value);
    }

    public function getNombreAttribute($value)
    {
        return  mb_strtoupper($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email']=strtolower($value);
    }

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion']=mb_strtolower($value);
    }

    public function getDireccionAttribute($value)
    {
        return  mb_strtoupper($value);
    }

    //como payment_id es unico, pero sino es null
    public function setPaymentIdAttribute($value)
    {
        if ( empty($value) ) {
            $this->attributes['payment_id'] = NULL;
        } else {
            $this->attributes['payment_id']=$value;
        }
    }


    /**
     * Relaciones
     */
    //una factura la realiza un empleado sino es inscripcion online
    public function user()
    {
        if  (!isNull($this->attributes['user_id'])){ //user_id=>empleado
            return $this->belongsTo('App\User');
        }
    }

    //una factura se le hace a una persona
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }

    //una factura tiene una forma de pago
    public function mpago()
    {
        return $this->belongsTo('App\Mpago');
    }

    //descuento aplicado a una factura
    public function descuento()
    {
        return $this->belongsTo('App\Descuento');
    }
}
