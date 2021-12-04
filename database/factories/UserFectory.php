<?php
use Faker\Generator as Faker;
use App\Models\Users;

$factory->define(Users::class, function (Faker $faker) {
    static $password;

    return [
        'username'     => $faker->userName,
        'email'    => $faker->unique()->safeEmail,
        'password' => (new \Illuminate\Hashing\BcryptHasher())->make('password'),
        'api_token' => \Illuminate\Support\Str::random(40)
    ];
});
