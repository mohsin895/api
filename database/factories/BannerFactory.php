<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Banner;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(Banner::class, function (Faker $faker) {
    return [
        'base_url'=>'https://picsum.photos',
        'banner_url'=>'/635/270?random='.$faker->numberBetween(1,200),
        'slug'=>uniqid(),
        'title'=>$faker->sentence(),
        'description'=>$faker->realText(),
        'status'=>1,
        'created_at'=>Carbon::now(),
        'category_id'=>function(){
        	return App\Models\Category::inRandomOrder()->first()->id;
        }
    ];
});
