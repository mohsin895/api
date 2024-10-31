<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Seller;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Seller::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'email'=>$faker->unique()->safeEmail(),
        'phone'=>$faker->unique()->phoneNumber,
        'password'=>bcrypt('asd123'),
        'social_id'=>null,
        'dob'=>$faker->date,
        'avatar'=>'https://picsum.photos/200/300?random='.$faker->randomNumber(),
        'is_verify'=>1,//$faker->randomElement([0,1]),
        'status'=>1,//$faker->randomElement([0,1]),
        'created_at'=>Carbon::now(),
    ];
});
