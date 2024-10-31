<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\FooterMenu;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(FooterMenu::class, function (Faker $faker) {
    return [
        'title'=>$faker->word,
        'look_type'=>$faker->randomElement([1,2,3]),
        'parent_id'=>function(){
        	return App\Models\Category::inRandomOrder()->first()->id;
        },
        'meta_key'=>$faker->word,
        'meta_title'=>$faker->word,
        'meta_details'=>$faker->realText(),
        'slug'=>uniqid(),
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
