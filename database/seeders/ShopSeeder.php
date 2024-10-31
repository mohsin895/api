<?php

use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $r = 0 . '-' . 10;
       // $dataRange = $this->command->ask('How many s do you need ?', $r);
        $this->command->info("Shop creating.");
       
        $datas=App\Models\Seller::all();
        $datas->each(function($data) {
            factory(App\Models\Shop::class,1)
                    ->create(['seller_id' => $data->id]);
        });
        $this->command->info('Shop Created');
    }

      // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
}
