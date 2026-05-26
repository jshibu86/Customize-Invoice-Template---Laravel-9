<div style="font-size:10px; font-weight:700; text-transform:uppercase;
            letter-spacing:1px; color:#fff;
            background:{{ $template->primary_color }};
            padding:3px 10px; display:inline-block;
            border-radius:3px; margin-bottom:8px;">From</div>
<div style="font-size:11px; line-height:1.7; color:#333;">
    <strong>{{ $invoice->from_name ?? 'Acme Corp' }}</strong><br>
    {{ $invoice->from_address ?? '123 Business Street' }}<br>
    {{ $invoice->from_city ?? 'City, State, ZIP' }}<br>
    {{ $invoice->from_email ?? 'email@company.com' }}
</div>