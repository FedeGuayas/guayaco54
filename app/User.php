<?php

namespace App;

use App\Traits\UserResetPassword;
use App\Traits\UserVerified;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use UserVerified; //my user verification trait
    use UserResetPassword;
    use HasRoles; //laravel-permissions

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'persona_id', 'escenario_id', 'password', 'avatar', 'verified', 'verification_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

//    public function setPasswordAttribute($password)
//    {
//        $this->attributes['password'] = bcrypt($password);
//    }

    public function setPasswordAttribute($value)
    {
        if (!empty ($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getEmailAttribute()
    {
        return strtolower($this->attributes['email']);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = mb_strtolower($value);
    }

    public function getLastNameAttribute()
    {
        return mb_strtoupper($this->attributes['last_name']);

    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = mb_strtolower($value);
    }

    public function getFirstNameAttribute()
    {
        return mb_strtoupper($this->attributes['first_name']);

    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;

    }

    //obtener los nombres de los roles del usuario en un arreglo separado por coma
    public function getRoleNames()
    {
        return $this->roles->pluck('name')->implode(', ');
    }

    //generar el token del usuario
    public static function onlyGenerateToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    //como persona_id es unica pero puede ser null
    public function setPersonaIdAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['persona_id'] = NULL;
        } else {
            $this->attributes['persona_id'] = $value;
        }
    }

    /**
     * Relaciones con los modelos
     **/
    //un usuario pertenece a un escenario
    public function escenario()
    {
        return $this->belongsTo('App\Escenario');
    }

    //un usuario tiene un perfil
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }

    //un empleado puede hacer muchas facturas
    public function facturas()
    {
        return $this->hasMany('App\Factura');
    }

    //un usuario puede tener varios  asociados
    public function asociados(){
        return $this->hasMany('App\Asociado');
    }

    //un empleado realiza muchas inscripciones
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    //Obtener los log activities  del usuario logeado.
    public function logs()
    {
        return $this->hasMany('App\LogActivities');
    }

}
