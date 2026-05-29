<?php

namespace App\Http\Controllers;

use App\Models\InvoicetemplatesModel as InvoiceTemplate;
use App\Services\InvoicePdfService;
use Illuminate\Http\Request;

class InvoiceTemplateController extends Controller
{
    public function previewHtml()
    {
        $template = InvoiceTemplate::forSubscriber(1);

        $rows = \App\Services\InvoiceLayoutConverter::toRows(
            $template->layout['blocks'] ?? []
        );

        $fields = collect($template->fields_config)->sortBy('order');

        $invoice = $this->fakeInvoice($template);

        return view('invoices.dynamic', compact('invoice', 'template', 'rows', 'fields'))
            ->with('forPdf', false);
    }

    public function preview()
    {
        $template = InvoiceTemplate::forSubscriber(1);

        $rows = \App\Services\InvoiceLayoutConverter::toRows(
            $template->layout['blocks'] ?? []
        );

        $fields = collect($template->fields_config)->sortBy('order');

        $invoice = $this->fakeInvoice($template);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.dynamic', [
            'invoice'  => $invoice,
            'template' => $template,
            'rows'     => $rows,
            'fields'   => $fields,
            'forPdf'   => true,
        ])
       ;

        return $pdf->download('invoice-INV-0001.pdf');
    }

    // ── Extract fake invoice to avoid repetition ──
    private function fakeInvoice($template): object
    {
        return (object) [
            'subscriber_id'  => 1,
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
                ['description' => 'Web Development', 'qty' => 10, 'unit_price' => 100.00, 'amount' => 1000.00],
                ['description' => 'UI/UX Design',    'qty' => 5,  'unit_price' => 80.00,  'amount' => 400.00],
                ['description' => 'Server Setup',    'qty' => 1,  'unit_price' => 150.00, 'amount' => 150.00],
            ],
            'subtotal'        => 1550.00,
            'tax_percent'     => 10,
            'tax_amount'      => 155.00,
            'discount_amount' => 0,
            'grand_total'     => 1705.00,
            'notes'           => $template->notes ?? 'Thank you for your business!',
        ];
    }

    // public function previewHtml()
    // {
    //     $template = InvoiceTemplate::forSubscriber(1);

    //     $blocks = collect($template->layout['blocks'])
    //         ->where('visible', true)
    //         ->sortBy('position')
    //         ->values();

    //     $fields = collect($template->fields_config)
    //         ->where('visible', true)
    //         ->sortBy('order');

    //     $invoice = (object) [
    //         'subscriber_id'  => 1,
    //         'invoice_number' => 'INV-0001',
    //         'invoice_date'   => '01 Jan 2025',
    //         'due_date'       => '31 Jan 2025',
    //         'po_number'      => 'PO-9999',
    //         'company_name'   => 'Acme Corp',
    //         'from_name'      => 'Acme Corp',
    //         'from_address'   => '123 Business Street',
    //         'from_city'      => 'New York, NY 10001',
    //         'from_email'     => 'billing@acme.com',
    //         'client_name'    => 'John Client',
    //         'client_address' => '456 Client Avenue',
    //         'client_city'    => 'Los Angeles, CA 90001',
    //         'client_email'   => 'john@client.com',
    //         'items' => [
    //             ['description' => 'Web Development', 'qty' => 10, 'unit_price' => 100.00, 'amount' => 1000.00],
    //             ['description' => 'UI/UX Design',    'qty' => 5,  'unit_price' => 80.00,  'amount' => 400.00],
    //             ['description' => 'Server Setup',    'qty' => 1,  'unit_price' => 150.00, 'amount' => 150.00],
    //         ],
    //         'subtotal'        => 1550.00,
    //         'tax_percent'     => 10,
    //         'tax_amount'      => 155.00,
    //         'discount_amount' => 0,
    //         'grand_total'     => 1705.00,
    //         'notes'           => $template->notes ?? 'Thank you for your business!',
    //     ];

    //     // Return as plain HTML (not PDF)
    //     return view('invoices.dynamic', compact('invoice', 'template', 'blocks', 'fields'));
    // }

    /**
     * Show the template builder UI
     */
    public function builder()
    {
        $template = InvoiceTemplate::forSubscriber(1);
        $invoice  = $this->fakeInvoice($template);

        $blockDefs = collect($template->layout['blocks'])->sortBy('position');
        $fields    = collect($template->fields_config)->sortBy('order');

        return view('invoices.builder', compact(
            'template', 'invoice', 'blockDefs', 'fields'
        ));
    }
    /**
     * Save template settings
     */
    public function save(Request $request)
    {
        try{
        $template = InvoiceTemplate::forSubscriber(1);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $template->logo_path = $path;
        }

        $template->primary_color   = $request->primary_color   ?? $template->primary_color;
        $template->secondary_color = $request->secondary_color ?? $template->secondary_color;
        $template->font_family     = $request->font_family     ?? $template->font_family;
        $template->notes = $request->notes ?? $template->notes;

        if ($request->layout) {
            $template->layout = json_decode($request->layout, true);
        }

        if ($request->fields_config) {
            $template->fields_config = json_decode($request->fields_config, true);
        }

        $template->save();
        }
        catch(\Exception $e){
            dd($e);
        }
        return response()->json(['success' => true, 'message' => 'Template saved!']);
    }
}

