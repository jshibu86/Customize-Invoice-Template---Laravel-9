<table width="100%">
    <tr>
        <td width="55%" style="vertical-align:top; padding-right:20px;">
            <div style="font-size:10px; text-transform:uppercase; letter-spacing:1px;
                        color:#888; margin-bottom:4px;">Company</div>
            <div style="font-size:15px; font-weight:bold; color:{{ $template->primary_color }};">
                {{ $invoice->from_name ?? 'Your Company Name' }}
            </div>
            <div style="font-size:12px; color:#555; margin-top:4px; line-height:1.7;">
                {{ $invoice->from_address ?? '123 Business Street' }}<br>
                {{ $invoice->from_city ?? 'City, State, ZIP' }}<br>
                {{ $invoice->from_email ?? 'email@company.com' }}
            </div>
        </td>
        <td width="45%" style="text-align:right; vertical-align:top;">
            <div style="font-size:32px; font-weight:900;
                        color:{{ $template->primary_color }}; letter-spacing:-1px;">
                INVOICE
            </div>
            @foreach($fields->sortBy('order') as $key => $field)
                @if($field['visible'])
                <div style="font-size:12px; margin-top:3px; color:#444;">
                    {{ $field['label'] }}:
                    <strong>
                        @if($key === 'invoice_number')   {{ $invoice->invoice_number ?? 'INV-0001' }}
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