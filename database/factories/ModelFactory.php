<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        //        'persona_id'=>
        //        'escenario_id'=>
        'password' => $password ?: $password = 'secret',
        //        'avatar'=>
        'remember_token' => str_random(10),
        'verified'=>$verificado=$faker->randomElement([\App\User::USUARIO_VERIFICADO,\App\User::USUARIO_NO_VERIFICADO]),
        'verification_token'=> $verificado==\App\User::USUARIO_VERIFICADO ? null : \App\User::onlyGenerateToken()
    ];
});
