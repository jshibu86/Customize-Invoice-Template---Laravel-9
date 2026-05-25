{{-- resources/views/invoices/blocks/bill_to.blade.php --}}
<table width="100%" style="margin-bottom: 30px;">
    <tr>
        {{-- From --}}
        <td width="50%" style="vertical-align: top;">
            <div style="font-size: 11px; text-transform: uppercase;
                        color: #fff; background: {{ $template->primary_color }};
                        padding: 4px 8px; display: inline-block;
                        margin-bottom: 8px;">
                From
            </div>
            <div style="font-size: 13px; line-height: 1.6;">
                <strong>{{ $invoice->from_name ?? 'Your Company Name' }}</strong><br>
                {{ $invoice->from_address ?? '123 Business Street' }}<br>
                {{ $invoice->from_city ?? 'City, State, ZIP' }}<br>
                {{ $invoice->from_email ?? 'email@company.com' }}
            </div>
        </td>

        {{-- Bill To --}}
        <td width="50%" style="vertical-align: top;">
            <div style="font-size: 11px; text-transform: uppercase;
                        color: #fff; background: {{ $template->primary_color }};
                        padding: 4px 8px; display: inline-block;
                        margin-bottom: 8px;">
                Bill To
            </div>
            <div style="font-size: 13px; line-height: 1.6;">
                <strong>{{ $invoice->client_name ?? 'Client Name' }}</strong><br>
                {{ $invoice->client_address ?? '456 Client Avenue' }}<br>
                {{ $invoice->client_city ?? 'City, State, ZIP' }}<br>
                {{ $invoice->client_email ?? 'client@email.com' }}
            </div>
        </td>
    </tr>
</table>