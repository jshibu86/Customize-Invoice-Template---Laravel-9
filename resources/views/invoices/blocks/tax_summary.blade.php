<table width="100%" style="border-collapse:collapse; font-size:11px;">
    <thead>
        <tr style="background:{{ $template->secondary_color ?? '#334155' }}; color:#fff;">
            <th style="padding:8px 10px; text-align:left;">Tax Type</th>
            <th style="padding:8px 10px; text-align:right;">Rate</th>
            <th style="padding:8px 10px; text-align:right;">Taxable</th>
            <th style="padding:8px 10px; text-align:right;">Tax</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->tax_lines ?? [] as $line)
            <tr>
                <td style="padding:8px 10px; border-bottom:1px solid #e5e7eb;">{{ $line['name'] }}</td>
                <td style="padding:8px 10px; border-bottom:1px solid #e5e7eb; text-align:right;">{{ $line['rate'] }}%</td>
                <td style="padding:8px 10px; border-bottom:1px solid #e5e7eb; text-align:right;">{{ number_format($line['taxable'], 2) }}</td>
                <td style="padding:8px 10px; border-bottom:1px solid #e5e7eb; text-align:right;">{{ number_format($line['amount'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
