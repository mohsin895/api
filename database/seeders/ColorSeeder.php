<?php

use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// $dataCount = (int)$this->command->ask('How many color do you need ?', 1000);

        $dataCount=60;
		
        $this->command->info("Creating {$dataCount} colors.");

		$datas = factory(App\Models\Color::class, $dataCount)->create();

		$this->command->info("{$dataCount} colors created.");
    }
}
