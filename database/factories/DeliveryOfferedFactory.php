<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\DeliveryOffered;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(DeliveryOffered::class, function (Faker $faker) {
    
    return [
        'thana_id'=>function(){
        	return App\Models\Thana::inRandomOrder()->where('status',1)->first()->id;
        },
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
