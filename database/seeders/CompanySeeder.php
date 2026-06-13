<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::firstOrCreate(
            ['name' => 'Manan Furniture'],
            [
                'address'             => 'Furniture Market, Ring Road',
                'city'                => 'Ahmedabad',
                'state'               => 'Gujarat',
                'state_code'          => '24',
                'pincode'             => '380005',
                'gstin'               => null,
                'pan'                 => null,
                'phone'               => '9879545044',
                'email'               => 'rameshsuthar@mananfurniture.com',
                'bank_name'           => 'State Bank of India',
                'bank_account_number' => null,
                'bank_ifsc'           => null,
                'bank_branch'         => 'Ahmedabad',
                'invoice_prefix'      => 'MF',
                'invoice_counter'     => 1,
                'financial_year'      => '2024-25',
                'currency'            => 'INR',
                'is_active'           => true,
            ]
        );
    }
}
