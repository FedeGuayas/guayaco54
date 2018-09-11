<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogActivities extends Model
{
    protected $fillable = [
       'user_type','user_id','subject','old_values','new_values','url','method','ip_address','user_agent'
    ];

    protected $table='log_activities';

    /**
     * Obtener al usuario a que pertenece el log.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
