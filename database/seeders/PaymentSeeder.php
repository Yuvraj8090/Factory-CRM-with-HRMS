<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = Invoice::orderBy('id')->take(8)->get();
        $creators = User::role(['Admin', 'Manager'])->get();
        $methods = ['Cash', 'Cheque', 'Bank Transfer', 'UPI', 'Credit Card', 'Debit Card', 'Wallet'];
        $paymentIndex = 1;

        foreach ($invoices as $index => $invoice) {
            $iterations = $index < 2 ? 2 : 1;
            $remaining = (float) $invoice->total;

            for ($step = 1; $step <= $iterations; $step++) {
                if ($remaining <= 0) {
                    break;
                }

                $amount = $iterations === 2 && $step === 1
                    ? round($remaining * 0.55, 2)
                    : $remaining;

                Payment::create([
                    'payment_number' => 'PAY-' . now()->format('Y') . '-' . str_pad((string) $paymentIndex, 4, '0', STR_PAD_LEFT),
                    'payment_date' => fake()->dateTimeBetween('-30 days', 'now'),
                    'invoice_id' => $invoice->id,
                    'customer_id' => $invoice->customer_id,
                    'amount' => $amount,
                    'payment_method' => $methods[array_rand($methods)],
                    'reference_number' => 'REF' . str_pad((string) $paymentIndex, 6, '0', STR_PAD_LEFT),
                    'notes' => fake()->sentence(),
                    'created_by' => $creators->random()->id,
                ]);

                $remaining = round($remaining - $amount, 2);
                $paymentIndex++;
            }
        }

        Invoice::with('payments')->get()->each(function (Invoice $invoice): void {
            $paidAmount = round((float) $invoice->payments->sum('amount'), 2);
            $balanceDue = round((float) $invoice->total - $paidAmount, 2);

            $invoice->update([
                'paid_amount' => $paidAmount,
                'balance_due' => max($balanceDue, 0),
                'payment_status' => $paidAmount <= 0 ? 'Pending' : ($balanceDue > 0 ? 'Partial' : 'Paid'),
                'invoice_status' => $balanceDue > 0 ? $invoice->invoice_status : 'Paid',
            ]);
        });
    }
}
