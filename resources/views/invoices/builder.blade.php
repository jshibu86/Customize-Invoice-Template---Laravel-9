<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Template Builder</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SortableJS for drag & drop --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ── LEFT PANEL ── */
        #panel {
            width: 320px;
            min-width: 320px;
            background: #1e1e2e;
            color: #cdd6f4;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        #panel-header {
            padding: 20px;
            background: #181825;
            border-bottom: 1px solid #313244;
            font-size: 16px;
            font-weight: 700;
            color: #cba6f7;
            letter-spacing: 0.5px;
        }

        .section {
            padding: 16px 20px;
            border-bottom: 1px solid #313244;
        }

        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c7086;
            margin-bottom: 12px;
            font-weight: 600;
        }

        label {
            display: block;
            font-size: 12px;
            color: #a6adc8;
            margin-bottom: 4px;
            margin-top: 10px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px 10px;
            background: #313244;
            border: 1px solid #45475a;
            border-radius: 6px;
            color: #cdd6f4;
            font-size: 13px;
            outline: none;
        }

        input[type="color"] {
            width: 100%;
            height: 38px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background: #313244;
            padding: 2px;
        }

        .logo-upload {
            width: 100%;
            padding: 20px;
            border: 2px dashed #45475a;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            color: #6c7086;
            font-size: 12px;
            transition: border-color 0.2s;
            margin-top: 10px;
        }

        .logo-upload:hover { border-color: #cba6f7; color: #cba6f7; }

        #logo-preview {
            max-width: 100%;
            max-height: 60px;
            margin-top: 8px;
            border-radius: 4px;
            display: none;
        }

        /* ── BLOCKS LIST ── */
        .block-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            background: #313244;
            border-radius: 8px;
            margin-bottom: 6px;
            cursor: grab;
            border: 1px solid #45475a;
            transition: background 0.15s;
            user-select: none;
        }

        .block-item:hover { background: #3d3d56; }
        .block-item.sortable-ghost { opacity: 0.3; }
        .block-item.sortable-chosen { background: #45475a; }

        .block-left {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
        }

        .drag-handle { color: #6c7086; font-size: 16px; cursor: grab; }

        .toggle {
            position: relative;
            width: 36px;
            height: 20px;
        }

        .toggle input { display: none; }

        .toggle-slider {
            position: absolute;
            inset: 0;
            background: #45475a;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.2s;
        }

        .toggle-slider:before {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            background: white;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: 0.2s;
        }

        .toggle input:checked + .toggle-slider { background: #a6e3a1; }
        .toggle input:checked + .toggle-slider:before { transform: translateX(16px); }

        /* ── FIELDS LIST ── */
        .field-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            background: #313244;
            border-radius: 6px;
            margin-bottom: 5px;
            cursor: grab;
            border: 1px solid #45475a;
            font-size: 12px;
        }

        .field-item:hover { background: #3d3d56; }
        .field-item.sortable-ghost { opacity: 0.3; }

        /* ── NOTES ── */
        textarea {
            width: 100%;
            padding: 10px;
            background: #313244;
            border: 1px solid #45475a;
            border-radius: 6px;
            color: #cdd6f4;
            font-size: 12px;
            resize: vertical;
            min-height: 80px;
            outline: none;
            font-family: inherit;
        }

        /* ── SAVE BUTTON ── */
        #save-btn {
            margin: 16px 20px;
            padding: 12px;
            background: #cba6f7;
            color: #1e1e2e;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            width: calc(100% - 40px);
            transition: background 0.2s;
        }

        #save-btn:hover { background: #b48de0; }

        #save-msg {
            text-align: center;
            font-size: 12px;
            padding-bottom: 12px;
            color: #a6e3a1;
            display: none;
        }

        /* ── RIGHT PREVIEW ── */
        #preview-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        #preview-topbar {
            background: #fff;
            padding: 12px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #preview-topbar span {
            font-size: 13px;
            color: #888;
        }

        #open-pdf-btn {
            padding: 8px 16px;
            background: #1e1e2e;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }

        #preview-frame {
            flex: 1;
            width: 100%;
            border: none;
            background: #e5e7eb;
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════ --}}
{{-- LEFT PANEL                            --}}
{{-- ══════════════════════════════════════ --}}
<div id="panel">
    <div id="panel-header">🧾 Invoice Builder</div>

    {{-- BRANDING --}}
    <div class="section">
        <div class="section-title">🎨 Branding</div>

        {{-- Logo Upload --}}
        <label>Company Logo</label>
        <div class="logo-upload" onclick="document.getElementById('logo-input').click()">
            <div>📁 Click to upload logo</div>
            <div style="font-size:10px; margin-top:4px;">PNG, JPG up to 2MB</div>
        </div>
        <input type="file" id="logo-input" accept="image/*" style="display:none">
        <img id="logo-preview" src="" alt="Logo Preview">

        {{-- Colors --}}
        <label>Primary Color</label>
        <input type="color" id="primary-color" value="{{ $template->primary_color ?? '#3B82F6' }}">

        <label>Secondary Color</label>
        <input type="color" id="secondary-color" value="{{ $template->secondary_color ?? '#1E40AF' }}">

        {{-- Font --}}
        <label>Font Family</label>
        <select id="font-family">
            @foreach(['DejaVu Sans','Arial','Courier','Helvetica','Times New Roman'] as $font)
                <option value="{{ $font }}"
                    {{ ($template->font_family ?? 'DejaVu Sans') === $font ? 'selected' : '' }}>
                    {{ $font }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- BLOCKS --}}
    <div class="section">
        <div class="section-title">📦 Blocks — drag to reorder</div>
        <div id="blocks-list">
            @foreach(collect($template->layout['blocks'])->sortBy('position') as $block)
                <div class="block-item" data-id="{{ $block['id'] }}">
                    <div class="block-left">
                        <span class="drag-handle">⠿</span>
                        <span>{{ ucfirst(str_replace('_', ' ', $block['id'])) }}</span>
                    </div>
                    <label class="toggle">
                        <input type="checkbox"
                               class="block-toggle"
                               data-id="{{ $block['id'] }}"
                               {{ $block['visible'] ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    {{-- FIELDS --}}
    <div class="section">
        <div class="section-title">🔢 Fields — drag to reorder</div>
        <div id="fields-list">
            @foreach(collect($template->fields_config)->sortBy('order') as $key => $field)
                <div class="field-item" data-key="{{ $key }}">
                    <span class="drag-handle" style="color:#6c7086;">⠿</span>
                    <label class="toggle" style="margin:0;">
                        <input type="checkbox"
                               class="field-toggle"
                               data-key="{{ $key }}"
                               {{ $field['visible'] ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                    <span style="font-size:12px;">{{ $field['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- NOTES --}}
    <div class="section">
        <div class="section-title">📝 Notes / Footer Text</div>
        <textarea id="notes-text" placeholder="Enter notes or terms...">{{ old('notes', '') }}</textarea>
    </div>

    {{-- SAVE --}}
    <button id="save-btn" onclick="saveTemplate()">💾 Save Template</button>
    <div id="save-msg">✅ Template saved successfully!</div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- RIGHT PREVIEW                         --}}
{{-- ══════════════════════════════════════ --}}
<div id="preview-area">
    <div id="preview-topbar">
        <span>🔍 Live Preview — updates on save</span>
        <a id="open-pdf-btn" href="/invoice/preview" target="_blank">⬇ Download PDF</a>
    </div>
    <iframe id="preview-frame" src="/invoice/preview"></iframe>
</div>

<script>
    // ── Drag & Drop: Blocks ──
    Sortable.create(document.getElementById('blocks-list'), {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
    });

    // ── Drag & Drop: Fields ──
    Sortable.create(document.getElementById('fields-list'), {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
    });

    // ── Logo Preview ──
    document.getElementById('logo-input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('logo-preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    // ── Collect Layout from DOM ──
    function getLayoutConfig() {
        const blocks = [];
        document.querySelectorAll('#blocks-list .block-item').forEach((el, index) => {
            blocks.push({
                id:       el.dataset.id,
                position: index + 1,
                visible:  el.querySelector('.block-toggle').checked,
            });
        });
        return { blocks };
    }

    // ── Collect Fields from DOM ──
    function getFieldsConfig() {
        const fields = {};
        // get original labels from server-rendered data
        const originalFields = @json($template->fields_config);

        document.querySelectorAll('#fields-list .field-item').forEach((el, index) => {
            const key = el.dataset.key;
            fields[key] = {
                visible: el.querySelector('.field-toggle').checked,
                order:   index + 1,
                label:   originalFields[key]?.label ?? key,
            };
        });
        return fields;
    }

    // ── Save Template ──
    function saveTemplate() {
        const btn = document.getElementById('save-btn');
        btn.textContent = '⏳ Saving...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('primary_color',   document.getElementById('primary-color').value);
        formData.append('secondary_color', document.getElementById('secondary-color').value);
        formData.append('font_family',     document.getElementById('font-family').value);
        formData.append('layout',          JSON.stringify(getLayoutConfig()));
        formData.append('fields_config',   JSON.stringify(getFieldsConfig()));
        formData.append('notes',           document.getElementById('notes-text').value);

        const logoFile = document.getElementById('logo-input').files[0];
        if (logoFile) formData.append('logo', logoFile);

        fetch('/invoice/save', {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            btn.textContent = '💾 Save Template';
            btn.disabled = false;

            if (data.success) {
                const msg = document.getElementById('save-msg');
                msg.style.display = 'block';
                setTimeout(() => msg.style.display = 'none', 3000);

                // Refresh preview iframe
                document.getElementById('preview-frame').src =
                    '/invoice/preview?t=' + Date.now();
            }
        })
        .catch(() => {
            btn.textContent = '💾 Save Template';
            btn.disabled = false;
            alert('Something went wrong. Please try again.');
        });
    }
</script>

</body>
</html>