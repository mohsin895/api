<?php

use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many orders do you need ?', 1000);

        $dataCount=10000;
        
        $this->command->info("Creating {$dataCount} orders.");
   
        $datas = factory(App\Models\Order::class, $dataCount)->create();

        $this->command->info("{$dataCount}  orders created.");
    }
}
