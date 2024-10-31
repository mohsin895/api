<?php

use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many slider do you need ?', 1000);

        $dataCount=100;

        $this->command->info("Creating {$dataCount} slider creating.");
   
        $datas = factory(App\Models\Slider::class, $dataCount)->create();

        $this->command->info("{$dataCount} slider created.");
    }
}
