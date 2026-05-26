<table width="100%" style="border-collapse:collapse; font-size:12px;">
    <thead>
        <tr style="background:{{ $template->primary_color }}; color:#fff;">
            <th style="padding:9px 12px; text-align:left; width:45%;">Description</th>
            <th style="padding:9px 12px; text-align:center; width:15%;">Qty</th>
            <th style="padding:9px 12px; text-align:right; width:20%;">Unit Price</th>
            <th style="padding:9px 12px; text-align:right; width:20%;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
        $items = $invoice->items ?? [
            ['description' => 'Sample Service', 'qty' => 2, 'unit_price' => 500.00, 'amount' => 1000.00],
        ];
        @endphp
        @foreach($items as $i => $item)
        <tr style="background:{{ $i % 2 === 0 ? '#fafafa' : '#fff' }};">
            <td style="padding:9px 12px; border-bottom:1px solid #eee;">
                {{ $item['description'] ?? '-' }}
            </td>
            <td style="padding:9px 12px; text-align:center; border-bottom:1px solid #eee;">
                {{ $item['qty'] ?? 1 }}
            </td>
            <td style="padding:9px 12px; text-align:right; border-bottom:1px solid #eee;">
                {{ number_format($item['unit_price'] ?? 0, 2) }}
            </td>
            <td style="padding:9px 12px; text-align:right; border-bottom:1px solid #eee;">
                {{ number_format($item['amount'] ?? 0, 2) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>