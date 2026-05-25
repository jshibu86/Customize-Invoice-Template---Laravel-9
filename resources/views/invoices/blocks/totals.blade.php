{{-- resources/views/invoices/blocks/totals.blade.php --}}
<table width="100%" style="margin-bottom: 30px;">
    <tr>
        <td width="60%"></td>
        <td width="40%">
            <table width="100%" style="font-size: 13px;">

                <tr>
                    <td style="padding: 6px 0; color: #555;">Subtotal:</td>
                    <td style="padding: 6px 0; text-align: right;">
                        {{ number_format($invoice->subtotal ?? 1250.00, 2) }}
                    </td>
                </tr>

                <tr>
                    <td style="padding: 6px 0; color: #555;">
                        Tax ({{ $invoice->tax_percent ?? 10 }}%):
                    </td>
                    <td style="padding: 6px 0; text-align: right;">
                        {{ number_format($invoice->tax_amount ?? 125.00, 2) }}
                    </td>
                </tr>

                @if(!empty($invoice->discount_amount))
                <tr>
                    <td style="padding: 6px 0; color: #555;">Discount:</td>
                    <td style="padding: 6px 0; text-align: right; color: red;">
                        - {{ number_format($invoice->discount_amount, 2) }}
                    </td>
                </tr>
                @endif

                {{-- Grand Total --}}
                <tr>
                    <td colspan="2">
                        <div style="border-top: 2px solid {{ $template->primary_color }};
                                    margin: 6px 0;"></div>
                    </td>
                </tr>
                <tr style="font-weight: bold; font-size: 15px;">
                    <td style="padding: 6px 0; color: {{ $template->primary_color }};">
                        Total:
                    </td>
                    <td style="padding: 6px 0; text-align: right;
                                color: {{ $template->primary_color }};">
                        {{ number_format($invoice->grand_total ?? 1375.00, 2) }}
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>