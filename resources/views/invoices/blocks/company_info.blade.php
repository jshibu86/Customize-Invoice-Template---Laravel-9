<div style="font-size:10px; text-transform:uppercase; letter-spacing:1px;
            color:#888; margin-bottom:4px;">Company</div>
<div style="font-size:14px; font-weight:bold; color:{{ $template->primary_color }};">
    {{ $invoice->from_name ?? 'Your Company' }}
</div>
<div style="font-size:11px; color:#555; margin-top:4px; line-height:1.7;">
    {{ $invoice->from_address ?? '123 Business Street' }}<br>
    {{ $invoice->from_city ?? 'City, State, ZIP' }}<br>
    {{ $invoice->from_email ?? 'email@company.com' }}
</div>