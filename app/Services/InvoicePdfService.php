<?php
namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;
use App\Models\InvoicetemplatesModel as InvoiceTemplate;

class InvoicePdfService
{
    public function generate(object $invoice)
    {
        $template = InvoiceTemplate::forSubscriber($invoice->subscriber_id);

        $rows = \App\Services\InvoiceLayoutConverter::toRows(
            $template->layout['blocks'] ?? []
        );

        $fields = collect($template->fields_config)->sortBy('order');

       $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.dynamic', [
            'invoice'  => $invoice,
            'template' => $template,
            'rows'     => $rows,
            'fields'   => $fields,
            'forPdf'   => true,
        ])
       ;

        return $pdf->download('invoice-' . ($invoice->invoice_number ?? 'preview') . '.pdf');
    }
}