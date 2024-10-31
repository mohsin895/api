<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Slider;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Slider::class, function (Faker $faker) {
   $slug=App\Models\Category::inRandomOrder()->first()->slug;
    return [
        'base_url'=>'https://picsum.photos',
        'slider_url'=>'/900/500?random='.$faker->numberBetween(1,200),
        'target_url'=>'https://localhost:8080/category/'.$slug,
        'url_type'=>1,
        'title'=>$faker->word,
        'description'=>$faker->sentence,
        'status'=>$faker->randomElement([1,2]),
        'created_at'=>Carbon::now(),
    ];
});
