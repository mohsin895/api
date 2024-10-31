<?php

use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many units do you need ?', 1000);

        $dataCount=10;
        
        $this->command->info("Creating {$dataCount} units.");

        
        $datas = factory(App\Models\Unit::class, $dataCount)->create();

        $this->command->info("{$dataCount} units Created.");
    }
}
