<?php

use Illuminate\Database\Seeder;

class StockInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $r = 0 . '-' . 10;
        // $dataRange = $this->command->ask('How many quantity per product do you need ?', $r);
     
        $dataRange="1-3";

        $this->command->info("{$dataRange} quantity creating per product.");
       
        $datas=App\Models\Product::all();
        $datas->each(function($data) use ($dataRange){
            factory(App\Models\StockInfo::class, $this->count($dataRange))
                    ->create(['product_id' => $data->id]);
        });
        $this->command->info("$dataRange} quantity per product created.");
    }

      // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
}
