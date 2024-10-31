<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CategoryImage;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(CategoryImage::class, function (Faker $faker) {
    return [
        'base_url'=>'https://picsum.photos',
        'category_image'=>'/200/200?random='.$faker->numberBetween(1,1000),
        'alt_name'=>$faker->word,
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
