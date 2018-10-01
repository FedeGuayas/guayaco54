<?php

use Illuminate\Database\Seeder;

class DeportesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('deportes')->insert([
            'deporte' => 'AJEDREZ',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'ATLETISMO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'ATLETISMO-TRIATLÓN',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'BADMINTON',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'BALONCESTO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'BEISBOL',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'BMX',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'BOXEO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'CANOTAJE',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'CICLISMO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'CLAVADOS',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'D.A - ATLETISMO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'D.A - NATACIÓN',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'D.A - TAEKWONDO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'D.A - TENIS DE MESA',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'ECUESTRE',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'ESCALADA',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'FUTBOL',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'GIMNASIA ARTÍSTICA',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'GIMNASIA RÍTMICA',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'JUDO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'KARATE',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'LEVANTAMIENTO DE PESAS',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'LEVANTAMIENTO DE POTENCIA',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'LIGA AJEDREZ',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'LIGA PESAS',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'LIGA TAEKWONDO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'LUCHA',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'NATACIÓN',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'PATINAJE ARTÍSTICO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'PATINAJE DE CARRERAS',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'PELOTA NACIONAL',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'REMO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'SOFTBOL',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'TAEKWONDO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'TENIS DE MESA',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'TIRO CON ARCO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'TIRO OLÍMPICO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'TRIATLÓN',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'VOLEIBOL',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'VOLEIBOL PLAYA',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'WUSHU',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'BILLAR',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'HOCKEY',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'KICKBOXING',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'TENIS DE CAMPO',
            'status' => \App\Deporte::ACTIVO,
        ]);
        DB::table('deportes')->insert([
            'deporte' => 'TIRO PRÁCTICO',
            'status' => \App\Deporte::ACTIVO,
        ]);
    }
}
