{{-- resources/views/invoices/blocks/notes.blade.php --}}
<div style="border-top: 1px solid #eee; padding-top: 16px; font-size: 12px; color: #666;">
    <div style="font-weight: bold; color: {{ $template->primary_color }};
                margin-bottom: 6px;">
        Notes / Terms & Conditions
    </div>
    <div style="line-height: 1.6;">
        {{ $invoice->notes ?? 'Thank you for your business! Payment is due within 30 days.' }}
    </div>
</div>