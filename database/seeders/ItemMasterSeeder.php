<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ItemMaster;
use Illuminate\Database\Seeder;

class ItemMasterSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Turmeric Blend', 'category' => 'Spices & Seasoning', 'unit' => 'Kg', 'hsn_code' => '0910', 'gst_rate' => 5],
            ['name' => 'Chilli Seasoning Mix', 'category' => 'Spices & Seasoning', 'unit' => 'Kg', 'hsn_code' => '0910', 'gst_rate' => 5],
            ['name' => 'Refined Palm Oil', 'category' => 'Edible Oils', 'unit' => 'Ltr', 'hsn_code' => '1511', 'gst_rate' => 5],
            ['name' => 'Sunflower Oil', 'category' => 'Edible Oils', 'unit' => 'Ltr', 'hsn_code' => '1512', 'gst_rate' => 5],
            ['name' => 'Printed Snack Pouch', 'category' => 'Flexible Packaging', 'unit' => 'Nos', 'hsn_code' => '3923', 'gst_rate' => 18],
            ['name' => 'Stand-up Zipper Pouch', 'category' => 'Flexible Packaging', 'unit' => 'Nos', 'hsn_code' => '3923', 'gst_rate' => 18],
            ['name' => 'Master Carton Box', 'category' => 'Corrugated Boxes', 'unit' => 'Nos', 'hsn_code' => '4819', 'gst_rate' => 12],
            ['name' => 'Retail Display Box', 'category' => 'Corrugated Boxes', 'unit' => 'Nos', 'hsn_code' => '4819', 'gst_rate' => 12],
            ['name' => 'Masala Chips 100g', 'category' => 'Snack Products', 'unit' => 'Nos', 'hsn_code' => '1905', 'gst_rate' => 12],
            ['name' => 'Salted Namkeen 250g', 'category' => 'Snack Products', 'unit' => 'Nos', 'hsn_code' => '1905', 'gst_rate' => 12],
            ['name' => 'Ready Mix Bites 500g', 'category' => 'Snack Products', 'unit' => 'Nos', 'hsn_code' => '1905', 'gst_rate' => 12],
            ['name' => 'Roasted Snack Combo', 'category' => 'Snack Products', 'unit' => 'Nos', 'hsn_code' => '1905', 'gst_rate' => 12],
        ];

        foreach ($items as $index => $item) {
            $salePrice = fake()->randomFloat(2, 25, 350);
            $purchasePrice = round($salePrice * fake()->randomFloat(2, 0.55, 0.82), 2);

            ItemMaster::create([
                'category_id' => Category::where('name', $item['category'])->value('id'),
                'item_code' => 'ITM-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'item_name' => $item['name'],
                'description' => fake()->sentence(12),
                'unit' => $item['unit'],
                'hsn_code' => $item['hsn_code'],
                'gst_rate' => $item['gst_rate'],
                'opening_stock' => fake()->randomFloat(2, 80, 1500),
                'reorder_level' => fake()->randomFloat(2, 25, 200),
                'sale_price' => $salePrice,
                'purchase_price' => $purchasePrice,
                'is_active' => true,
            ]);
        }
    }
}
