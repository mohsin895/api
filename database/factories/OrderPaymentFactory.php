<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OrderPayment;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(OrderPayment::class, function (Faker $faker) {
    return [
        'transaction_id'=>uniqid(),
        'payment_id'=>uniqid(),
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
