<?php
use Faker\Generator as Faker;
use App\Models\Todo;


$factory->define(Todo::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomElement(range(1, 10)),
        'title' => 'Task No.' . \Illuminate\Support\Str::random(100),
        'body' => 'Task No.' . \Illuminate\Support\Str::random(100),
        'due_date'    => \Illuminate\Support\Carbon::now()->toDateTimeString(),
        'completed' => random_int(0, 1),
    ];
});
