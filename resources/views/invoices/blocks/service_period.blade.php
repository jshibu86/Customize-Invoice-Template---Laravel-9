<div style="background:#f8fafc; border:1px solid #e5e7eb; padding:12px 14px; font-size:11px;">
    <div style="font-size:10px; text-transform:uppercase; letter-spacing:1px; color:{{ $template->primary_color ?? '#2563eb' }}; font-weight:bold; margin-bottom:8px;">
        Service Period
    </div>
    <div style="font-size:18px; font-weight:bold; color:#111827;">
        {{ $invoice->service_period ?? 'Jan 01 - Jan 31, 2026' }}
    </div>
    <div style="margin-top:6px; color:#6b7280; line-height:1.5;">
        {{ $invoice->service_description ?? 'Monthly managed services, support, and delivery operations.' }}
    </div>
</div>
