<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         Eloquent::unguard();

        // Ask for db migration refresh, default is no
        if ($this->command->confirm('Do you wish to refresh migration before seeding, it will clear all old data ?')) {

            // Call the php artisan migrate:fresh using Artisan
            $this->command->call('migrate:fresh');

            $this->command->line("Database cleared.");
        }

            $this->call(AppInfoSeeder::class);
            $this->call(DivisionSeeder::class);
            $this->call(DistrictSeeder::class);
            $this->call(ThanaSeeder::class);
            $this->call(UnionSeeder::class);
            $this->call(StaffSeeder::class);
            $this->call(CustomerSeeder::class);
            $this->call(CustomerAddressSeeder::class);
            $this->call(SellerSeeder::class);
            $this->call(ShopSeeder::class);
            $this->call(ColorSeeder::class);
            $this->call(SizeSeeder::class);
            $this->call(BrandSeeder::class);
            $this->call(UnitSeeder::class);
            $this->call(CategorySeeder::class);
            $this->call(SubCategorySeeder::class);
            $this->call(NormalCategorySeeder::class);
            $this->call(CategoryImageSeeder::class);
            $this->call(CatCommissionSeeder::class);
            $this->call(BannerSeeder::class);
            $this->call(SliderSeeder::class);
            $this->call(ProductSeeder::class);
            $this->call(ProductImageSeeder::class);
            $this->call(StockInfoSeeder::class);
            $this->call(FooterMenuSeeder::class);
            $this->call(BlogSeeder::class);
            $this->call(DeliveryChargeSeeder::class);
            $this->call(DeliveryRuleSeeder::class);
            $this->call(CartRuleSeeder::class);
            $this->call(CartProductSeeder::class);
            $this->call(OrderStatusSeeder::class);
            $this->call(OrderSeeder::class);
            $this->call(OrderItemSeeder::class);
            $this->call(OrderNoteSeeder::class);
            $this->call(OrderPaymentSeeder::class);
        

        $this->command->info("Database seeded.");

        // Re Guard model
        Eloquent::reguard();
    }
}
