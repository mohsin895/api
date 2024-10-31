<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Unit;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Unit::class, function (Faker $faker) {
    return [
        'label'=>$faker->word,
        'status'=>$faker->randomElement([1,2]),
        'created_at'=>Carbon::now(),
    ];
});
