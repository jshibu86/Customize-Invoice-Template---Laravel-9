<div style="font-size:10px; font-weight:700; text-transform:uppercase;
            letter-spacing:1px; color:#fff;
            background:{{ $template->primary_color }};
            padding:3px 10px; display:inline-block;
            border-radius:3px; margin-bottom:8px;">Bill To</div>
<div style="font-size:11px; line-height:1.7; color:#333;">
    <strong>{{ $invoice->client_name ?? 'John Client' }}</strong><br>
    {{ $invoice->client_address ?? '456 Client Avenue' }}<br>
    {{ $invoice->client_city ?? 'City, State, ZIP' }}<br>
    {{ $invoice->client_email ?? 'client@email.com' }}
</div>