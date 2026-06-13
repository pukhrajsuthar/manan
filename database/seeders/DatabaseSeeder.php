<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TaxRuleSeeder::class,
            CompanySeeder::class,
            ItemSeeder::class,
            ClientSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
