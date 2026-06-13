<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\TaxRule;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $exempt = TaxRule::where('name', 'Exempt (0%)')->first();
        $gst18  = TaxRule::where('name', 'GST 18%')->first();

        $items = [
            // Plywood
            ['name' => 'Plywood 19mm',   'unit' => 'Sqft', 'hsn_code' => '4412', 'selling_price' => 200.00,  'tax_rule_id' => $exempt->id, 'category' => 'Plywood'],
            ['name' => 'Plywood 12mm',   'unit' => 'Sqft', 'hsn_code' => '4412', 'selling_price' => 150.00,  'tax_rule_id' => $exempt->id, 'category' => 'Plywood'],
            ['name' => 'Plywood 8mm',    'unit' => 'Sqft', 'hsn_code' => '4412', 'selling_price' => 100.00,  'tax_rule_id' => $exempt->id, 'category' => 'Plywood'],
            ['name' => 'Plywood 6mm',    'unit' => 'Sqft', 'hsn_code' => '4412', 'selling_price' => 80.00,   'tax_rule_id' => $exempt->id, 'category' => 'Plywood'],
            // HDF / MDF
            ['name' => 'HDF 4mm',        'unit' => 'Sqft', 'hsn_code' => '4411', 'selling_price' => 50.00,   'tax_rule_id' => $exempt->id, 'category' => 'HDF'],
            ['name' => 'HDF 6mm',        'unit' => 'Sqft', 'hsn_code' => '4411', 'selling_price' => 70.00,   'tax_rule_id' => $exempt->id, 'category' => 'HDF'],
            ['name' => 'HDF 12mm',       'unit' => 'Sqft', 'hsn_code' => '4411', 'selling_price' => 120.00,  'tax_rule_id' => $exempt->id, 'category' => 'HDF'],
            // Laminate & Veneer
            ['name' => 'Inside Laminate','unit' => 'Nos',  'hsn_code' => '3921', 'selling_price' => 1500.00, 'tax_rule_id' => $gst18->id,  'category' => 'Laminate'],
            ['name' => 'Veneer 4mm',     'unit' => 'Sqft', 'hsn_code' => '4408', 'selling_price' => 250.00,  'tax_rule_id' => $exempt->id, 'category' => 'Laminate'],
            // Sheet & Board
            ['name' => 'Cement Sheet',   'unit' => 'Sqft', 'hsn_code' => '6811', 'selling_price' => 125.00,  'tax_rule_id' => $exempt->id, 'category' => 'Sheet'],
            // Wood / Timber
            ['name' => 'Wood / Timber',  'unit' => 'CFT',  'hsn_code' => '4407', 'selling_price' => 6000.00, 'tax_rule_id' => $exempt->id, 'category' => 'Wood'],
            // Metal / Hardware
            ['name' => 'Aluminium Pipe', 'unit' => 'Nos',  'hsn_code' => '7610', 'selling_price' => 800.00,  'tax_rule_id' => $exempt->id, 'category' => 'Metal'],
            ['name' => 'Hardware',       'unit' => 'Lot',  'hsn_code' => '8302', 'selling_price' => 1.00,    'tax_rule_id' => $gst18->id,  'category' => 'Hardware'],
            ['name' => 'Mix Metal',      'unit' => 'Lot',  'hsn_code' => '7326', 'selling_price' => 1.00,    'tax_rule_id' => $exempt->id, 'category' => 'Metal'],
            // Glass
            ['name' => 'Glass',          'unit' => 'Sqft', 'hsn_code' => '7007', 'selling_price' => 600.00,  'tax_rule_id' => $exempt->id, 'category' => 'Glass'],
            // Labour (percentage-based; rate set per invoice)
            ['name' => 'Labour Charges', 'unit' => 'Lot',  'hsn_code' => null,   'selling_price' => 1.00,    'tax_rule_id' => $exempt->id, 'category' => 'Labour'],
        ];

        foreach ($items as $item) {
            Item::firstOrCreate(['name' => $item['name']], array_merge($item, ['is_active' => true]));
        }
    }
}
