<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Staff;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Staff::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'email'=>$faker->unique()->safeEmail(),
        'phone'=>$faker->unique()->phoneNumber,
        'password'=>bcrypt('123asd'),
        // 'role'=>null,
        'avatar'=>'https://picsum.photos/200/300?random='.$faker->randomNumber(),
        'address'=>$faker->address,
        'status'=>$faker->randomElement([0,1]),
        'created_at'=>Carbon::now(),
    ];
});
