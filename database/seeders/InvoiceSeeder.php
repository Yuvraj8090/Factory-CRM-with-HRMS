<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ItemMaster;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $acceptedQuotations = Quotation::with('items')->whereIn('status', ['Accepted', 'Sent'])->get();
        $customers = Customer::all();
        $items = ItemMaster::all();
        $creators = User::role(['Admin', 'Manager', 'Sales Lead'])->get();

        for ($i = 1; $i <= 8; $i++) {
            $quotation = $i <= $acceptedQuotations->count() ? $acceptedQuotations[$i - 1] : null;
            $customerId = $quotation?->customer_id ?? $customers->random()->id;
            $sourceItems = $quotation?->items?->count() ? $quotation->items : $items->shuffle()->take(fake()->numberBetween(2, 3));
            $subtotal = 0;
            $taxAmount = 0;
            $itemPayload = [];

            foreach ($sourceItems as $sourceItem) {
                $item = $sourceItem instanceof ItemMaster ? $sourceItem : $sourceItem->item;
                if (! $item) {
                    continue;
                }

                $quantity = $sourceItem->quantity ?? fake()->randomFloat(2, 3, 30);
                $unitPrice = (float) ($sourceItem->unit_price ?? $item->sale_price);
                $gstRate = (float) ($sourceItem->tax_rate ?? $item->gst_rate);
                $lineSubtotal = round($quantity * $unitPrice, 2);
                $lineTax = round($lineSubtotal * ($gstRate / 100), 2);

                $subtotal += $lineSubtotal;
                $taxAmount += $lineTax;

                $itemPayload[] = [
                    'item_id' => $item->id,
                    'hsn_code' => $item->hsn_code,
                    'quantity' => $quantity,
                    'unit' => $item->unit,
                    'unit_price' => $unitPrice,
                    'gst_rate' => $gstRate,
                    'cgst_rate' => round($gstRate / 2, 2),
                    'sgst_rate' => round($gstRate / 2, 2),
                    'igst_rate' => 0,
                    'tax_amount' => $lineTax,
                    'total' => round($lineSubtotal + $lineTax, 2),
                    'description' => $item->item_name . ' invoice line item',
                ];
            }

            $discountType = $quotation?->discount_type ?? fake()->randomElement([null, 'percentage', 'fixed']);
            $discountValue = (float) ($quotation?->discount_value ?? ($discountType === 'percentage'
                ? fake()->randomFloat(2, 3, 10)
                : ($discountType === 'fixed' ? fake()->randomFloat(2, 200, 900) : 0)));
            $discountAmount = $discountType === 'percentage'
                ? round($subtotal * ($discountValue / 100), 2)
                : $discountValue;
            $total = round($subtotal - $discountAmount + $taxAmount, 2);

            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . now()->format('Y') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'invoice_date' => fake()->dateTimeBetween('-60 days', '-5 days'),
                'customer_id' => $customerId,
                'quotation_id' => $quotation?->id,
                'subtotal' => $subtotal,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'paid_amount' => 0,
                'balance_due' => $total,
                'payment_status' => 'Pending',
                'invoice_status' => fake()->randomElement(['Draft', 'Sent', 'Overdue']),
                'due_date' => fake()->dateTimeBetween('+5 days', '+30 days'),
                'notes' => fake()->paragraph(),
                'terms_conditions' => 'Goods once sold will not be taken back. Interest applies on overdue balances.',
                'created_by' => $creators->random()->id,
            ]);

            $invoice->items()->createMany($itemPayload);
        }
    }
}
