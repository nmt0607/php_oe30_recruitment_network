<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'id' => 2,
        'name' => $faker->name,
        'type' => $faker->numberBetween(1, 3),

    ];
});
