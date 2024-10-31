<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CartRule;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(CartRule::class, function (Faker $faker) {
    return [
        'name'=>$faker->word,
        'details'=>$faker->realText(),
        'isproduct_wise'=>$faker->randomElement([0,1]),
        'isprice_wise'=>$faker->randomElement([0,1]),
        'isproduct_required'=>$faker->randomElement([0,1]),
        'isproduct_discount'=>$faker->randomElement([0,1]),
        'isinvoice_discount'=>$faker->randomElement([0,1]),
        'isfree_product'=>$faker->randomElement([0,1]),
        'rules_for'=>$faker->randomElement([0,1,2]),
        'total_discount'=>$faker->numberBetween(20,200),
        'price_required'=>$faker->numberBetween(500,2000),
        'start_at'=>Carbon::yesterday(),
        'end_at'=>Carbon::today()->add('25 days'),
        'is_restricted'=>$faker->randomElement([0,1]),
        'created_at'=>Carbon::now(),
    ];
});
