<?php

use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many blog do you need ?', 1000);

        $dataCount=100;
        
        $this->command->info("Creating {$dataCount} blogs.");

        
        $datas = factory(App\Models\Blog::class, $dataCount)->create();

        $this->command->info("{$dataCount} blogs Created.");
    }
}
