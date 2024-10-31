<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(Blog::class, function (Faker $faker) {
    return [
        'menu_id'=>function(){
        	return App\Models\FooterMenu::inRandomOrder()->first()->id;
        },
        'blog_info'=>$faker->realText(),
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
