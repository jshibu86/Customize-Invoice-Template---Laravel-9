<table width="100%" style="border-collapse:collapse; font-size:11px;">
    <tr>
        <td colspan="2" style="padding:8px 10px; background:{{ $template->primary_color ?? '#2563eb' }}; color:#fff; font-weight:bold;">
            Payment Details
        </td>
    </tr>
    <tr>
        <td style="padding:7px 10px; border:1px solid #e5e7eb; color:#6b7280; width:38%;">Bank</td>
        <td style="padding:7px 10px; border:1px solid #e5e7eb;">{{ $invoice->bank_name ?? 'Business Bank' }}</td>
    </tr>
    <tr>
        <td style="padding:7px 10px; border:1px solid #e5e7eb; color:#6b7280;">Account</td>
        <td style="padding:7px 10px; border:1px solid #e5e7eb;">{{ $invoice->account_number ?? '000123456789' }}</td>
    </tr>
    <tr>
        <td style="padding:7px 10px; border:1px solid #e5e7eb; color:#6b7280;">Routing / IFSC</td>
        <td style="padding:7px 10px; border:1px solid #e5e7eb;">{{ $invoice->routing_code ?? 'BIZB0001234' }}</td>
    </tr>
    <tr>
        <td style="padding:7px 10px; border:1px solid #e5e7eb; color:#6b7280;">Terms</td>
        <td style="padding:7px 10px; border:1px solid #e5e7eb;">{{ $invoice->payment_terms ?? 'Net 30' }}</td>
    </tr>
</table>
