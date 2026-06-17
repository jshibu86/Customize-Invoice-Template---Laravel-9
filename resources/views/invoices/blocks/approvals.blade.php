<table width="100%" style="border-collapse:collapse; font-size:11px;">
    <tr>
        <td style="padding-bottom:8px; font-size:10px; text-transform:uppercase; letter-spacing:1px; color:{{ $template->primary_color ?? '#2563eb' }}; font-weight:bold;">
            Approvals
        </td>
    </tr>
    <tr>
        <td style="padding:10px 0 18px; border-bottom:1px solid #d1d5db;">
            Prepared by: {{ $invoice->prepared_by ?? 'Finance Team' }}
        </td>
    </tr>
    <tr>
        <td style="padding:10px 0 18px; border-bottom:1px solid #d1d5db;">
            Approved by: {{ $invoice->approved_by ?? 'Operations Director' }}
        </td>
    </tr>
</table>
