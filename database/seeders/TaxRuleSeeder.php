<?php

namespace Database\Seeders;

use App\Models\TaxRule;
use Illuminate\Database\Seeder;

class TaxRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'name'        => 'Exempt (0%)',
                'type'        => 'exempt',
                'cgst_rate'   => 0.00,
                'sgst_rate'   => 0.00,
                'igst_rate'   => 0.00,
                'description' => 'Exempt / Composite contractor — no GST',
                'is_active'   => true,
            ],
            [
                'name'        => 'GST 5%',
                'type'        => 'gst',
                'cgst_rate'   => 2.50,
                'sgst_rate'   => 2.50,
                'igst_rate'   => 5.00,
                'description' => 'CGST 2.5% + SGST 2.5% | IGST 5%',
                'is_active'   => true,
            ],
            [
                'name'        => 'GST 12%',
                'type'        => 'gst',
                'cgst_rate'   => 6.00,
                'sgst_rate'   => 6.00,
                'igst_rate'   => 12.00,
                'description' => 'CGST 6% + SGST 6% | IGST 12% — Works contract / plywood',
                'is_active'   => true,
            ],
            [
                'name'        => 'GST 18%',
                'type'        => 'gst',
                'cgst_rate'   => 9.00,
                'sgst_rate'   => 9.00,
                'igst_rate'   => 18.00,
                'description' => 'CGST 9% + SGST 9% | IGST 18% — Hardware, laminate, HDF',
                'is_active'   => true,
            ],
            [
                'name'        => 'GST 28%',
                'type'        => 'gst',
                'cgst_rate'   => 14.00,
                'sgst_rate'   => 14.00,
                'igst_rate'   => 28.00,
                'description' => 'CGST 14% + SGST 14% | IGST 28%',
                'is_active'   => true,
            ],
        ];

        foreach ($rules as $rule) {
            TaxRule::firstOrCreate(['name' => $rule['name']], $rule);
        }
    }
}
