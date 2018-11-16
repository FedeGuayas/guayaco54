<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    const APP_ON='1';
    const APP_OFF='0';

    protected $fillable=[
        'status',
        'users_permit'
    ];

    protected $casts = [
      'users_permit'  => 'array',
    ];

}
