<?php

use Illuminate\Database\Seeder;

class FooterMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $dataCount = (int)$this->command->ask('How many footer menu do you need ?', 1000);

        $dataCount=100;
        
        $this->command->info("Creating {$dataCount} footer menu.");
   
        $datas = factory(App\Models\FooterMenu::class, $dataCount)->create();

        $this->command->info("{$dataCount}  footer menus created.");
    }
}
