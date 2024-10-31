<?php

use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $r = 0 . '-' . 10;
        // $dataRange = $this->command->ask('How many product image per product do you need ?', $r);
       
       $dataRange="1-3";
       
        $this->command->info("{$dataRange} product image creating per product.");
       
        $datas=App\Models\Product::all();
        $datas->each(function($data) use ($dataRange){
            factory(App\Models\ProductImage::class, $this->count($dataRange))
                    ->create(['product_id' => $data->id]);
        });
        $this->command->info("$dataRange} product image per product created.");
    }

      // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
}
