<?php

namespace Database\Seeders;

use App\Models\InvoicetemplatesModel as InvoiceTemplate;
use Illuminate\Database\Seeder;

class InvoiceTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = $this->templates();
        $templateNames = array_column($templates, 'name');

        InvoiceTemplate::where('subscriber_id', 1)
            ->where('template_type', 'starter')
            ->whereNotIn('name', $templateNames)
            ->update(['is_active' => false]);

        foreach ($templates as $template) {
            InvoiceTemplate::updateOrCreate(
                [
                    'subscriber_id' => 1,
                    'name' => $template['name'],
                ],
                $template
            );
        }

        $this->command->info('B2B invoice templates seeded for subscriber_id = 1');
    }

    private function templates(): array
    {
        return [
            // ─── Template 1: Classic B2B ─────────────────────────────────────────
            // Logo + company top-left, invoice title top-right, from/bill-to side by
            // side, single items table, payment + totals split below, notes footer.
            [
                'subscriber_id'   => 1,
                'name'            => 'Classic B2B Invoice',
                'template_type'   => 'starter',
                'source_template_id' => null,
                'is_active'       => true,
                'logo_path'       => null,
                'primary_color'   => '#2563EB',
                'secondary_color' => '#1E40AF',
                'font_family'     => 'Arial',
                'layout'          => $this->classicLayout(),
                'fields_config'   => $this->fieldsConfig(false),
                'notes'           => 'Thank you for your business. Payment is due within 30 days.',
            ],

            // ─── Template 2: Procurement Two-Table ───────────────────────────────
            // Full-width header banner, project summary ribbon, bill-to/from swapped
            // (buyer left, seller right), items table, tax summary + totals side by
            // side, notes + approvals block in bottom row.
            [
                'subscriber_id'   => 1,
                'name'            => 'Procurement Two Table',
                'template_type'   => 'starter',
                'source_template_id' => null,
                'is_active'       => true,
                'logo_path'       => null,
                'primary_color'   => '#0F766E',
                'secondary_color' => '#134E4A',
                'font_family'     => 'Helvetica',
                'layout'          => $this->procurementLayout(),
                'fields_config'   => $this->fieldsConfig(true),
                'notes'           => 'Goods and services are billed against the referenced purchase order and contract.',
            ],

            // ─── Template 3: Service Retainer ────────────────────────────────────
            // Logo top-left, invoice title top-right (no company block at top),
            // full-width service period band, bill-to left + company info right,
            // wide items table for hours/rates, payment left + totals right, notes.
            [
                'subscriber_id'   => 1,
                'name'            => 'Service Retainer',
                'template_type'   => 'starter',
                'source_template_id' => null,
                'is_active'       => true,
                'logo_path'       => null,
                'primary_color'   => '#7C3AED',
                'secondary_color' => '#5B21B6',
                'font_family'     => 'DejaVu Sans',
                'layout'          => $this->serviceLayout(),
                'fields_config'   => $this->fieldsConfig(false),
                'notes'           => 'Retainer services are billed monthly. Unused hours do not roll over unless stated in the MSA.',
            ],

            // ─── Template 4: Enterprise Subscription ─────────────────────────────
            // Full-width header (no separate logo block at top), bill-to left +
            // project summary right, items table, totals left + payment right
            // (reversed from Classic), approvals left + notes right, logo sits as a
            // small footer watermark bottom-left.
            [
                'subscriber_id'   => 1,
                'name'            => 'Enterprise Subscription',
                'template_type'   => 'starter',
                'source_template_id' => null,
                'is_active'       => true,
                'logo_path'       => null,
                'primary_color'   => '#111827',
                'secondary_color' => '#475569',
                'font_family'     => 'Arial',
                'layout'          => $this->subscriptionLayout(),
                'fields_config'   => $this->fieldsConfig(true),
                'notes'           => 'Subscription renews according to the master services agreement. Contact finance for billing changes.',
            ],

            // ─── Template 5: Tax Detail Invoice ──────────────────────────────────
            // No logo at top; company info top-left + invoice title top-right,
            // from/bill-to row, items, then a full-width dedicated tax summary band,
            // payment left + totals right, notes footer.
            [
                'subscriber_id'   => 1,
                'name'            => 'Tax Detail Invoice',
                'template_type'   => 'starter',
                'source_template_id' => null,
                'is_active'       => true,
                'logo_path'       => null,
                'primary_color'   => '#B45309',
                'secondary_color' => '#92400E',
                'font_family'     => 'Times New Roman',
                'layout'          => $this->taxDetailLayout(),
                'fields_config'   => $this->fieldsConfig(true),
                'notes'           => 'This invoice includes a detailed tax summary for accounting reconciliation.',
            ],
        ];
    }

    private function fieldsConfig(bool $showPoNumber): array
    {
        return [
            'invoice_number' => ['visible' => true,            'order' => 1, 'label' => 'Invoice #'],
            'invoice_date'   => ['visible' => true,            'order' => 2, 'label' => 'Invoice Date'],
            'due_date'       => ['visible' => true,            'order' => 3, 'label' => 'Due Date'],
            'po_number'      => ['visible' => $showPoNumber,   'order' => 4, 'label' => 'PO Number'],
        ];
    }

    // ─── Layout 1: Classic ───────────────────────────────────────────────────────
    // Logo + company stacked top-left | invoice title top-right
    // from (left) | bill_to (right)
    // items (full width)
    // payment_details (left) | totals (right)
    // notes (full width footer)
    private function classicLayout(): array
    {
        return ['blocks' => [
            $this->block('logo',            1,   0,   0, 200,  80),
            $this->block('company_info',    2,   0,  90, 300, 100),
            $this->block('invoice_title',   3, 310,   0, 384, 100),
            $this->block('from',            4,   0, 200, 330, 110),
            $this->block('bill_to',         5, 340, 200, 354, 110),
            $this->block('items',           6,   0, 330, 694, 160),
            $this->block('payment_details', 7,   0, 520, 350, 145),
            $this->block('totals',          8, 420, 520, 274, 120),
            $this->block('notes',           9,   0, 690, 694,  80),
        ]];
    }

    // ─── Layout 2: Procurement Two-Table ─────────────────────────────────────────
    // header (full width)
    // project_summary (full width ribbon — PO ref, contract no.)
    // bill_to (left buyer) | from (right seller)   ← buyer/seller swapped vs Classic
    // items (full width — two logical tables: ordered vs delivered)
    // tax_summary (left, wider) | totals (right)
    // notes (left) | approvals (right — signature block)
    private function procurementLayout(): array
    {
        return ['blocks' => [
            $this->block('header',          1,   0,   0, 694, 120),
            $this->block('project_summary', 2,   0, 145, 694, 100),
            $this->block('bill_to',         3,   0, 270, 330, 105),
            $this->block('from',            4, 365, 270, 329, 105),
            $this->block('items',           5,   0, 405, 694, 155),
            $this->block('tax_summary',     6,   0, 585, 420, 130),
            $this->block('totals',          7, 445, 585, 249, 130),
            $this->block('notes',           8,   0, 745, 420,  85),
            $this->block('approvals',       9, 445, 745, 249, 110),
        ]];
    }

    // ─── Layout 3: Service Retainer ──────────────────────────────────────────────
    // logo (top-left) | invoice_title (top-right, no company at top)
    // service_period (full-width band — billing period, engagement ref)
    // bill_to (left) | company_info (right)
    // items (full width — line items are hours × rate)
    // payment_details (left) | totals (right)
    // notes (full width footer)
    private function serviceLayout(): array
    {
        return ['blocks' => [
            $this->block('logo',            1,   0,   0, 170,  70),
            $this->block('invoice_title',   2, 390,   0, 304, 100),
            $this->block('service_period',  3,   0, 110, 694, 110),
            $this->block('bill_to',         4,   0, 250, 330, 110),
            $this->block('company_info',    5, 365, 250, 329, 110),
            $this->block('items',           6,   0, 390, 694, 170),
            $this->block('payment_details', 7,   0, 590, 360, 145),
            $this->block('totals',          8, 430, 590, 264, 120),
            $this->block('notes',           9,   0, 765, 694,  90),
        ]];
    }

    // ─── Layout 4: Enterprise Subscription ───────────────────────────────────────
    // header (full width — logo embedded inside header block)
    // bill_to (left) | project_summary (right — subscription plan + cycle dates)
    // items (full width — subscription line items)
    // totals (left) | payment_details (right)   ← reversed order vs Classic
    // approvals (left) | notes (right)
    // logo (bottom-left, small footer watermark, hidden from print if needed)
    private function subscriptionLayout(): array
    {
        return ['blocks' => [
            $this->block('header',          1,   0,   0, 694, 125),
            $this->block('bill_to',         2,   0, 150, 300, 120),
            $this->block('project_summary', 3, 330, 150, 364, 120),
            $this->block('items',           4,   0, 305, 694, 145),
            $this->block('totals',          5,   0, 480, 300, 120),
            $this->block('payment_details', 6, 330, 480, 364, 145),
            $this->block('approvals',       7,   0, 655, 300, 115),
            $this->block('notes',           8, 330, 655, 364, 115),
            $this->block('logo',            9,   0, 790, 160,  60, false),
        ]];
    }

    // ─── Layout 5: Tax Detail ────────────────────────────────────────────────────
    // company_info (top-left) | invoice_title (top-right)  ← no logo row at top
    // from (left) | bill_to (right)
    // items (full width)
    // tax_summary (full width — dedicated HST/GST/PST breakdown band)
    // payment_details (left) | totals (right)
    // notes (full width footer)
    private function taxDetailLayout(): array
    {
        return ['blocks' => [
            $this->block('company_info',    1,   0,   0, 310,  95),
            $this->block('invoice_title',   2, 380,   0, 314, 110),
            $this->block('from',            3,   0, 140, 330, 105),
            $this->block('bill_to',         4, 365, 140, 329, 105),
            $this->block('items',           5,   0, 275, 694, 150),
            $this->block('tax_summary',     6,   0, 455, 694, 130),
            $this->block('totals',          7, 410, 615, 284, 125),
            $this->block('payment_details', 8,   0, 615, 370, 145),
            $this->block('notes',           9,   0, 790, 694,  80),
        ]];
    }

    private function block(string $id, int $position, int $x, int $y, int $w, int $h, bool $visible = true): array
    {
        return compact('id', 'position', 'visible', 'x', 'y', 'w', 'h');
    }
}
