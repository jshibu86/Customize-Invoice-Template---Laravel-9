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
                        ['id' => 'logo',          'position' => 1, 'visible' => true,
                        'x' => 0,   'y' => 0,   'w' => 200, 'h' => 80],

                        ['id' => 'company_info',  'position' => 2, 'visible' => true,
                        'x' => 0,   'y' => 90,  'w' => 300, 'h' => 100],

                        ['id' => 'invoice_title', 'position' => 3, 'visible' => true,
                        'x' => 310, 'y' => 0,   'w' => 384, 'h' => 100],

                        ['id' => 'from',          'position' => 4, 'visible' => true,
                        'x' => 0,   'y' => 200, 'w' => 330, 'h' => 110],

                        ['id' => 'bill_to',       'position' => 5, 'visible' => true,
                        'x' => 340, 'y' => 200, 'w' => 354, 'h' => 110],

                        ['id' => 'items',         'position' => 6, 'visible' => true,
                        'x' => 0,   'y' => 320, 'w' => 694, 'h' => 160],

                        ['id' => 'totals',        'position' => 7, 'visible' => true,
                        'x' => 440, 'y' => 490, 'w' => 254, 'h' => 100],

                        ['id' => 'notes',         'position' => 8, 'visible' => true,
                        'x' => 0,   'y' => 490, 'w' => 430, 'h' => 80],
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