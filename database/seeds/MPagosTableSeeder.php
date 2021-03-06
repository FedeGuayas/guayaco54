<?php

use Illuminate\Database\Seeder;

class MPagosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mpagos')->insert([
           'nombre'=>'western',
            'descripcion'=>'pago mediante wester union',
            'status'=>\App\Mpago::ACTIVO
        ]);

        DB::table('mpagos')->insert([
            'nombre'=>'contado',
            'descripcion'=>'efectivo en instalaciones de fedeguayas',
            'status'=>\App\Mpago::ACTIVO
        ]);

        DB::table('mpagos')->insert([
            'nombre'=>'tarjeta',
            'descripcion'=>'pago online mediante tarjeta de crédito / débito',
            'status'=>\App\Mpago::ACTIVO
        ]);
    }
}
