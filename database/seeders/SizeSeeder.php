<?php

use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many  size do you need ?', 1000);

        $dataCount=20;

        $this->command->info("Creating {$dataCount}  size creating.");
   
        $datas = factory(App\Models\Size::class, $dataCount)->create();

        $this->command->info("{$dataCount} product status created.");
    }
}
