<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CatCommission;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(CatCommission::class, function (Faker $faker) {
    return [
        'commission'=>$faker->numberBetween(1,7),
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
