<?php

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many brand do you need ?', 1000);

       $dataCount=40; 

        $this->command->info("Creating {$dataCount} brands.");

        
        $datas = factory(App\Models\Brand::class, $dataCount)->create();

        $this->command->info("{$dataCount} brands Created.");
    }
}
