<?php

use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // $dataCount = (int)$this->command->ask('How many banner do you need ?', 1000);

        $dataCount=100;

        $this->command->info("Creating {$dataCount} banners.");
        
        $datas = factory(App\Models\Banner::class, $dataCount)->create();

        $this->command->info("{$dataCount} banners Created.");
    }

     // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
}
