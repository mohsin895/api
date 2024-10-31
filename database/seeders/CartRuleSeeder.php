<?php

use Illuminate\Database\Seeder;

class CartRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataCount=5;
		
        $this->command->info("Creating {$dataCount} cart rules.");

		$datas = factory(App\Models\CartRule::class, $dataCount)->create();

		$this->command->info("{$dataCount} cart rules created.");
    }
}
