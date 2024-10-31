<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CartProduct;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(CartProduct::class, function (Faker $faker) {
    return [
        'product_id'=>function(){
        	return App\Models\Product::inRandomOrder()->where('published',1)->first()->id;
        },
        'quantity'=>1,
        'discount'=>$faker->numberBetween(10,100),
        'apply_index'=>1,
        'type'=>2, // for discount product
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
