<?php

use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many order status do you need ?', 1000);

        $dataCount=100;
        
        $this->command->info("Creating {$dataCount} order status.");
   
        $datas = factory(App\Models\OrderStatus::class, $dataCount)->create();

        $this->command->info("{$dataCount} order status created.");
    }
}
