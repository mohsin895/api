<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\StockInfo;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(StockInfo::class, function (Faker $faker) {
    return [
        'seller_id'=>function(){
        	return App\Models\Seller::inRandomOrder()->first()->id;
        },
        'size_id'=>function(){
        	return App\Models\Size::inRandomOrder()->first()->id;
        },
        'color_id'=>function(){
        	return App\Models\Color::inRandomOrder()->first()->id;
        },
        'quantity'=>$faker->numberBetween(10,100),
        'purchase_price'=>$faker->numberBetween(100,5000),
        'sell_price'=>$faker->numberBetween(200,6000),
        'whole_sale_price'=>$faker->numberBetween(150,5500),
        'note'=>$faker->realText(),
        'status'=>$faker->randomElement([1,2]),
        'created_at'=>Carbon::now(),
    ];
});
