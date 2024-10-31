<?php

use Illuminate\Database\Seeder;

class CustomerAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $r = 0 . '-' . 10;

        // $dataRange = $this->command->ask('How many address  per customer do you need ?', $r);

        $dataRange="3-5"; 
        
        $this->command->info("{$dataRange} address creating per customer.");
       
        $datas=App\Models\Customer::all();
        $datas->each(function($data) use ($dataRange){
            factory(App\Models\CustomerAddress::class, $this->count($dataRange))
                    ->create(['customer_id' => $data->id]);
        });
        $this->command->info("$dataRange} address per customer created.");
    }

      // Return random value in given range
    function count($range)
    {
        return rand(...explode('-', $range));
    }
    
}
