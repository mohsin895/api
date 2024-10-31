<?php

use Illuminate\Database\Seeder;

class OrderPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $r = 0 . '-' . 10;
       // $dataRange = $this->command->ask('How many category image per category do you need ?', $r);
        $this->command->info("Order payment history creating.");
       
        $datas=App\Models\Order::all();
        $datas->each(function($data) {
            factory(App\Models\OrderPayment::class)
                    ->create(['order_id' => $data->id,'amount'=>$data->price+$data->delivery_charge]);
        });
        $this->command->info("Order payment history created.");
    }

      // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
}
