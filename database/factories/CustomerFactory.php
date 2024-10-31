<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'phone'=>$faker->unique()->phoneNumber,
        'email'=>$faker->unique()->safeEmail(),
        'avatar'=>'https://picsum.photos/200/300',
        'password'=>bcrypt('123asd'),
        'is_verify'=>1,
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
