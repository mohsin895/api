<?php

use Illuminate\Database\Seeder;

class CategoryImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $r = 0 . '-' . 10;

        // $dataRange = $this->command->ask('How many category image per category do you need ?', $r);

        $dataRange="1-3";

        $this->command->info("{$dataRange} category image creating per category.");
       
        $datas=App\Models\Category::all();
        $datas->each(function($data) use ($dataRange){
            factory(App\Models\CategoryImage::class, $this->count($dataRange))
                    ->create(['category_id' => $data->id]);
        });
        $this->command->info("$dataRange} category image per category created.");
    }

      // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
}
