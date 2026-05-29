<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }

    body {
        font-family: {{ $template->font_family ?? 'DejaVu Sans' }}, sans-serif;
        font-size: 12px;
        color: #333;
        background: {{ isset($forPdf) && $forPdf ? '#fff' : '#f0f2f5' }};
        margin: 0;
        padding: 0;
    }

    /* A4 = 210mm x 297mm. At 96dpi → 794px x 1123px
       dompdf default DPI is 96. Keep padding small to avoid overflow. */
    .page {
        width: 794px;
        min-height: 1123px;
        background: #fff;
        margin: 0 auto;
        @if(isset($forPdf) && $forPdf)
        padding: 30px 40px;   /* smaller padding for PDF */
        @else
        padding: 40px 50px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        margin-top: 24px;
        margin-bottom: 24px;
        @endif
    }

    .pdf-row {
        width: 100%;
        margin-bottom: {{ isset($forPdf) && $forPdf ? '14px' : '20px' }};
    }

    .pdf-row-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .pdf-cell {
        vertical-align: top;
        padding: 0;
    }

    /* Small gap between side-by-side blocks */
    .pdf-cell-gap {
        width: 14px;
        padding: 0;
    }
</style>
</head>
<body>
<div class="page" style="padding-right:40px !important;">

    @foreach($rows as $row)
        <div class="pdf-row">

            @if(count($row['blocks']) === 1)
                {{-- Single full-width block --}}
                @php
                    $block    = $row['blocks'][0];
                    $viewPath = 'invoices.blocks.' . $block['id'];
                @endphp
                @if(view()->exists($viewPath))
                    @include($viewPath, [
                        'invoice'  => $invoice,
                        'template' => $template,
                        'fields'   => $fields,
                        'forPdf'   => $forPdf ?? false,
                    ])
                @endif

            @else
                {{-- Multiple blocks side by side --}}
                @php
                    $totalW   = $row['totalW'];
                    $gapTotal = (count($row['blocks']) - 1) * 14; // 14px gap each
                    $usableW  = $totalW - $gapTotal;
                @endphp
                <table class="pdf-row-table">
                    <colgroup>
                        @foreach($row['blocks'] as $i => $block)
                            @php $pct = round(($block['w'] / $usableW) * 100, 2); @endphp
                            <col style="width:{{ $pct }}%">
                            @if(!$loop->last)
                                <col style="width:14px">
                            @endif
                        @endforeach
                    </colgroup>
                    <tr>
                        @foreach($row['blocks'] as $i => $block)
                            @php $viewPath = 'invoices.blocks.' . $block['id']; @endphp
                            @if(view()->exists($viewPath))
                                <td class="pdf-cell">
                                    @include($viewPath, [
                                        'invoice'  => $invoice,
                                        'template' => $template,
                                        'fields'   => $fields,
                                        'forPdf'   => $forPdf ?? false,
                                    ])
                                </td>
                                @if(!$loop->last)
                                    <td class="pdf-cell-gap"></td>
                                @endif
                            @endif
                        @endforeach
                    </tr>
                </table>
            @endif

        </div>
    @endforeach

</div>
</body>
</html>