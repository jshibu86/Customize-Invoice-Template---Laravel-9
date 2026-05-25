<?php

namespace App\Http\Controllers;

use App\Models\InvoicetemplatesModel as InvoiceTemplate;
use App\Services\InvoicePdfService;
use Illuminate\Http\Request;

class InvoiceTemplateController extends Controller
{
    public function preview(InvoicePdfService $pdfService)
    {
        // Temp fake invoice object for testing (no DB needed yet)
        $invoice = (object) [
            'subscriber_id'  => 1,               // matches our seeded template
            'invoice_number' => 'INV-0001',
            'invoice_date'   => '01 Jan 2025',
            'due_date'       => '31 Jan 2025',
            'po_number'      => 'PO-9999',

            'company_name'   => 'Acme Corp',
            'from_name'      => 'Acme Corp',
            'from_address'   => '123 Business Street',
            'from_city'      => 'New York, NY 10001',
            'from_email'     => 'billing@acme.com',

            'client_name'    => 'John Client',
            'client_address' => '456 Client Avenue',
            'client_city'    => 'Los Angeles, CA 90001',
            'client_email'   => 'john@client.com',

            'items' => [
                ['description' => 'Web Development',  'qty' => 10, 'unit_price' => 100.00, 'amount' => 1000.00],
                ['description' => 'UI/UX Design',     'qty' => 5,  'unit_price' => 80.00,  'amount' => 400.00],
                ['description' => 'Server Setup',     'qty' => 1,  'unit_price' => 150.00, 'amount' => 150.00],
            ],

            'subtotal'        => 1550.00,
            'tax_percent'     => 10,
            'tax_amount'      => 155.00,
            'discount_amount' => 0,
            'grand_total'     => 1705.00,

            'notes' => 'Thank you for your business! Payment is due within 30 days. Bank transfer details will be shared separately.',
        ];

        return $pdfService->generate($invoice);
    }

    /**
     * Show the template builder UI
     */
    public function builder()
    {
        $template = InvoiceTemplate::forSubscriber(1); // temp subscriber_id = 1
        return view('invoices.builder', compact('template'));
    }

    /**
     * Save template settings
     */
    public function save(Request $request)
    {
        $template = InvoiceTemplate::forSubscriber(1);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $template->logo_path = $path;
        }

        $template->primary_color   = $request->primary_color   ?? $template->primary_color;
        $template->secondary_color = $request->secondary_color ?? $template->secondary_color;
        $template->font_family     = $request->font_family     ?? $template->font_family;

        if ($request->layout) {
            $template->layout = json_decode($request->layout, true);
        }

        if ($request->fields_config) {
            $template->fields_config = json_decode($request->fields_config, true);
        }

        $template->save();

        return response()->json(['success' => true, 'message' => 'Template saved!']);
    }
}

