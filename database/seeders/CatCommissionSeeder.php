<?php

use Illuminate\Database\Seeder;

class CatCommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Category Wise Commission Setting.");
       
        $datas=App\Models\Category::where('look_type',2)->get();
        $datas->each(function($data) {
            factory(App\Models\CatCommission::class)
                    ->create(['category_id' => $data->id]);
        });
        $this->command->info("Category Wise Commission Setted.");
    }
}
