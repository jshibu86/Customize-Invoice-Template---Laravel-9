<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvoicetemplatesModel as InvoiceTemplate;

class InvoiceTemplateSeeder extends Seeder
{
    public function run(): void
    {
        InvoiceTemplate::updateOrCreate(
            ['subscriber_id' => 1], // temp subscriber ID = 1
            [
                'name'            => 'Default Template',
                'is_active'       => true,
                'logo_path'       => null,
                'primary_color'   => '#3B82F6',
                'secondary_color' => '#1E40AF',
                'font_family'     => 'Inter',

                'layout' => [
                    'blocks' => [
                        ['id' => 'header',  'position' => 1, 'visible' => true],
                        ['id' => 'bill_to', 'position' => 2, 'visible' => true],
                        ['id' => 'items',   'position' => 3, 'visible' => true],
                        ['id' => 'totals',  'position' => 4, 'visible' => true],
                        ['id' => 'notes',   'position' => 5, 'visible' => true],
                    ]
                ],

                'fields_config' => [
                    'invoice_number' => ['visible' => true,  'order' => 1, 'label' => 'Invoice #'],
                    'invoice_date'   => ['visible' => true,  'order' => 2, 'label' => 'Invoice Date'],
                    'due_date'       => ['visible' => true,  'order' => 3, 'label' => 'Due Date'],
                    'po_number'      => ['visible' => false, 'order' => 4, 'label' => 'PO Number'],
                ],
            ]
        );

        $this->command->info('✅ Temp subscriber template seeded (subscriber_id = 1)');
    }
}