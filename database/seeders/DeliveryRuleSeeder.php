<?php

use Illuminate\Database\Seeder;

class DeliveryRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many delivery rules do you need ?', 1000);

        $dataCount=5;
		
        $this->command->info("Creating {$dataCount} delivery rules.");

		$datas = factory(App\Models\DeliveryRule::class, $dataCount)->create();

		$this->command->info("{$dataCount} delivery rules created.");
    }
}
