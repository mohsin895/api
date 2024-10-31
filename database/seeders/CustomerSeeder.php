<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many customer do you need ?', 1000);

        $dataCount=300;
		
        $this->command->info("Creating {$dataCount} customers.");

		$datas = factory(App\Models\Customer::class, $dataCount)->create();

		$this->command->info("{$dataCount} customers created.");
    }
}
