<?php
namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;
use App\Models\InvoicetemplatesModel as InvoiceTemplate;

class InvoicePdfService
{
    public function generate($invoice): \Illuminate\Http\Response
    {
        $template = InvoiceTemplate::forSubscriber($invoice->subscriber_id);

        // Sort blocks by position, filter hidden
        $blocks = collect($template->layout['blocks'])
            ->where('visible', true)
            ->sortBy('position')
            ->values();

        // Sort fields by order, filter hidden
        $fields = collect($template->fields_config)
            ->where('visible', true)
            ->sortBy('order');

        $pdf = Pdf::loadView('invoices.dynamic', [
            'invoice'  => $invoice,
            'template' => $template,
            'blocks'   => $blocks,
            'fields'   => $fields,
        ]);

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
}