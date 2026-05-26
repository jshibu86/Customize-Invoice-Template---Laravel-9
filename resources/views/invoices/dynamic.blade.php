<!DOCTYPE html>
<html><head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family: {{ $template->font_family ?? 'DejaVu Sans' }}, sans-serif;
    font-size:12px; color:#333; background:#fff;
}
.page { position:relative; width:794px; min-height:1123px; padding:0; }
.pdf-block { position:absolute; overflow:hidden; padding:10px; }
</style>
</head><body>
<div class="page">
@foreach($blocks as $block)
    @php
        $bid      = $block['id'];
        $viewPath = 'invoices.blocks.' . $bid;
        $x = $block['x'] ?? 0;
        $y = $block['y'] ?? 0;
        $w = $block['w'] ?? 300;
        $h = $block['h'] ?? 100;
    @endphp
    @if(view()->exists($viewPath))
    <div class="pdf-block"
         style="left:{{ $x }}px; top:{{ $y }}px;
                width:{{ $w }}px; height:{{ $h }}px;">
        @include($viewPath, [
            'invoice'  => $invoice,
            'template' => $template,
            'fields'   => $fields,
            'forPdf'   => true,
        ])
    </div>
    @endif
@endforeach
</div>
</body></html>