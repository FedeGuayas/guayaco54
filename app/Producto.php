<?php
/**
 * Producto defien una carrera por su circuito categoria y precio
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'categoria_id','ejercicio_id','circuito_id','description','price','image'
    ];

    public function getDescriptionAttribute()
    {
        return  strtoupper($this->attributes['description']);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description']=strtolower($value);
    }

    public function setImageAttribute($value)
    {
        $this->attributes['image']=strtolower($value);
    }

    public function getPriceAttribute()
    {
        return  $this->attributes['price']/100;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price']=$value*100;
    }

    /**
     * Relaciones
     */
    //un producto tiene ua categoria, ejercicio,circuito
    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }
    public function circuito(){
        return $this->belongsTo('App\Circuito');
    }
    public function ejercicio(){
        return $this->belongsTo('App\Ejercicio');
    }
    //un producto puede estar muchas inscripciones
    public function inscripcions(){
        return $this->hasMany('App\Incripcion');
    }
}
