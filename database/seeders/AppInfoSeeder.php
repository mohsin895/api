<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class AppInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        	"app_url"=>"https://loyel.com.bd",
        	"app_logo"=>"http://localhost/loyel-final/loyelApi/storage/app/public/logo/app_logo.png",
        	"phone"=>"01775086266",
        	"email"=>"info@loyel.com.bd",
        	"address"=>"1340 Dhaka, Dhaka Division, Bangladesh",
        	"facebook"=>"https://www.facebook.com/loyel.com.bd/",
        	"twitter"=>"https://twitter.com/?lang=en",
        	"instagram"=>"https://www.instagram.com",
        	"youtube"=>"https://www.youtube.com/watch?v=p0ysH2Glw5w",
        	"status"=>1,
        	"created_at"=>Carbon::now(),
        ];

        \DB::table('app_infos')->insert($data); 
    }
}
