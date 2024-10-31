<?php

use Illuminate\Database\Seeder;

class NormalCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataRange = (int)$this->command->ask('How many sub category do you need ?', 1000);

        $dataRange='1-5';

        $this->command->info("Creating {$dataRange} sub categories.");
   
        $datas=App\Models\Category::where('look_type',2)->get();
        $datas->each(function($data) use ($dataRange){
            factory(App\Models\Category::class, $this->count($dataRange))
                    ->create(['parent_id' => $data->id,'look_type'=>3]);
        });

        $this->command->info("{$dataRange}  sub categories created.");
    }

      // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
}
