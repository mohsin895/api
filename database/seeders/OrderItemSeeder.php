<?php

use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     
        $r = 0 . '-' . 10;
        
        // $dataRange = $this->command->ask('How many product per order do you need ?', $r);
        
        $dataRange="2-5";

        $this->command->info("{$dataRange} products creating per order.");
       
        $datas=App\Models\Order::all();
        $datas->each(function($data) use ($dataRange){
            factory(App\Models\OrderItem::class, $this->count($dataRange))
                    ->create(['order_id' => $data->id]);
        });
        $this->command->info("{$dataRange} order item per order created.");
    }

      // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
}
