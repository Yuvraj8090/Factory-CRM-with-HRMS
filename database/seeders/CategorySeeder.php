<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $rawCategories = [
            ['name' => 'Raw Materials', 'description' => 'Core food processing inputs and ingredients.', 'parent' => null],
            ['name' => 'Packaging', 'description' => 'Pouches, cartons, labels, and packing support materials.', 'parent' => null],
            ['name' => 'Finished Goods', 'description' => 'Ready-to-sell manufactured SKUs.', 'parent' => null],
            ['name' => 'Spices & Seasoning', 'description' => 'Dry spice blends used in formulations.', 'parent' => 'Raw Materials'],
            ['name' => 'Edible Oils', 'description' => 'Cooking oils and lipid ingredients.', 'parent' => 'Raw Materials'],
            ['name' => 'Flexible Packaging', 'description' => 'Laminates, rolls, and printed pouches.', 'parent' => 'Packaging'],
            ['name' => 'Corrugated Boxes', 'description' => 'Outer cartons and corrugated transport boxes.', 'parent' => 'Packaging'],
            ['name' => 'Snack Products', 'description' => 'Finished snack and ready-to-cook products.', 'parent' => 'Finished Goods'],
        ];

        foreach ($rawCategories as $entry) {
            $parentId = null;

            if ($entry['parent']) {
                $parentId = Category::where('name', $entry['parent'])->value('id');
            }

            Category::create([
                'name' => $entry['name'],
                'description' => $entry['description'],
                'parent_id' => $parentId,
                'is_active' => true,
            ]);
        }
    }
}
