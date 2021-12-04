<?php
use App\Models\Token;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Token::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(range(1, 10)),
        'access_token'  => \Illuminate\Support\Str::random(40),
        'refresh_token'  => \Illuminate\Support\Str::random(40),
        'expires_in'    => Carbon::now()->addDays(5)->toDateTimeString(),
    ];
});
