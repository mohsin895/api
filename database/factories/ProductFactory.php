<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Product::class, function (Faker $faker) {
    
    $categoryInfo=App\Models\Category::with('subInfo','subInfo.subInfo')->inRandomOrder()->where('look_type',3)->first();

    return [
        'category_id'=>(!is_null($categoryInfo->subInfo)) ? (!is_null($categoryInfo->subInfo->subInfo)) ? $categoryInfo->subInfo->subInfo->id:null:null,
        'subcategory_id'=>(!is_null($categoryInfo->subInfo)) ? $categoryInfo->subInfo->id:null,
        'sub_subcategory_id'=>$categoryInfo->id,
        // 'category_id'=>function(){
        //     return App\Models\Category::inRandomOrder()->where('look_type',1)->first()->id;
        // },
        // 'subcategory_id'=>function(){
        //     return App\Models\Category::inRandomOrder()->where('look_type',2)->first()->id;
        // },
        // 'sub_subcategory_id'=>function(){
        //     return App\Models\Category::inRandomOrder()->where('look_type',3)->first()->id;
        // },
        'name'=>$faker->text,
        'added_by'=>1,
        'staff_id'=>null,
        'seller_id'=>function(){
        	return App\Models\Seller::inRandomOrder()->first()->id;
        },
        'shop_id'=>function(){
            return App\Models\Shop::inRandomOrder()->first()->id;
        },
        'brand_id'=>function(){
        	return App\Models\Brand::inRandomOrder()->first()->id;
        },
        'unit_id'=>function(){
        	return App\Models\Unit::inRandomOrder()->first()->id;
        },
        'refundable'=>$faker->randomElement([0,1]),
        'tags'=>null,//$faker->json,
        'product_type'=>$faker->randomElement([1,2]),
        'has_color'=>1,
        'has_size'=>1,
        'thumbnail_img'=>'https://picsum.photos/200/300?random='.$faker->randomNumber(),
        'video_link'=>'https://www.youtube.com/watch?v=NtddLPkd44Y',
        'description'=>$faker->realText,
        'min_qty'=>1,
        'max_qty'=>10,
        'quantity'=>$faker->numberBetween(10,100),
        'warranty_type'=>$faker->randomElement([0,1]),
        'warranty_period'=>$faker->realText(),
        'slug'=>uniqid(),
        'sku'=>uniqid(),
        'has_discount'=>1,
        'discount'=>$faker->numberBetween(5,50),
        'discount_start'=>Carbon::yesterday(),
        'discount_end'=>Carbon::today()->add('10 day'),
        'published'=>1,
        'is_b_to_b'=>$faker->randomElement([0,1]),
        'is_b_to_c'=>$faker->randomElement([0,1]),
        'total_view'=>$faker->numberBetween(10,1000),
        'status'=>1,
        'created_at'=>Carbon::now(),
    ];
});
