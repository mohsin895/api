<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\DeliveryCharge;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(DeliveryCharge::class, function (Faker $faker) {
    return [
       'charge'=>$faker->numberBetween(40,150),
       'max_quantity'=>$faker->numberBetween(1,5),
       'status'=>1,
       'created_at'=>Carbon::now(),
    ];
});
