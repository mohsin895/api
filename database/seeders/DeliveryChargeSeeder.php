<?php

use Illuminate\Database\Seeder;

class DeliveryChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Delivery Charge Creating.");
       
        $products=App\Models\Product::all();
        $products->each(function($product){
        	$thanas=App\Models\Thana::all();
            $thanas->each(function($thana) use($product){
            	factory(App\Models\DeliveryCharge::class)
                    ->create(['thana_id' => $thana->id,'product_id'=>$product->id]);
            });
        });
        $this->command->info("Delivery Charge Created.");
    }
}
