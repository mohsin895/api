<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\DeliveryRule;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(DeliveryRule::class, function (Faker $faker) {
    $isPriceWise=$faker->randomElement([0,1]);
    return [
        'name'=>$faker->word,
        'details'=>$faker->realText(),
        'discount_in_per'=>$faker->randomElement([0,1]),
        'discount'=>$faker->numberBetween(5,40),
        'is_price_wise'=>$isPriceWise,
        'price_required'=>$faker->numberBetween(500,2000),
        'is_city_wise'=>(!$isPriceWise) ? 1:0,
        'start_at'=>Carbon::yesterday(),
        'end_at'=>Carbon::today()->add('10 days'),
        'rules_for'=>0,
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
