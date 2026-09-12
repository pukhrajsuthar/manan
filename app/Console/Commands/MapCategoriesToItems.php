<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Console\Command;

class MapCategoriesToItems extends Command
{
    protected $signature = 'app:map-categories';
    protected $description = 'Map categories to items based on item names';

    public function handle()
    {
        $mapping = [
            'Plywood' => 'Plywood',
            'HDF' => 'HDF',
            'Cement Sheet' => 'Cement Sheet',
            'Glass' => 'Glass',
            'Hardware' => 'Hardware',
            'Laminate' => 'Laminate & Veneer',
            'Veneer' => 'Laminate & Veneer',
            'Wood' => 'Wood / Timber',
            'Timber' => 'Wood / Timber',
            'Aluminium' => 'Metal',
            'Metal' => 'Metal',
            'Mix' => 'Metal',
            'Labour' => 'Labour Charges',
        ];

        $updated = 0;

        Item::all()->each(function ($item) use ($mapping, &$updated) {
            foreach ($mapping as $keyword => $categoryName) {
                if (stripos($item->name, $keyword) !== false) {
                    $category = Category::where('name', $categoryName)->first();
                    if ($category && $item->category_id !== $category->id) {
                        $item->update(['category_id' => $category->id]);
                        $this->info("✓ {$item->name} → {$categoryName}");
                        $updated++;
                    }
                    break;
                }
            }
        });

        $total = Item::count();
        $withCategory = Item::whereNotNull('category_id')->count();

        $this->info("\n✓ Mapping complete!");
        $this->info("Total items: {$total}");
        $this->info("Items with categories: {$withCategory}");
        $this->info("Updated: {$updated}");
    }
}
