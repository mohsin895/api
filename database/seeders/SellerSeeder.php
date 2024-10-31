<?php

use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many seller do you need ?', 1000);
        $dataCount=100;
        $this->command->info("Creating {$dataCount} seller status.");
   
        $datas = factory(App\Models\Seller::class, $dataCount)->create();

        $this->command->info("{$dataCount} sellers created.");
    }
}
