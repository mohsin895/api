<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OrderStatus;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(OrderStatus::class, function (Faker $faker) {
    return [
        'label_staff'=>$faker->word,
        'label_seller'=>$faker->word,
        'label_customer'=>$faker->word,
        'bg_color'=>$faker->hexColor,
        'font_color'=>$faker->hexColor,
        'is_paid'=>$faker->randomElement([0,1]),
        'is_cancel'=>$faker->randomElement([0,1]),
        'is_delivered'=>$faker->randomElement([0,1]),
        'is_pending'=>$faker->randomElement([0,1]),
        'is_shipping'=>$faker->randomElement([0,1]),
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
