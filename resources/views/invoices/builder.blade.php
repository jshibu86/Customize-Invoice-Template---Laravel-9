<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Template Builder</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.27/dist/interact.min.js"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Segoe UI',sans-serif;
            background:#f0f2f5;
            display:flex;
            height:100vh;
            overflow:hidden;
        }

        /* ── PANEL ── */
        #panel {
            width:260px; min-width:260px;
            background:#1e1e2e; color:#cdd6f4;
            display:flex; flex-direction:column;
            overflow-y:auto;
        }
        #panel-header {
            padding:13px 16px;
            background:#181825;
            border-bottom:1px solid #313244;
            font-size:14px; font-weight:700; color:#cba6f7;
        }
        .section { padding:12px 14px; border-bottom:1px solid #313244; }
        .section-title {
            font-size:9px; text-transform:uppercase;
            letter-spacing:1px; color:#6c7086;
            margin-bottom:8px; font-weight:600;
        }
        .panel-label {
            display:block; font-size:10px;
            color:#a6adc8; margin-bottom:2px; margin-top:7px;
        }
        select {
            width:100%; padding:6px 8px;
            background:#313244; border:1px solid #45475a;
            border-radius:5px; color:#cdd6f4;
            font-size:11px; outline:none;
        }
        input[type="color"] {
            width:100%; height:32px; border:none;
            border-radius:5px; cursor:pointer;
            background:#313244; padding:2px;
        }
        .logo-upload {
            width:100%; padding:10px;
            border:2px dashed #45475a; border-radius:6px;
            text-align:center; cursor:pointer;
            color:#6c7086; font-size:10px;
            transition:border-color 0.2s; margin-top:6px;
        }
        .logo-upload:hover { border-color:#cba6f7; color:#cba6f7; }
        #logo-preview-panel {
            max-width:100%; max-height:40px;
            margin-top:5px; border-radius:3px; display:none;
        }

        /* block visibility toggles */
        .vis-item {
            display:flex; align-items:center;
            justify-content:space-between;
            padding:5px 8px;
            background:#313244; border-radius:5px;
            margin-bottom:4px; font-size:11px;
        }
        .toggle { position:relative; width:30px; height:16px; flex-shrink:0; }
        .toggle input { display:none; }
        .toggle-slider {
            position:absolute; inset:0;
            background:#45475a; border-radius:16px;
            cursor:pointer; transition:0.2s;
        }
        .toggle-slider:before {
            content:''; position:absolute;
            width:10px; height:10px; background:white;
            border-radius:50%; top:3px; left:3px; transition:0.2s;
        }
        .toggle input:checked + .toggle-slider { background:#a6e3a1; }
        .toggle input:checked + .toggle-slider:before { transform:translateX(14px); }

        /* fields */
        .field-item {
            display:flex; align-items:center; gap:6px;
            padding:6px 8px; background:#313244;
            border-radius:5px; margin-bottom:4px;
            cursor:grab; border:1px solid #45475a;
            font-size:10px; user-select:none;
        }
        .field-item:hover { background:#3d3d56; }
        .field-item.sortable-ghost { opacity:0.25; }
        .drag-icon { color:#585b70; font-size:12px; pointer-events:none; }

        textarea {
            width:100%; padding:6px 8px;
            background:#313244; border:1px solid #45475a;
            border-radius:5px; color:#cdd6f4;
            font-size:10px; resize:vertical;
            min-height:55px; outline:none; font-family:inherit;
        }

        #save-btn {
            margin:12px 14px 4px;
            padding:10px; background:#cba6f7;
            color:#1e1e2e; border:none;
            border-radius:7px; font-size:12px;
            font-weight:700; cursor:pointer;
            width:calc(100% - 28px);
            transition:background 0.2s;
        }
        #save-btn:hover { background:#b48de0; }
        #save-msg {
            text-align:center; font-size:10px;
            padding-bottom:8px; color:#a6e3a1; display:none;
        }

        /* ── CANVAS AREA ── */
        #canvas-area {
            flex:1; display:flex;
            flex-direction:column; overflow:hidden;
            background:#e5e7eb;
        }
        #canvas-topbar {
            background:#fff; padding:9px 14px;
            border-bottom:1px solid #e0e0e0;
            display:flex; align-items:center;
            justify-content:space-between; flex-shrink:0;
        }
        #canvas-topbar span { font-size:11px; color:#888; }
        .btn-pdf {
            padding:6px 12px; background:#1e1e2e;
            color:#fff; border:none; border-radius:5px;
            font-size:11px; cursor:pointer;
            font-weight:600; text-decoration:none;
        }

        /* ── A4 SCROLL WRAPPER ── */
        #paper-scroll {
            flex:1; overflow:auto;
            padding:24px; display:flex;
            justify-content:center; align-items:flex-start;
        }

        /* ── A4 PAPER ── */
        #a4-paper {
            background:#fff;
            width:794px;
            min-height:1123px;
            position:relative;
            box-shadow:0 4px 24px rgba(0,0,0,0.13);
            border-radius:3px;
            flex-shrink:0;
        }

        /* ── BLOCKS ── */
        .inv-block {
            position:absolute;
            border:2px dashed transparent;
            border-radius:5px;
            overflow:hidden;
            transition:border-color 0.15s;
            cursor:move;
            background:#fff;
            padding:10px;
            font-size:12px;
            color:#333;
        }
        .inv-block:hover {
            border-color:#cba6f7;
            z-index:10;
        }
        .inv-block.active {
            border-color:#cba6f7;
            z-index:20;
            box-shadow:0 2px 12px rgba(203,166,247,0.25);
        }
        .inv-block.hidden-block { display:none; }

        /* block label */
        .blk-label {
            display:none;
            position:absolute;
            top:-11px; left:6px;
            background:#cba6f7; color:#1e1e2e;
            font-size:8px; font-weight:800;
            padding:1px 7px; border-radius:3px;
            text-transform:uppercase;
            letter-spacing:0.5px;
            pointer-events:none; z-index:30;
            white-space:nowrap;
        }
        .inv-block:hover .blk-label,
        .inv-block.active .blk-label { display:block; }

        /* resize handle */
        .resize-handle {
            position:absolute;
            bottom:0; right:0;
            width:14px; height:14px;
            cursor:se-resize;
            background:linear-gradient(135deg, transparent 50%, #cba6f7 50%);
            border-radius:0 0 4px 0;
            opacity:0;
            transition:opacity 0.15s;
        }
        .inv-block:hover .resize-handle,
        .inv-block.active .resize-handle { opacity:1; }
    </style>
</head>
<body>

{{-- ════ LEFT PANEL ════ --}}
<div id="panel">
    <div id="panel-header">🧾 Invoice Builder</div>

    {{-- BRANDING --}}
    <div class="section">
        <div class="section-title">🎨 Branding</div>
        <span class="panel-label">Logo</span>
        <div class="logo-upload" onclick="document.getElementById('logo-input').click()">
            📁 Click to upload &nbsp;<span style="font-size:9px;color:#6c7086">PNG/JPG 2MB</span>
        </div>
        <input type="file" id="logo-input" accept="image/*" style="display:none">
        <img id="logo-preview-panel" src="" alt="">

        <span class="panel-label">Primary Color</span>
        <input type="color" id="primary-color" value="{{ $template->primary_color ?? '#3B82F6' }}">

        <span class="panel-label">Secondary Color</span>
        <input type="color" id="secondary-color" value="{{ $template->secondary_color ?? '#1E40AF' }}">

        <span class="panel-label">Font</span>
        <select id="font-family" onchange="document.getElementById('a4-paper').style.fontFamily=this.value">
            @foreach(['DejaVu Sans','Arial','Courier','Helvetica','Times New Roman'] as $font)
                <option value="{{ $font }}"
                    {{ ($template->font_family ?? 'DejaVu Sans') === $font ? 'selected' : '' }}>
                    {{ $font }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- BLOCK VISIBILITY --}}
    <div class="section">
        <div class="section-title">👁 Block Visibility</div>
        @foreach(collect($template->layout['blocks'])->sortBy('position') as $block)
        <div class="vis-item">
            <span>{{ ucfirst(str_replace('_', ' ', $block['id'])) }}</span>
            <label class="toggle">
                <input type="checkbox" class="vis-toggle"
                       data-id="{{ $block['id'] }}"
                       {{ $block['visible'] ? 'checked' : '' }}
                       onchange="toggleBlock('{{ $block['id'] }}', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>
        @endforeach
    </div>

    {{-- FIELDS --}}
    <div class="section">
        <div class="section-title">🔢 Fields</div>
        <div id="fields-list">
            @foreach(collect($template->fields_config)->sortBy('order') as $key => $field)
            <div class="field-item" data-key="{{ $key }}">
                <span class="drag-icon">⠿</span>
                <label class="toggle" onclick="event.stopPropagation()">
                    <input type="checkbox" class="field-toggle"
                           {{ $field['visible'] ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
                <span>{{ $field['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- NOTES --}}
    <div class="section">
        <div class="section-title">📝 Notes</div>
        <textarea id="notes-text"
                  oninput="updateNotes(this.value)"
                  placeholder="Notes / terms...">{{ $template->notes ?? '' }}</textarea>
    </div>

    <button id="save-btn" onclick="saveTemplate()">💾 Save Template</button>
    <div id="save-msg">✅ Saved!</div>
</div>

{{-- ════ A4 CANVAS ════ --}}
<div id="canvas-area">
    <div id="canvas-topbar">
        <span>↔ Drag to move &nbsp;|&nbsp; ↘ Corner to resize &nbsp;|&nbsp; 👁 Toggle visibility in panel</span>
        <!-- <a class="btn-pdf" href="/invoice/preview" target="_blank">⬇ Download PDF</a> -->
         <button type="button" class="btn-pdf">⬇ Download PDF</button>
    </div>
    <div id="paper-scroll">
        <div id="a4-paper" style="background:#fff !important;border:none !important;">
            @php
                $blockDefs = collect($template->layout['blocks'])->sortBy('position');
                $fields    = collect($template->fields_config)->where('visible', true)->sortBy('order');
            @endphp

            @foreach($blockDefs as $block)
                @php
                    $bid      = $block['id'];
                    $visible  = $block['visible'] ?? true;
                    $x        = $block['x'] ?? 0;
                    $y        = $block['y'] ?? 0;
                    $w        = $block['w'] ?? 300;
                    $h        = $block['h'] ?? 100;
                    $viewPath = 'invoices.blocks.' . $bid;
                @endphp

                @if(view()->exists($viewPath))
                <div class="inv-block {{ !$visible ? 'hidden-block' : '' }}"
                     id="blk-{{ $bid }}"
                     data-id="{{ $bid }}"
                     style="left:{{ $x }}px; top:{{ $y }}px;
                            width:{{ $w }}px; height:{{ $h }}px;">
                    <div class="blk-label">{{ ucfirst(str_replace('_',' ',$bid)) }}</div>
                    @include($viewPath, [
                        'invoice'  => $invoice,
                        'template' => $template,
                        'fields'   => $fields,
                        'forPdf'   => false,
                    ])
                    <div class="resize-handle"></div>
                </div>
                @endif
            @endforeach

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.27/dist/interact.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    const originalFields = @json($template->fields_config);

    // ── Interact.js: drag + resize every block ──
    interact('.inv-block').draggable({
        listeners: {
            start(e) { e.target.classList.add('active'); },
            move(e) {
                const el = e.target;
                const x = (parseFloat(el.style.left) || 0) + e.dx;
                const y = (parseFloat(el.style.top)  || 0) + e.dy;
                el.style.left = Math.max(0, x) + 'px';
                el.style.top  = Math.max(0, y) + 'px';
            },
            end(e) { e.target.classList.remove('active'); }
        }
    }).resizable({
        edges: { right: true, bottom: true, bottomRight: '.resize-handle' },
        modifiers: [
            interact.modifiers.restrictSize({ minWidth: 80, minHeight: 40 })
        ],
        listeners: {
            start(e) { e.target.classList.add('active'); },
            move(e) {
                const el = e.target;
                el.style.width  = e.rect.width  + 'px';
                el.style.height = e.rect.height + 'px';
            },
            end(e) { e.target.classList.remove('active'); }
        }
    });

    // ── Toggle block visibility ──
    function toggleBlock(id, visible) {
        const el = document.getElementById('blk-' + id);
        if (el) el.classList.toggle('hidden-block', !visible);
    }

    // ── Logo upload → live update on paper ──
    document.getElementById('logo-input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const panel = document.getElementById('logo-preview-panel');
            panel.src = e.target.result;
            panel.style.display = 'block';

            const logoBlk = document.querySelector('#blk-logo img');
            if (logoBlk) {
                logoBlk.src = e.target.result;
            } else {
                const logoBlkDiv = document.querySelector('#blk-logo div:not(.blk-label):not(.resize-handle)');
                if (logoBlkDiv) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText = 'max-height:70px;max-width:100%;display:block;';
                    logoBlkDiv.replaceWith(img);
                }
            }
        };
        reader.readAsDataURL(file);
    });

    // ── Live notes update ──
    function updateNotes(val) {
        const el = document.querySelector('#blk-notes');
        if (el) {
            const content = el.querySelectorAll('div:not(.blk-label):not(.resize-handle)');
            if (content.length > 0) {
                content[content.length - 1].textContent = val;
            }
        }
    }

    // ── Fields sortable ──
    Sortable.create(document.getElementById('fields-list'), {
        animation: 150,
        ghostClass: 'sortable-ghost',
        handle: '.drag-icon',
    });

    // ── Collect block positions from DOM ──
    function getLayoutConfig() {
        const blocks = [];
        document.querySelectorAll('#a4-paper .inv-block').forEach((el, idx) => {
            blocks.push({
                id:       el.dataset.id,
                position: idx + 1,
                visible:  !el.classList.contains('hidden-block'),
                x: Math.round(parseFloat(el.style.left) || 0),
                y: Math.round(parseFloat(el.style.top)  || 0),
                w: Math.round(parseFloat(el.style.width)  || 200),
                h: Math.round(parseFloat(el.style.height) || 100),
            });
        });
        return { blocks };
    }

    // ── Collect fields ──
    function getFieldsConfig() {
        const fields = {};
        document.querySelectorAll('#fields-list .field-item').forEach((el, idx) => {
            const key = el.dataset.key;
            fields[key] = {
                visible: el.querySelector('.field-toggle').checked,
                order:   idx + 1,
                label:   originalFields[key]?.label ?? key,
            };
        });
        return fields;
    }

    // ── Save ──
    function saveTemplate() {
        const btn = document.getElementById('save-btn');
        btn.textContent = '⏳ Saving...';
        btn.disabled = true;

        const fd = new FormData();
        fd.append('_token',         document.querySelector('meta[name="csrf-token"]').content);
        fd.append('primary_color',  document.getElementById('primary-color').value);
        fd.append('secondary_color',document.getElementById('secondary-color').value);
        fd.append('font_family',    document.getElementById('font-family').value);
        fd.append('layout',         JSON.stringify(getLayoutConfig()));
        fd.append('fields_config',  JSON.stringify(getFieldsConfig()));
        fd.append('notes',          document.getElementById('notes-text').value);

        const logoFile = document.getElementById('logo-input').files[0];
        if (logoFile) fd.append('logo', logoFile);

        fetch('/invoice/save', { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            btn.textContent = '💾 Save Template';
            btn.disabled = false;
            if (data.success) {
                const msg = document.getElementById('save-msg');
                msg.style.display = 'block';
                setTimeout(() => msg.style.display = 'none', 3000);
            }
        })
        .catch((error) => {
            btn.textContent = '💾 Save Template';
            btn.disabled = false;
            console.log("error:",error);
            alert('Something went wrong.');
        });
    }
</script>
<script>
    // ── Print ──
function printInvoice() {

    let printContents = document.getElementById('a4-paper').outerHTML;
    let originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;

    location.reload();
}

document.querySelector('.btn-pdf').addEventListener('click', printInvoice);
</script>
</body>
</html>