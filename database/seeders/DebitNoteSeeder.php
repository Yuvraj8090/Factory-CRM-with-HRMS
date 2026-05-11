<?php

namespace Database\Seeders;

use App\Models\DebitNote;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Seeder;

class DebitNoteSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = Invoice::with('items')->take(5)->get();
        $creators = User::role(['Admin', 'Manager'])->get();

        foreach ($invoices as $index => $invoice) {
            $note = DebitNote::create([
                'debit_note_number' => 'DN-' . now()->format('Y') . '-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'debit_note_date' => fake()->dateTimeBetween('-20 days', 'now'),
                'invoice_id' => $invoice->id,
                'customer_id' => $invoice->customer_id,
                'reason' => fake()->randomElement(['Short supply adjustment', 'Rate revision', 'Damage claim', 'Promotional credit']),
                'subtotal' => 0,
                'tax_amount' => 0,
                'total' => 0,
                'status' => fake()->randomElement(['Open', 'Adjusted', 'Closed']),
                'created_by' => $creators->random()->id,
            ]);

            $selectedItems = $invoice->items->shuffle()->take(min(2, $invoice->items->count()));
            $subtotal = 0;
            $taxAmount = 0;
            $payload = [];

            foreach ($selectedItems as $item) {
                $quantity = min((float) $item->quantity, fake()->randomFloat(2, 1, max(1, (float) $item->quantity)));
                $lineSubtotal = round($quantity * (float) $item->unit_price, 2);
                $lineTax = round($lineSubtotal * ((float) $item->gst_rate / 100), 2);

                $subtotal += $lineSubtotal;
                $taxAmount += $lineTax;

                $payload[] = [
                    'item_id' => $item->item_id,
                    'quantity' => $quantity,
                    'unit_price' => $item->unit_price,
                    'tax_amount' => $lineTax,
                    'total' => round($lineSubtotal + $lineTax, 2),
                    'description' => 'Adjustment against ' . ($item->description ?: 'invoice line item'),
                ];
            }

            $note->items()->createMany($payload);
            $note->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => round($subtotal + $taxAmount, 2),
            ]);
        }
    }
}
