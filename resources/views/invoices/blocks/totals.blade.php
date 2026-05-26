<div style="display:flex; justify-content:flex-end;">
    <table style="font-size:12px; min-width:260px;">
        <tr>
            <td style="padding:5px 12px 5px 0; color:#666;">Subtotal:</td>
            <td style="padding:5px 0; text-align:right; color:#333;">
                {{ number_format($invoice->subtotal ?? 1550.00, 2) }}
            </td>
        </tr>
        <tr>
            <td style="padding:5px 12px 5px 0; color:#666;">
                Tax ({{ $invoice->tax_percent ?? 10 }}%):
            </td>
            <td style="padding:5px 0; text-align:right; color:#333;">
                {{ number_format($invoice->tax_amount ?? 155.00, 2) }}
            </td>
        </tr>
        @if(!empty($invoice->discount_amount))
        <tr>
            <td style="padding:5px 12px 5px 0; color:#666;">Discount:</td>
            <td style="padding:5px 0; text-align:right; color:red;">
                - {{ number_format($invoice->discount_amount, 2) }}
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="2">
                <div style="border-top:2px solid {{ $template->primary_color }};
                            margin:6px 0;"></div>
            </td>
        </tr>
        <tr>
            <td style="padding:6px 12px 6px 0; font-weight:bold; font-size:14px;
                        color:{{ $template->primary_color }};">
                Total:
            </td>
            <td style="padding:6px 0; text-align:right; font-weight:bold;
                        font-size:14px; color:{{ $template->primary_color }};">
                {{ number_format($invoice->grand_total ?? 1705.00, 2) }}
            </td>
        </tr>
    </table>
</div>