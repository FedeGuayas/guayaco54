<?php

use Illuminate\Database\Seeder;

class EscenariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('escenarios')->insert([
            'escenario'=>'estadio modelo',
            'status'=>\App\Escenario::ACTIVO
        ]);

        DB::table('escenarios')->insert([
            'escenario'=>'piscina olímpica',
            'status'=>\App\Escenario::ACTIVO
        ]);

        DB::table('escenarios')->insert([
            'escenario'=>'miraflores',
            'status'=>\App\Escenario::ACTIVO
        ]);

        DB::table('escenarios')->insert([
            'escenario'=>'4 mosqueteros',
            'status'=>\App\Escenario::ACTIVO
        ]);

        DB::table('escenarios')->insert([
            'escenario'=>'polideportivo huancavilca',
            'status'=>\App\Escenario::ACTIVO
        ]);

        DB::table('escenarios')->insert([
            'escenario'=>'samanes',
            'status'=>\App\Escenario::ACTIVO
        ]);
    }
}
