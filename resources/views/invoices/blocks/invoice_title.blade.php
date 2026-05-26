<div style="font-size:28px; font-weight:900; color:{{ $template->primary_color }};
            text-align:right; letter-spacing:-1px;">INVOICE</div>
@foreach($fields->sortBy('order') as $key => $field)
    @if($field['visible'])
    <div style="font-size:11px; margin-top:3px; color:#444; text-align:right;">
        {{ $field['label'] }}:
        <strong>
            @if($key==='invoice_number')   {{ $invoice->invoice_number ?? 'INV-0001' }}
            @elseif($key==='invoice_date') {{ $invoice->invoice_date ?? now()->format('d M Y') }}
            @elseif($key==='due_date')     {{ $invoice->due_date ?? 'N/A' }}
            @elseif($key==='po_number')    {{ $invoice->po_number ?? 'N/A' }}
            @endif
        </strong>
    </div>
    @endif
@endforeach