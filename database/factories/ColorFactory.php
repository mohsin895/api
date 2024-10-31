<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Color;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(Color::class, function (Faker $faker) {
    return [
        'color'=>$faker->safeColorName,
        'color_code'=>$faker->hexColor,
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
