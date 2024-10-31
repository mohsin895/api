<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OrderNote;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(OrderNote::class, function (Faker $faker) {
    
    return [
        'shop_id'=>null,
        'noter_type'=>1,
        'note'=>$faker->realText(),
        'show_invoice'=>1,
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
