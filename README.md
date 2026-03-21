<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" alt="Laravel Logo" width="300">
  <h1>Mondal's E-Commerce Architecture</h1>
  <p><b>An exhaustive, enterprise-grade multi-tenant platform built on Laravel 11.</b></p>
  
  [![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
  [![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
  [![License](https://img.shields.io/badge/License-Proprietary-yellow.svg)]()
</div>

---

## 📖 Executive Overview

Mondal's Engine is designed to facilitate a robust multi-vendor ecosystem. It isolates structural logic between native **Customers**, **Vendors**, and **Platform Administrators**. To ensure infinite scalability without altering core framework files, the platform utilizes custom decoupled **Theme** and **Plugin** engines, alongside a completely mapped **RESTful API v1** utilizing Sanctum.

---

## 🧱 Core Application Architecture (MVC+)

The architecture heavily abstracts standard logic. Code is organized using deeply nested controllers and custom Service execution layers.

```text
c:\laragon\www\MondalsEcommerce\
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Secure CMS, vendor approvals, global order pipeline
│   │   │   ├── Api/V1/        # 100% JSON serialized strict headless endpoints
│   │   │   ├── Auth/          # Cookie/Session & Sanctum bearer token factories
│   │   │   ├── Storefront/    # Cart state manipulation, product browsing
│   │   │   └── Vendor/        # Scoped vendor dashboard and localized catalogs
│   │   └── Middleware/        # Guards: EnsureUserIsAdmin, EnsureUserIsVendor
│   ├── Models/                # Eloquent definitions (with Filterable, HasSlug Traits)
│   ├── Providers/             # Registers Core singletons (ThemeServiceProvider)
│   └── Services/              
│       ├── ThemeManager.php   # Handles active theme view mapping & asset symlinks
│       └── PluginManager.php  # Discovers, installs, and boots ZIP plugin ecosystems
├── database/
│   └── migrations/            # Strict MySQL ENUM schemas & SQLite compatible indexes
├── plugins/                   # Extracted ZIP extensions
│   ├── bkash-payment/         # Tokenized bKash Checkout (v1.2.0-beta)
│   ├── pathao/                # Dynamic shipping rates via Pathao API
│   └── flat-rate-shipping/    # Zone-based configurable shipping
├── resources/
│   ├── themes/                # Root array for Swappable frontend blade files
│   └── views/                 # Administrative & Vendor Core UI (Tailwind based)
└── routes/
    ├── api.php                # Headless API pipeline
    └── web.php                # Browser based interactions (includes Plugin Purging)
```

---

## 💾 Database Schemas & Strict Enums

To prevent catastrophic financial mapping errors, E-Commerce statuses do **not** use open strings. They rely on strictly enforced database Enums natively migrated into the `orders` table.

### Physical Fulfillment (`status`)
*   `pending`: Default state on order receive.
*   `confirmed`, `processing`, `shipped`: Transit stages.
*   `delivered`: Package handed off.
*   `completed`: Finalized.
*   `cancelled`: Order aborted.

### Financial Settlement (`payment_status`)
*   `pending`: Awaiting gateway confirmation.
*   `paid`: Cleared financial transaction.
*   `refunded`: Capital reverted to customer.
*   `failed`: Gateway or manual rejection.

> ⚠️ **Note:** Updating order states natively validates against these ENUM constants.

---

## 🔌 Extensibility Ecosystem

### 1. ThemeManager Service
Mondal's engine replaces Laravel's native view mapping with a dynamic array priority system.
*   **Location:** `resources/themes/{theme_id}`
*   **Function:** `ThemeServiceProvider` searches the `settings` table for an active theme string. It uses `View::prependNamespace()` to force Blade to look inside the active theme folder before using system defaults.
*   **Assets:** CSS and JS placed in a theme's `assets/` directory are auto-symlinked to the public folder. In Blade files, strictly use `{{ @themeAsset('css/main.css') }}` to dynamically load resources without breaking logic.

### 2. PluginManager Engine
The ecosystem supports "Drag-and-Drop" ZIP plugin additions for extending gateways or logic.
*   **Lifecycle:** Upload (`plugins/`) → Discover (reads `plugin.json` to DB) → Install (executes internal DB migrations) → Enable (Providers boot up) → **Purge** (Complete removal from disk).
*   **Dynamic Settings:** If a plugin ZIP contains a `views/settings.blade.php` (or as defined in `plugin.json`), the Admin Dashboard intelligently intercepts it and renders a "Configure" window automatically!
*   **Hook System:** Standardized hooks like `register_shipping_methods` and `register_payment_gateways` allow plugins to inject checkout options dynamically.

---

## 💳 Payment & Shipping Integrations

### 1. bKash Tokenized Checkout
A production-ready integration for **bKash Tokenized Checkout (v1.2.0-beta)**. 
*   **Security:** Utilizes `X-APP-Key` and `Bearer` tokenization for every request.
*   **Logic:** Handles `create`, `execute`, and `query` payment status flows natively.
*   **Callback:** Supports dynamic `IPN` and `callbackURL` resolution via `APP_URL`.

### 2. Unified Shipping Strategy
The engine consolidates DB-based methods and Plugin-based methods into a single `availShipping` collection. 
*   **Prioritization:** Injected plugins (e.g., Pathao) take precedence over legacy seeded methods.
*   **Config:** All rates are calculated based on Plugin Settings (e.g. `inside_city_rate`) fetched in real-time.

## 🌐 Complete REST API v1 Specification

All paths are prefixed with `/api/v1/`.

### 🔓 Public Catalog & Authentication
| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/register` | Validates user array, returns User + Sanctum Bearer Token |
| `POST` | `/login` | Trades credentials for Sanctum Session array |
| `GET` | `/categories/tree` | Returns deeply nested JSON mapping of active categories |
| `GET` | `/products` | Paginated index supporting queries: `?q=&sort=&category=` |
| `GET` | `/products/{slug}`| Details variant arrays and reviews mapped to product |

### 🔒 Protected Interactions (Requires `Bearer {token}`)
| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/addresses` | Inserts unified billing/shipping nodes into user profile |
| `GET` | `/cart` | Re-calculates Session variables, applies tax logic natively |
| `POST` | `/cart/items` | Adds `product_id, quantity` checking inventory constraints |
| `POST` | `/checkout/calculate` | Estimates weight/distance and applies active coupon modifiers |
| `POST` | `/checkout/order` | Fires `Order` DB build, kills Cart, generates Transaction rows |
| `POST` | `/vendor/apply` | Queues user application natively targeting the Admin dashboard |

---

## 💻 Installation & Setup Protocol

To boot up a local or production instance of the engine:

**1. Clone the Source Directory**
```bash
git clone https://github.com/needyamin/mondals-ecommerce.git
cd mondals-ecommerce
```

**2. Hydrate Dependencies**
```bash
composer install
npm install && npm run build
```

**3. Configure Native Environment**
```bash
cp .env.example .env
php artisan key:generate
```
*Modify your `.env` to target your local MySQL or MariaDB instance.*

**4. Generate Schema & Seed Admin Variables**
```bash
php artisan migrate --seed
```
*Crucial step: The seeder populates the initial Settings table and active Theme namespaces needed for the Application Provider to boot without 500 errors.*

**5. Symlink Local Storage**
```bash
php artisan storage:link
```

**6. Start Native Server**
```bash
php artisan serve
```

---

## 🧪 E2E Testing Rules

The framework supports extremely fast in-memory SQLite driver tests.

```bash
php artisan test
```

> **Important Architecture Fix:** Standard SQLite does not natively support `fullText` index migrations directly off the schema grammar. The migrations (e.g., `2026_03_20_030005_create_products_table`) have been successfully wrapped in `DB::connection()->getDriverName() !== 'sqlite'` checks allowing tests to bypass full-text index compilation while utilizing SQLite memory.

---

<p align="center">
  <small><em>Proprietary Engine crafted by <b>Mondal's E-Commerce Development</b>. Architecture locked for v1.0.0.</em></small>
</p>
