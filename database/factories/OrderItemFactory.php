<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OrderItem;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(OrderItem::class, function (Faker $faker) {
    $stockInfo=App\Models\StockInfo::inRandomOrder()->first();
    return [
        'seller_id'=>$stockInfo->seller_id,
        'stock_id'=>$stockInfo->id,
        'product_id'=>$stockInfo->product_id,
        'quantity'=>1,
        'buy_rate'=>$stockInfo->purchase_price,
        'sell_rate'=>$stockInfo->sell_price,
        'sell_price'=>$stockInfo->sell_price,
        'discount'=>0,
        'commission'=>$faker->numberBetween(100,1000),
        'is_free'=>0,
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
