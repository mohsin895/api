<?php

use Illuminate\Database\Seeder;

class CartProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Cart Rule Product Setting.");
       
        $datas=App\Models\CartRule::where('isproduct_discount',1)->get();
        $datas->each(function($data){
        	factory(App\Models\CartProduct::class)
                    ->create(['rule_id' => $data->id]);
        });
        $this->command->info("Cart Rule Product Setted.");
    }
}
