<div style="border-top:1px solid #eee; padding-top:14px;">
    <div style="font-size:10px; font-weight:700; text-transform:uppercase;
                letter-spacing:1px; color:{{ $template->primary_color }};
                margin-bottom:6px;">
        Notes &amp; Terms
    </div>
    <div style="font-size:11px; color:#666; line-height:1.7;">
        {{ $invoice->notes ?? 'Thank you for your business! Payment is due within 30 days.' }}
    </div>
</div>