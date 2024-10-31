<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many category do you need ?', 1000);

        $dataCount=10;

        $this->command->info("Creating {$dataCount} categories.");
   
        $datas = factory(App\Models\Category::class, $dataCount)->create(['look_type'=>1]);

        $this->command->info("{$dataCount}  categories created.");
    }

      // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
}
