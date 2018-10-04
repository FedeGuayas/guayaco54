<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{

    const RESERVADA = 'r';
    const PAGADA = 'p';

    const INSCRIPCION_ONLINE='1';
    const INSCRIPCION_PRESENCIAL='0';

    const KIT_ENTREGADO = '1';
    const KIT_POR_ENTREGAR =null;

    protected $fillable = [
        'escenario_id', 'producto_id', 'persona_id', 'user_id', 'user_edit', 'deporte_id', 'factura_id', 'fecha', 'num_corredor', 'kit', 'talla_id', 'costo', 'ejercicio_id','inscripcion_type' ,'status'
    ];

    /**
     * Relaciones
     **/
    //una inscripcion la realiza un empleado sino es inscripcion online, sino el user_id=null
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    //en una inscripcion hay un producto
    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    //en una inscripcion hay una persona
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
    //en una inscripcion hay una talla
    public function talla()
    {
        return $this->belongsTo('App\Talla');
    }
    //en una inscripcion hay una factura si no es deportista que tiene costo 0 y no se genera factura
    public function factura()
    {
        return $this->belongsTo('App\Factura');
    }
    //punto de cobro de la inscripcion, online =null
    public function escenario()
    {
        return $this->belongsTo('App\Escenario');
    }

    //
    public function deporte()
    {
        return $this->belongsTo('App\Deporte');
    }

}
