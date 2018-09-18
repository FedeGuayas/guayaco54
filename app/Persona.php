<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    const GEN_MASCULINO ='m';
    const GEN_FEMENINO ='f';

    const DISCAPACITADO ='1';
    const NO_DISCAPACITADO ='0';

    const PERFIL_ACTIVO ='1';
    const PERFIL_INACTIVO ='0';

    const PERFIL_PRIVADO ='1'; //no se muestra en la busqueda de los demas usuarios, se desasocia a los usuarios k lo tengan agregado
    const PERFIL_PUBLICO ='0';

    protected $dates = ['fecha_nac'];


    protected $fillable = [
        'nombres','apellidos','num_doc','gen','discapacitado','fecha_nac','email','direccion','telefono','privado','estado'
    ];

    public function getFechaNacAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function getGenAttribute()
    {
        if ($this->attributes['gen']=='m'){
            return  'MASCULINO';
        }elseif ($this->attributes['gen']=='f'){
            return  'FEMENINO';
        }
    }

    public function setGenAttribute($value)
    {
        $gen=strtolower($value);
        if ($gen=='masculino' || $gen=='m' ){
            $this->attributes['gen']=Persona::GEN_MASCULINO;
        }
        if ($gen=='femenino' || $gen=='f'){
            $this->attributes['gen']=Persona::GEN_FEMENINO;
        }
    }

    public function setNombresAttribute($value){
        $this->attributes['nombres']=mb_strtolower($value);
    }

    public function getNombresAttribute(){
        return mb_strtoupper($this->attributes['nombres']);
    }

    public function setApellidosAttribute($value){
        $this->attributes['apellidos']=mb_strtolower($value);
    }

    public function getApellidosAttribute(){
        return mb_strtoupper($this->attributes['apellidos']);
    }

    public function setEmailAttribute($value){
        $this->attributes['email']=strtolower($value);
    }

    public function setDireccionAttribute($value){
        $this->attributes['direccion']=mb_strtolower($value);
    }

    public function getDireccionAttribute(){
        return mb_strtoupper($this->attributes['direccion']);
    }

    public function getFullName()
    {
        return $this->nombres.' '.$this->apellidos;

    }

    //calcular edad
    public function getEdad() {
        $date = explode('-', $this->attributes['fecha_nac']);
        return Carbon::createFromDate($date[0],$date[1],$date[2])->diff(Carbon::now())->format('%y');
        // return Carbon::createFromDate($date[0],$date[1],$date[2])->diff(Carbon::now())->format('%y years %m months %d days');
//        return \Carbon\Carbon::parse($this->fecha_nac)->age;
    }

    /**
     * Relaciones
     */
    //un perfil esta asociado a un usuario
    public function user()
    {
        return $this->hasOne('App\User');
    }

    //un persona puede estar en  muchas inscripciones
    public function inscripciones(){
        return $this->hasMany('App\Inscripcion');
    }


}
