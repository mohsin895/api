<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CustomerAddress;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(CustomerAddress::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'phone'=>$faker->phoneNumber(),
        'address'=>$faker->address,
        'district_id'=>function(){
        	return App\Models\District::inRandomOrder()->first()->id;
        },
        'thana_id'=>function(){
            return App\Models\Thana::inRandomOrder()->first()->id;
        },
        'union_id'=>function(){
            return App\Models\Union::inRandomOrder()->first()->id;
        },
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
