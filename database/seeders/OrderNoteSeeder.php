<?php

use Illuminate\Database\Seeder;

class OrderNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
       $r = 0 . '-' . 10;
        
        // $dataRange = $this->command->ask('How many note per order do you need ?', $r);
       
       $dataRange="2-5";
       
        $this->command->info("{$dataRange} note creating per order.");
       
        $datas=App\Models\Order::all();
       
        $datas->each(function($data) use ($dataRange){
            factory(App\Models\OrderNote::class, $this->count($dataRange))
                    ->create(['order_id' => $data->id,'noter_id'=>$data->customer_id]);
        });
        $this->command->info("$dataRange} note per order created.");
    }

      // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
}
