<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductImage;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(ProductImage::class, function (Faker $faker) {
    return [
        'color_id'=>function(){
        	return App\Models\Color::inRandomOrder()->first()->id;
        },
        'base_url'=>'https://picsum.photos',
        'product_image'=>'/180/180?random='.$faker->numberBetween(1,1500),
        'alt_name'=>$faker->text,
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
