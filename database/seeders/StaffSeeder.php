<?php

use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many staffs do you need ?', 1000);
        
        $dataCount=100;

		$this->command->info("Creating {$dataCount} staffs.");

		$datas = factory(App\Models\Staff::class, $dataCount)->create();

		$this->command->info("{$dataCount} staffs created.");
    }
}
