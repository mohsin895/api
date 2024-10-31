<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Brand;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(Brand::class, function (Faker $faker) {
    return [
        'name'=>$faker->company.'-'.$faker->numberBetween(1,200),
        'name_bd'=>$faker->company,
        'logo'=>'https://picsum.photos/100/100?random='.$faker->numberBetween(1,200),
        'slug'=>uniqid(),
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
