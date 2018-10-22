<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    const PAGADA = '1'; //se pago
    const PENDIENTE= 'p'; //para pago online, esta pendiente de pago, despues que se pague pasara a ACTIVA (Pagada)
    const CANCELADA = '0'; //se elimino

    protected $fillable = [
        'numero','fecha_edit','descuento','subtotal','total','user_id','persona_id','nombre','email','direccion','telefono','identificacion','mpago_id','transaction_id','status'
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

    //como transaction_id es unico, pero sino es null
    public function setTransactionIdAttribute($value)
    {
        if ( empty($value) ) {
            $this->attributes['transaction_id'] = NULL;
        } else {
            $this->attributes['transaction_id']=$value;
        }
    }


    /**
     * Relaciones
     */
    //una factura la realiza un empleado sino es inscripcion online
    public function user()
    {
        return $this->belongsTo('App\User');
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

    //un fcatura puede estar en  muchas inscripciones
    public function inscripciones(){
        return $this->hasMany('App\Inscripcion');
    }
}
