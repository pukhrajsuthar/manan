<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'name'                => 'Bhavesh Bhai Patel',
                'phone'               => '9000000001',
                'email'               => null,
                'billing_address'     => 'B-1001, Altera, Near SG Highway',
                'billing_city'        => 'Ahmedabad',
                'billing_state'       => 'Gujarat',
                'billing_state_code'  => '24',
                'billing_pincode'     => '380059',
                'gstin'               => null,
                'pan'                 => null,
                'client_type'         => 'individual',
                'notes'               => 'Site: B-1001, Altera',
                'is_active'           => true,
            ],
            [
                'name'                => 'Shubhash Ji Tibrewal',
                'phone'               => '9000000002',
                'email'               => null,
                'billing_address'     => 'Raskans Golf Link, Bopal',
                'billing_city'        => 'Ahmedabad',
                'billing_state'       => 'Gujarat',
                'billing_state_code'  => '24',
                'billing_pincode'     => '380058',
                'gstin'               => null,
                'pan'                 => null,
                'client_type'         => 'individual',
                'notes'               => 'Site: Raskans Golf Link',
                'is_active'           => true,
            ],
            [
                'name'                => 'Nirvan Mehta',
                'phone'               => '9000000003',
                'email'               => null,
                'billing_address'     => 'Roogta Estella, Shela',
                'billing_city'        => 'Ahmedabad',
                'billing_state'       => 'Gujarat',
                'billing_state_code'  => '24',
                'billing_pincode'     => '382213',
                'gstin'               => null,
                'pan'                 => null,
                'client_type'         => 'individual',
                'notes'               => 'Site: Roogta Estella',
                'is_active'           => true,
            ],
        ];

        foreach ($clients as $client) {
            Client::firstOrCreate(['name' => $client['name']], $client);
        }
    }
}
