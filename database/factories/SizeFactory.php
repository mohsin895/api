<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Size;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Size::class, function (Faker $faker) {
    return [
        'label'=>$faker->word,
        'size'=>$faker->word,
        'status'=>$faker->randomElement([1,2]),
        'created_at'=>Carbon::now(),
    ];
});
