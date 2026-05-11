<?php

namespace Database\Seeders;

use App\Models\WhatsAppTemplate;
use Illuminate\Database\Seeder;

class WhatsAppTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['template_name' => 'new_lead_intro', 'template_id' => 'WAT-1001', 'category' => 'Marketing', 'variables' => ['name', 'company_name']],
            ['template_name' => 'quotation_followup', 'template_id' => 'WAT-1002', 'category' => 'Sales', 'variables' => ['name', 'quotation_number', 'amount']],
            ['template_name' => 'payment_reminder', 'template_id' => 'WAT-1003', 'category' => 'Finance', 'variables' => ['name', 'invoice_number', 'balance_due']],
            ['template_name' => 'dispatch_update', 'template_id' => 'WAT-1004', 'category' => 'Operations', 'variables' => ['name', 'invoice_number', 'dispatch_date']],
            ['template_name' => 'meeting_confirmation', 'template_id' => 'WAT-1005', 'category' => 'Sales', 'variables' => ['name', 'meeting_date', 'sales_rep']],
            ['template_name' => 'service_checkin', 'template_id' => 'WAT-1006', 'category' => 'Support', 'variables' => ['name', 'reference_number']],
        ];

        foreach ($templates as $template) {
            WhatsAppTemplate::create([
                ...$template,
                'is_active' => true,
            ]);
        }
    }
}
