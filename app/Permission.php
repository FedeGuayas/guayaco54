<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{

    protected $fillable = [
        'name'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }


    /**
     * @return array
     */
    public static function defaultPermissions()
    {
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',

            'view_permissions',
            'add_permissions',
            'edit_permissions',
            'delete_permissions',

            'view_circuitos',
            'add_circuitos',
            'edit_circuitos',
            'delete_circuitos',

            'view_categorias',
            'add_categorias',
            'edit_categorias',
            'delete_categorias',

            'view_tallas',
            'add_tallas',
            'edit_tallas',
            'delete_tallas',

            'view_escenarios',
            'add_escenarios',
            'edit_escenarios',
            'delete_escenarios',

            'view_deportes',
            'add_deportes',
            'edit_deportes',
            'delete_deportes',

            'view_personas',
            'add_personas',
            'edit_personas',
            'delete_personas',

            'view_inscripciones',
            'add_inscripciones',
            'edit_inscripciones',
            'delete_inscripciones',

            'view_comprobantes',
            'add_comprobantes',
            'edit_comprobantes',
            'delete_comprobantes',

            'view_cuadre',
            'add_cuadre',
            'edit_cuadre',
            'delete_cuadre',

            'view_configuracion',
            'add_configuracion',
            'edit_configuracion',
            'delete_configuracion',

            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_log_activities',
            'add_log_activities',
            'edit_log_activities',
            'delete_log_activities',
        ];
    }

}
