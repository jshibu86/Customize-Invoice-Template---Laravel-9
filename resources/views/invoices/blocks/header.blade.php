{{-- resources/views/invoices/blocks/header.blade.php --}}
<table width="100%" style="margin-bottom: 30px;">
    <tr>
        {{-- Logo Left --}}
        <td width="50%" style="vertical-align: top;">
            @if($template->logo_path)
                <img src="{{ public_path('storage/' . $template->logo_path) }}"
                     alt="Logo"
                     style="max-height: 80px; max-width: 200px;">
            @else
                <div style="font-size: 24px; font-weight: bold; color: {{ $template->primary_color }};">
                    {{ $invoice->company_name ?? 'Your Company' }}
                </div>
            @endif
        </td>

        {{-- Invoice Title Right --}}
        <td width="50%" style="text-align: right; vertical-align: top;">
            <div style="font-size: 28px; font-weight: bold; color: {{ $template->primary_color }};">
                INVOICE
            </div>

            {{-- Dynamic Fields --}}
            @foreach($fields->sortBy('order') as $key => $field)
                @if($field['visible'])
                    <div style="margin-top: 4px; font-size: 13px;">
                        <span style="color: #666;">{{ $field['label'] }}:</span>
                        <strong>
                            @if($key === 'invoice_number') {{ $invoice->invoice_number ?? 'INV-0001' }}
                            @elseif($key === 'invoice_date') {{ $invoice->invoice_date ?? now()->format('d M Y') }}
                            @elseif($key === 'due_date')     {{ $invoice->due_date ?? 'N/A' }}
                            @elseif($key === 'po_number')    {{ $invoice->po_number ?? 'N/A' }}
                            @endif
                        </strong>
                    </div>
                @endif
            @endforeach
        </td>
    </tr>
</table>