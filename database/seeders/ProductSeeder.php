<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many product do you need ?', 1000);


        $dataCount=500;

        $this->command->info("Creating {$dataCount} product .");
   
        $datas = factory(App\Models\Product::class, $dataCount)->create();

        $this->command->info("{$dataCount} product  created.");
    }
}
