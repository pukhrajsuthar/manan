<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Plywood', 'order' => 1],
            ['name' => 'HDF', 'order' => 2],
            ['name' => 'Cement Sheet', 'order' => 3],
            ['name' => 'Glass', 'order' => 4],
            ['name' => 'Hardware', 'order' => 5],
            ['name' => 'Laminate & Veneer', 'order' => 6],
            ['name' => 'Metal', 'order' => 7],
            ['name' => 'Wood / Timber', 'order' => 8],
            ['name' => 'Labour Charges', 'order' => 9],
        ];

        foreach ($categories as $category) {
            Category::create(array_merge($category, ['is_active' => true]));
        }
    }
}
