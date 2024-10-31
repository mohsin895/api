<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Order::class, function (Faker $faker) {
   
	$addressInfo=App\Models\CustomerAddress::inRandomOrder()->first();
    return [
        'address_id'=>$addressInfo->id,
        'customer_id'=>$addressInfo->customer_id,
        'thana_id'=>$addressInfo->thana_id,
        'price'=>$faker->numberBetween(100,10000),
        'commission'=>$faker->numberBetween(100,1000),
        'discount'=>0,
        'invoice_discount'=>0,
        'promo_discount'=>0,
        'delivery_charge'=>60,
        'promo_id'=>null,
        'is_bkash_paid'=>0,
        'is_online_paid'=>0,
        'is_cash_on'=>1,
        'is_address_printed'=>0,
        'is_printed'=>0,
        'is_delivered'=>0,
        'is_cancelled'=>0,
        'is_proccessing'=>1,
        'is_packing'=>0,
        'is_shipping'=>0,
        'placed_by'=>1,
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
