<?php

namespace App\Http\Controllers;

use App\Models\InvoicetemplatesModel as InvoiceTemplate;
use App\Services\InvoicePdfService;
use Illuminate\Http\Request;
/**
 * @OA\Info(
 *     title="My API",
 *     version="1.0.0"
 * )
 */
class InvoiceTemplateController extends Controller
{
    private int $subscriberId = 1;

    /**
     * @OA\Get(
     *     path="/api/invoice-templates",
     *     summary="Get all invoice templates",
     *     tags={"Invoice Templates"},
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function templates()
    {
        $starterTemplates = InvoiceTemplate::where('subscriber_id', $this->subscriberId)
            ->where('is_active', true)
            ->where('template_type', 'starter')
            ->orderBy('id')
            ->get();

        $savedTemplates = InvoiceTemplate::where('subscriber_id', $this->subscriberId)
            ->where('is_active', true)
            ->where('template_type', 'saved')
            ->latest()
            ->get();

        $activeTab = request('tab') === 'saved' ? 'saved' : 'starter';

        return view('invoices.templates', compact('starterTemplates', 'savedTemplates', 'activeTab'));
    }

    public function previewHtml(?int $template = null)
    {
        $template = $this->findTemplate($template);
        [$rows, $fields] = $this->renderConfig($template);

        $invoice = $this->fakeInvoice($template);

        return view('invoices.dynamic', compact('invoice', 'template', 'rows', 'fields'))
            ->with('forPdf', false);
    }

    public function preview(?int $template = null)
    {
        $template = $this->findTemplate($template);
        [$rows, $fields] = $this->renderConfig($template);

        $invoice = $this->fakeInvoice($template);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.dynamic', [
            'invoice'  => $invoice,
            'template' => $template,
            'rows'     => $rows,
            'fields'   => $fields,
            'forPdf'   => true,
        ])
       ;

        return $pdf->download('invoice-' . $template->id . '-INV-0001.pdf');
    }

    public function exportHtml(int $template)
    {
        $template = $this->findTemplate($template);
        [$rows, $fields] = $this->renderConfig($template);

        $invoice = $this->fakeInvoice($template);

        $html = view('invoices.dynamic', [
            'invoice' => $invoice,
            'template' => $template,
            'rows' => $rows,
            'fields' => $fields,
            'forPdf' => false,
            'forHtmlExport' => true,
        ])->render();

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="invoice-template-' . $template->id . '.html"',
        ]);
    }

    public function logo(int $template)
    {
        $template = $this->findTemplate($template);

        abort_if(!$template->logo_path, 404);

        $path = storage_path('app/public/' . $template->logo_path);

        abort_unless(is_file($path), 404);

        return response()->file($path);
    }

    private function findTemplate(?int $templateId = null): InvoiceTemplate
    {
        return InvoiceTemplate::forSubscriberTemplate($this->subscriberId, $templateId);
    }

    private function renderConfig(InvoiceTemplate $template): array
    {
        $rows = \App\Services\InvoiceLayoutConverter::toRows(
            $template->layout['blocks'] ?? []
        );

        $fields = collect($template->fields_config)->sortBy('order');

        return [$rows, $fields];
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
            'tax_lines' => [
                ['name' => 'CGST', 'rate' => 5, 'taxable' => 1550.00, 'amount' => 77.50],
                ['name' => 'SGST', 'rate' => 5, 'taxable' => 1550.00, 'amount' => 77.50],
            ],
            'subtotal'        => 1550.00,
            'tax_percent'     => 10,
            'tax_amount'      => 155.00,
            'discount_amount' => 0,
            'grand_total'     => 1705.00,
            'bank_name'        => 'Business First Bank',
            'account_number'   => '9876543210',
            'routing_code'     => 'BFBK0009876',
            'payment_terms'    => 'Net 30',
            'project_name'     => 'Enterprise Portal Rollout',
            'contract_number'  => 'MSA-2026-042',
            'cost_center'      => 'OPS-2048',
            'service_period'   => '01 Jan 2026 - 31 Jan 2026',
            'service_description' => 'Monthly implementation support, SLA monitoring, and platform maintenance.',
            'prepared_by'      => 'Finance Team',
            'approved_by'      => 'Operations Director',
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
    public function builder(int $template)
    {
        $template = $this->findTemplate($template);
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
    public function save(Request $request, int $template)
    {
        try{
        $sourceTemplate = $this->findTemplate($template);
        $template = $sourceTemplate->template_type === 'saved'
            ? $sourceTemplate
            : $sourceTemplate->replicate(['created_at', 'updated_at']);

        if ($sourceTemplate->template_type !== 'saved') {
            $template->name = 'Saved - ' . ($sourceTemplate->name ?? 'Invoice Template') . ' - ' . now()->format('d M H:i');
            $template->template_type = 'saved';
            $template->source_template_id = $sourceTemplate->id;
            $template->is_active = true;
        }

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
        return response()->json([
            'success' => true,
            'message' => 'Template saved!',
            'template_id' => $template->id,
            'redirect_url' => route('invoice.builder', $template->id),
            'save_url' => route('invoice.save', $template->id),
            'preview_url' => route('invoice.preview', $template->id),
            'saved_templates_url' => route('invoice.templates', ['tab' => 'saved']),
        ]);
    }
}
