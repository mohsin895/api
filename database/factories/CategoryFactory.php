<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(Category::class, function (Faker $faker) {
    return [
        'title'=>$faker->word,
        // 'look_type'=>$faker->randomElement([1,2,3]),
        // 'look_type'=>1,
        'meta_key'=>$faker->word,
        'meta_title'=>$faker->word,
        'meta_details'=>$faker->realText(),
        'slug'=>uniqid(),
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
