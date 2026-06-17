<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Invoice Template</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f8;
            color: #1f2937;
        }
        .shell {
            max-width: 1180px;
            margin: 0 auto;
            padding: 32px 24px 48px;
        }
        .top {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 24px;
        }
        h1 {
            margin: 0 0 6px;
            font-size: 28px;
            line-height: 1.2;
            color: #111827;
        }
        .sub {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }
        .tabs {
            display: inline-flex;
            gap: 4px;
            padding: 4px;
            margin-bottom: 22px;
            background: #e8edf3;
            border: 1px solid #d7dee8;
            border-radius: 8px;
        }
        .tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 8px 14px;
            border-radius: 6px;
            color: #475569;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
        }
        .tab.active {
            background: #fff;
            color: #111827;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.10);
        }
        .count {
            margin-left: 7px;
            min-width: 22px;
            padding: 2px 7px;
            border-radius: 999px;
            background: #dbe3ee;
            color: #334155;
            font-size: 11px;
            text-align: center;
        }
        .tab.active .count {
            background: #111827;
            color: #fff;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 18px;
        }
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(17, 24, 39, 0.06);
        }
        .preview {
            height: 320px;
            padding: 18px;
            background: #eef2f7;
        }
        .paper {
            height: 100%;
            background: #fff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 8px 18px rgba(17, 24, 39, 0.10);
            position: relative;
            overflow: hidden;
        }
        .mini-block {
            position: absolute;
            border: 1px solid #dbe3ee;
            background: #fff;
            overflow: hidden;
        }
        .mini-title {
            border: 0;
            background: transparent;
            color: inherit;
            font-size: 13px;
            font-weight: 900;
            text-align: right;
        }
        .mini-label {
            height: 8px;
            width: 70%;
            margin: 8px;
            border-radius: 4px;
            background: #dbe3ee;
        }
        .mini-label.short { width: 45%; }
        .mini-table-row {
            height: 16px;
            border-bottom: 1px solid #eef2f7;
        }
        .mini-table-head {
            height: 18px;
        }
        .mini-totals {
            border-top: 3px solid;
            background: #fff;
        }
        .mini-chip {
            position: absolute;
            left: 6px;
            bottom: 5px;
            font-size: 8px;
            line-height: 1;
            color: #64748b;
            text-transform: uppercase;
        }
        .meta {
            padding: 14px 16px 16px;
        }
        .name {
            margin: 0 0 10px;
            font-size: 15px;
            font-weight: 800;
        }
        .badge {
            display: inline-flex;
            margin-bottom: 9px;
            padding: 4px 8px;
            border-radius: 999px;
            background: #eef2ff;
            color: #3730a3;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .badge.saved {
            background: #ecfdf3;
            color: #027a48;
        }
        .actions {
            display: flex;
            gap: 8px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 7px 11px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }
        .btn-primary {
            color: #fff;
            background: #111827;
        }
        .btn-muted {
            color: #374151;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
        }
        .empty {
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 24px;
            color: #64748b;
        }
        @media (max-width: 640px) {
            .shell { padding: 22px 14px 34px; }
            .top { display: block; }
            h1 { font-size: 23px; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <div class="top">
            <div>
                <h1>Choose an invoice template</h1>
                <p class="sub">Pick a starting design, then customize the layout, colors, fields, logo, notes, and PDF.</p>
            </div>
        </div>

        <nav class="tabs">
            <a class="tab {{ $activeTab === 'starter' ? 'active' : '' }}" href="{{ route('invoice.templates') }}">
                Starter Templates <span class="count">{{ $starterTemplates->count() }}</span>
            </a>
            <a class="tab {{ $activeTab === 'saved' ? 'active' : '' }}" href="{{ route('invoice.templates', ['tab' => 'saved']) }}">
                Saved Templates <span class="count">{{ $savedTemplates->count() }}</span>
            </a>
        </nav>

        @php
            $templates = $activeTab === 'saved' ? $savedTemplates : $starterTemplates;
            $emptyMessage = $activeTab === 'saved'
                ? 'No saved templates yet. Customize a starter template and click Save Template.'
                : 'No starter invoice templates found. Run the invoice template seeder to create starter designs.';
        @endphp

        @if($templates->isEmpty())
            <div class="empty">
                {{ $emptyMessage }}
            </div>
        @else
            <div class="grid">
                @foreach($templates as $template)
                    @php
                        $primary = $template->primary_color ?? '#2563eb';
                        $secondary = $template->secondary_color ?? '#1f2937';
                        $blocks = collect($template->layout['blocks'] ?? [])->where('visible', true)->sortBy('position');
                        $tableBlocks = ['items', 'tax_summary', 'payment_details'];
                    @endphp
                    <article class="card">
                        <div class="preview">
                            <div class="paper">
                                @foreach($blocks as $block)
                                    @php
                                        $left = (($block['x'] ?? 0) / 694) * 100;
                                        $top = (($block['y'] ?? 0) / 900) * 100;
                                        $width = (($block['w'] ?? 200) / 694) * 100;
                                        $height = (($block['h'] ?? 80) / 900) * 100;
                                        $id = $block['id'] ?? '';
                                    @endphp
                                    <div class="mini-block {{ $id === 'invoice_title' || $id === 'header' ? 'mini-title' : '' }} {{ $id === 'totals' ? 'mini-totals' : '' }}"
                                         style="left:{{ $left }}%; top:{{ $top }}%; width:{{ $width }}%; height:{{ $height }}%; color:{{ $primary }}; border-color:{{ $id === 'totals' ? $primary : '#dbe3ee' }};">
                                        @if($id === 'invoice_title' || $id === 'header')
                                            INVOICE
                                        @elseif(in_array($id, $tableBlocks, true))
                                            <div class="mini-table-head" style="background:{{ $id === 'tax_summary' ? $secondary : $primary }}"></div>
                                            <div class="mini-table-row"></div>
                                            <div class="mini-table-row"></div>
                                            <div class="mini-table-row"></div>
                                            <span class="mini-chip">{{ str_replace('_', ' ', $id) }}</span>
                                        @elseif($id === 'totals')
                                            <div class="mini-label"></div>
                                            <div class="mini-label short"></div>
                                            <span class="mini-chip">total</span>
                                        @else
                                            <div class="mini-label"></div>
                                            <div class="mini-label short"></div>
                                            <span class="mini-chip">{{ str_replace('_', ' ', $id) }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="meta">
                            <span class="badge {{ $template->template_type === 'saved' ? 'saved' : '' }}">
                                {{ $template->template_type === 'saved' ? 'Saved' : 'Starter' }}
                            </span>
                            <h2 class="name">{{ $template->name ?? 'Invoice Template' }}</h2>
                            <div class="actions">
                                <a class="btn btn-primary" href="{{ route('invoice.builder', $template->id) }}">
                                    {{ $template->template_type === 'saved' ? 'Edit Saved' : 'Customize' }}
                                </a>
                                <a class="btn btn-muted" href="{{ route('invoice.preview-html', $template->id) }}" target="_blank">Preview</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
