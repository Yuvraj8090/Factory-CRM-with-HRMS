<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\ItemMaster;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuotationSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $items = ItemMaster::all();
        $creators = User::role(['Sales Lead', 'Sales Rep', 'Manager'])->get();
        $statuses = ['Draft', 'Sent', 'Accepted', 'Rejected', 'Expired', 'Sent', 'Accepted', 'Draft'];

        for ($i = 1; $i <= 8; $i++) {
            $selectedItems = $items->shuffle()->take(fake()->numberBetween(2, 3));
            $subtotal = 0;
            $taxAmount = 0;
            $itemPayload = [];

            foreach ($selectedItems as $item) {
                $quantity = fake()->randomFloat(2, 5, 50);
                $lineSubtotal = round($quantity * (float) $item->sale_price, 2);
                $lineTax = round($lineSubtotal * ((float) $item->gst_rate / 100), 2);

                $subtotal += $lineSubtotal;
                $taxAmount += $lineTax;

                $itemPayload[] = [
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $item->sale_price,
                    'tax_rate' => $item->gst_rate,
                    'tax_amount' => $lineTax,
                    'total' => round($lineSubtotal + $lineTax, 2),
                    'description' => $item->item_name . ' supply line item',
                ];
            }

            $discountType = fake()->randomElement([null, 'percentage', 'fixed']);
            $discountValue = match ($discountType) {
                'percentage' => fake()->randomFloat(2, 5, 12),
                'fixed' => fake()->randomFloat(2, 250, 1500),
                default => 0,
            };
            $discountAmount = $discountType === 'percentage'
                ? round($subtotal * ($discountValue / 100), 2)
                : (float) $discountValue;
            $total = round($subtotal - $discountAmount + $taxAmount, 2);

            $quotation = Quotation::create([
                'quotation_number' => 'QT-' . now()->format('Y') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'customer_id' => $customers->random()->id,
                'quotation_date' => fake()->dateTimeBetween('-90 days', '-10 days'),
                'valid_until' => fake()->dateTimeBetween('+7 days', '+45 days'),
                'subtotal' => $subtotal,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'notes' => fake()->paragraph(),
                'terms_conditions' => 'Delivery within 7 working days. Payment terms 30% advance and balance against dispatch.',
                'status' => $statuses[$i - 1],
                'created_by' => $creators->random()->id,
            ]);

            $quotation->items()->createMany($itemPayload);
        }
    }
}
