<div style="border:1px solid #e5e7eb; border-left:4px solid {{ $template->primary_color ?? '#2563eb' }}; padding:12px 14px; font-size:11px;">
    <div style="font-size:10px; text-transform:uppercase; letter-spacing:1px; color:{{ $template->primary_color ?? '#2563eb' }}; font-weight:bold; margin-bottom:7px;">
        Project Summary
    </div>
    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td style="padding:3px 0; color:#6b7280; width:35%;">Project</td>
            <td style="padding:3px 0; font-weight:bold;">{{ $invoice->project_name ?? 'Enterprise Portal Rollout' }}</td>
        </tr>
        <tr>
            <td style="padding:3px 0; color:#6b7280;">Contract</td>
            <td style="padding:3px 0;">{{ $invoice->contract_number ?? 'MSA-2026-042' }}</td>
        </tr>
        <tr>
            <td style="padding:3px 0; color:#6b7280;">Cost Center</td>
            <td style="padding:3px 0;">{{ $invoice->cost_center ?? 'OPS-2048' }}</td>
        </tr>
    </table>
</div>
