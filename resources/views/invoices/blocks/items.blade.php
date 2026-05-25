{{-- resources/views/invoices/blocks/items.blade.php --}}
<table width="100%" style="border-collapse: collapse; margin-bottom: 30px; font-size: 13px;">

    {{-- Table Header --}}
    <thead>
        <tr style="background: {{ $template->primary_color }}; color: #fff;">
            <th style="padding: 10px 12px; text-align: left;  width: 40%;">Description</th>
            <th style="padding: 10px 12px; text-align: center; width: 15%;">Qty</th>
            <th style="padding: 10px 12px; text-align: right;  width: 20%;">Unit Price</th>
            <th style="padding: 10px 12px; text-align: right;  width: 25%;">Amount</th>
        </tr>
    </thead>

    {{-- Table Body --}}
    <tbody>
        @php $items = $invoice->items ?? [
            ['description' => 'Sample Service',  'qty' => 2, 'unit_price' => 500.00, 'amount' => 1000.00],
            ['description' => 'Sample Product',  'qty' => 1, 'unit_price' => 250.00, 'amount' => 250.00],
        ]; @endphp

        @foreach($items as $index => $item)
            <tr style="background: {{ $index % 2 === 0 ? '#f9f9f9' : '#ffffff' }};">
                <td style="padding: 10px 12px; border-bottom: 1px solid #eee;">
                    {{ $item['description'] ?? '-' }}
                </td>
                <td style="padding: 10px 12px; text-align: center; border-bottom: 1px solid #eee;">
                    {{ $item['qty'] ?? 1 }}
                </td>
                <td style="padding: 10px 12px; text-align: right; border-bottom: 1px solid #eee;">
                    {{ number_format($item['unit_price'] ?? 0, 2) }}
                </td>
                <td style="padding: 10px 12px; text-align: right; border-bottom: 1px solid #eee;">
                    {{ number_format($item['amount'] ?? 0, 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>