<?php

use Illuminate\Database\Seeder;

class DeliveryOfferedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Delivery Offered Area Creating.");
       
        $datas=App\Models\DeliveryRule::where('status',1)->where('is_city_wise',1)
        ->get();
        $datas->each(function($data) {
        	factory(App\Models\DeliveryOffered::class)
                    ->create(['delivery_rule_id' => $data->id]);
        });
        $this->command->info("Delivery Offered Area Created.");
    }
}
