# 🧾 Dynamic Invoice Builder — Laravel SaaS

A multi-tenant invoice template builder where each subscriber can fully customize their invoice layout — drag & drop blocks, resize sections, change colors, upload logos, and download a pixel-perfect PDF.

---

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Storage Setup](#storage-setup)
- [Running the App](#running-the-app)
- [Routes](#routes)
- [Project Structure](#project-structure)
- [How It Works](#how-it-works)
- [Adding a New Block](#adding-a-new-block)
- [Multi-Tenant Usage](#multi-tenant-usage)
- [Troubleshooting](#troubleshooting)

---

## ✨ Features

- 🎨 **Live Invoice Builder UI** — visual drag & drop editor on an A4 canvas
- 📦 **Independent Blocks** — Logo, Company Info, Invoice Title, From, Bill To, Items, Totals, Notes
- ↔ **Free-form Positioning** — drag each block anywhere on the A4 paper
- ↘ **Resize Handles** — resize width & height of any block independently
- 👁 **Block Visibility Toggle** — show/hide any block per subscriber
- 🔢 **Field Ordering** — reorder and toggle invoice fields (Invoice #, Date, Due Date, PO Number)
- 🖼 **Logo Upload** — upload company logo, previews live on canvas
- 🎨 **Color Customization** — primary & secondary color pickers
- 🔤 **Font Picker** — choose font family per subscriber
- 📝 **Notes Editor** — editable footer notes
- 📄 **PDF Download** — generates PDF matching the saved canvas layout exactly
- 🏢 **Multi-Tenant Ready** — all settings scoped per `subscriber_id`

---

## ✅ Requirements

| Requirement        | Version                   |
| ------------------ | ------------------------- |
| PHP                | >= 8.1                    |
| Laravel            | >= 9.x                    |
| Composer           | >= 2.x                    |
| MySQL / PostgreSQL | Any recent version        |
| Node.js (optional) | For frontend tooling only |

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-org/your-project.git
cd your-project
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Copy environment file

```bash
cp .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

---

## ⚙️ Configuration

Open `.env` and update the following:

```env
APP_NAME="Invoice Builder"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

FILESYSTEM_DISK=public
```

---

## 🗄️ Database Setup

### 1. Run all migrations

```bash
php artisan migrate
```

This creates the `invoice_templates` table with these columns:

| Column            | Type    | Description                        |
| ----------------- | ------- | ---------------------------------- |
| `subscriber_id`   | bigint  | Tenant identifier                  |
| `name`            | string  | Template name                      |
| `is_active`       | boolean | Active template flag               |
| `logo_path`       | string  | Path to uploaded logo              |
| `primary_color`   | string  | Hex color e.g. `#3B82F6`           |
| `secondary_color` | string  | Hex color                          |
| `font_family`     | string  | e.g. `Arial`                       |
| `layout`          | json    | Block positions, sizes, visibility |
| `fields_config`   | json    | Field labels, order, visibility    |
| `notes`           | text    | Footer notes text                  |

### 2. Seed a test subscriber template

```bash
php artisan db:seed --class=InvoiceTemplateSeeder
```

This creates a default template for `subscriber_id = 1` with all blocks pre-configured.

### 3. Re-seed fresh (reset + reseed)

```bash
php artisan tinker
\App\Models\InvoiceTemplate::where('subscriber_id', 1)->delete();
exit

php artisan db:seed --class=InvoiceTemplateSeeder
```

---

## 🗂️ Storage Setup

Logo uploads use Laravel's public disk. Run this **once** after installation:

```bash
php artisan storage:link
```

This creates a `public/storage` symlink so uploaded logos are web-accessible.

Verify:

```bash
ls public/storage
# should show: logos/
```

---

## ▶️ Running the App

```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🛣️ Routes

| Method | URL                     | Description                           |
| ------ | ----------------------- | ------------------------------------- |
| `GET`  | `/invoice/builder`      | Open the visual template builder      |
| `POST` | `/invoice/save`         | Save template settings to DB          |
| `GET`  | `/invoice/preview`      | Download invoice as PDF               |
| `GET`  | `/invoice/preview-html` | View invoice as HTML (used in iframe) |

---

## 📁 Project Structure

```
app/
├── Http/
│   └── Controllers/
│       └── InvoiceTemplateController.php   # Builder, Save, Preview
├── Models/
│   └── InvoiceTemplate.php                 # Template model with JSON casts
└── Services/
    └── InvoicePdfService.php               # PDF generation service

database/
├── migrations/
│   └── xxxx_create_invoice_templates_table.php
│   └── xxxx_add_notes_to_invoice_templates_table.php
└── seeders/
    └── InvoiceTemplateSeeder.php           # Default template for subscriber_id=1

resources/views/invoices/
├── builder.blade.php                       # Full visual builder UI
├── dynamic.blade.php                       # PDF/HTML invoice wrapper
└── blocks/
    ├── logo.blade.php                      # Company logo block
    ├── company_info.blade.php              # Company name & address
    ├── invoice_title.blade.php             # INVOICE heading + fields
    ├── from.blade.php                      # Sender details
    ├── bill_to.blade.php                   # Client details
    ├── items.blade.php                     # Line items table
    ├── totals.blade.php                    # Subtotal, tax, grand total
    └── notes.blade.php                     # Footer notes & terms

routes/
└── web.php                                 # All invoice routes
```

---

## ⚙️ How It Works

### Template Storage (JSON)

All layout data is stored as JSON in the `invoice_templates` table:

```json
{
    "layout": {
        "blocks": [
            {
                "id": "logo",
                "position": 1,
                "visible": true,
                "x": 0,
                "y": 0,
                "w": 200,
                "h": 80
            },
            {
                "id": "company_info",
                "position": 2,
                "visible": true,
                "x": 0,
                "y": 90,
                "w": 300,
                "h": 100
            },
            {
                "id": "invoice_title",
                "position": 3,
                "visible": true,
                "x": 310,
                "y": 0,
                "w": 384,
                "h": 100
            },
            {
                "id": "from",
                "position": 4,
                "visible": true,
                "x": 0,
                "y": 200,
                "w": 330,
                "h": 110
            },
            {
                "id": "bill_to",
                "position": 5,
                "visible": true,
                "x": 340,
                "y": 200,
                "w": 354,
                "h": 110
            },
            {
                "id": "items",
                "position": 6,
                "visible": true,
                "x": 0,
                "y": 320,
                "w": 694,
                "h": 160
            },
            {
                "id": "totals",
                "position": 7,
                "visible": true,
                "x": 440,
                "y": 490,
                "w": 254,
                "h": 100
            },
            {
                "id": "notes",
                "position": 8,
                "visible": true,
                "x": 0,
                "y": 490,
                "w": 430,
                "h": 80
            }
        ]
    },
    "fields_config": {
        "invoice_number": { "visible": true, "order": 1, "label": "Invoice #" },
        "invoice_date": {
            "visible": true,
            "order": 2,
            "label": "Invoice Date"
        },
        "due_date": { "visible": true, "order": 3, "label": "Due Date" },
        "po_number": { "visible": false, "order": 4, "label": "PO Number" }
    }
}
```

### Builder Flow

```
User opens /invoice/builder
        ↓
Canvas renders all blocks at saved x/y/w/h positions
        ↓
User drags block → interact.js updates left/top CSS
User resizes block → interact.js updates width/height CSS
User toggles visibility → block shown/hidden on canvas
        ↓
User clicks Save
        ↓
JS reads all block positions from DOM → sends to /invoice/save
        ↓
Controller updates invoice_templates record in DB
        ↓
User clicks Download PDF
        ↓
InvoicePdfService loads template → renders dynamic.blade.php
PDF uses absolute positioning matching saved x/y/w/h
```

### PDF Generation

Uses `barryvdh/laravel-dompdf`. The `dynamic.blade.php` renders each block as
an absolutely positioned `div` matching the saved `x`, `y`, `w`, `h` values —
so the PDF looks exactly like the builder canvas.

**Important:** dompdf cannot load URLs for images. The `$forPdf = true` flag
switches logo `src` from `asset()` (URL) to `storage_path()` (absolute file path).

---

## ➕ Adding a New Block

1. **Create the blade partial:**

```bash
touch resources/views/invoices/blocks/your_block.blade.php
```

2. **Add content** (use `$invoice`, `$template`, `$fields`, `$forPdf` variables)

3. **Add to seeder** in the `layout.blocks` array:

```php
['id' => 'your_block', 'position' => 9, 'visible' => true,
 'x' => 0, 'y' => 600, 'w' => 300, 'h' => 80],
```

4. **Reseed:**

```bash
php artisan tinker
\App\Models\InvoiceTemplate::where('subscriber_id', 1)->delete();
exit
php artisan db:seed --class=InvoiceTemplateSeeder
```

The block automatically appears in the builder and PDF — no other changes needed.

---

## 🏢 Multi-Tenant Usage

Currently the builder uses `subscriber_id = 1` (hardcoded for development).

To go multi-tenant, replace the hardcoded ID with the authenticated user's subscriber:

**In `InvoiceTemplateController.php`:**

```php
// Replace this:
$template = InvoiceTemplate::forSubscriber(1);

// With this (example using Auth):
$template = InvoiceTemplate::forSubscriber(auth()->user()->subscriber_id);
```

**In `InvoiceTemplate.php` — handle missing template gracefully:**

```php
public static function forSubscriber(int $subscriberId): self
{
    return static::where('subscriber_id', $subscriberId)
                 ->where('is_active', true)
                 ->firstOrCreate(
                     ['subscriber_id' => $subscriberId],
                     static::defaultConfig()   // fallback defaults
                 );
}
```

---

## 🔧 Troubleshooting

### Logo not showing in builder

```bash
# Make sure symlink exists
php artisan storage:link

# Check file uploaded
ls storage/app/public/logos/
```

### Logo not showing in PDF

Make sure `$forPdf = true` is passed to `InvoicePdfService` and the blade
uses `storage_path('app/public/' . $template->logo_path)` for PDF rendering.

### Blocks not dragging

Ensure `interact.js` is loaded from CDN:

```html
<script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.27/dist/interact.min.js"></script>
```

### PDF blank / not generating

```bash
# Install dompdf if not done
composer require barryvdh/laravel-dompdf

# Publish config
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### Migration errors

```bash
# Check migration status
php artisan migrate:status

# Rollback and retry if needed
php artisan migrate:rollback
php artisan migrate
```

### Seeder not updating blocks

The seeder uses `updateOrCreate` which won't add new blocks to existing records.
Always delete and reseed fresh when changing the block structure:

```bash
php artisan tinker
\App\Models\InvoiceTemplate::where('subscriber_id', 1)->delete();
exit
php artisan db:seed --class=InvoiceTemplateSeeder
```

---

## 📦 Key Packages

| Package                   | Purpose                        |
| ------------------------- | ------------------------------ |
| `barryvdh/laravel-dompdf` | HTML to PDF generation         |
| `interactjs` (CDN)        | Drag & drop + resize on canvas |
| `sortablejs` (CDN)        | Field ordering in panel        |

---

## 📄 License

MIT License. Free to use and modify.
