<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Shop;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Shop::class, function (Faker $faker) {
    return [
        'shop_name'=>$faker->company,
        'shop_description'=>$faker->sentence,
        'shop_logo'=>'https://picsum.photos/100/100?random='.$faker->randomNumber(),
        'shop_banner'=>'https://picsum.photos/275/105?random='.$faker->randomNumber(),
        'trade_license'=>'https://picsum.photos/275/105?random='.$faker->randomNumber(),
        'trade_license_no'=>$faker->randomNumber(),
        'shop_photo'=>'https://picsum.photos/275/105?random='.$faker->randomNumber(),
        'email'=>$faker->safeEmail(),
        'phone'=>$faker->phoneNumber,
        'facebook'=>null,
        'twitter'=>null,
        'youtube'=>null,
        'instagram'=>null,
        'address'=>$faker->address,
        'rate'=>$faker->numberBetween(100,10000),
        'rated'=>$faker->numberBetween(10,500),
        'vacation'=>1,//$faker->randomElement([1,0]),
        'status'=>1,//$faker->randomElement([1,2]),
        'created_at'=>Carbon::now(),
    ];
});
