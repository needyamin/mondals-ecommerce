<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" alt="Mondal's E-Commerce" width="300">
  <h1>Mondal's E-Commerce Architecture</h1>
  <p><b>An exhaustive, enterprise-grade multi-tenant platform built on Laravel 11.</b></p>
  
  [![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg?style=for-the-badge&logo=php)](https://php.net)
  [![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg?style=for-the-badge&logo=laravel)](https://laravel.com)
  [![Database](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)]()
  [![Deployment](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)]()
  [![License](https://img.shields.io/badge/License-Proprietary-yellow.svg?style=for-the-badge)]()
</div>

---

## 📖 Executive Overview

**Mondal's Engine** is a high-performance multi-vendor ecosystem. It isolates logic between **Customers**, **Vendors**, and **Platform Administrators** while supporting infinite scalability. 

The core mission is zero framework-level modifications. This is achieved via a custom decoupled **Theme Engine** (powered by the premium `lovemad` theme) and a ZIP-based **Plugin Engine**, alongside a strict **RESTful API v1** authenticated via Laravel Sanctum.

### ✨ What's New in v1.1.0 (March 2026)
- 🎨 **`lovemad` Premium Theme:** A modern, high-conversion default storefront.
- 🖼️ **`HasFallbackImage` Engine:** Deterministic, colorful placeholders for missing assets using UI Avatars & Placehold.co.
- 🚢 **Unified Deployment:** One-click deployment script (`deploy.sh`) supporting Docker Compose, K8s, and Cloudflare Tunnels (GHA integration).
- 🧹 **Plugin Purge:** Enhanced plugin lifecycle management with physical disk cleanup.

---

<img width="1920" height="auto" alt="Architecture Overview" src="https://github.com/user-attachments/assets/66045b2d-83ca-408e-9799-38c240cc2ba0" />

---

## 🧱 Core Application Architecture (MVC+)

The architecture heavily abstracts standard logic. Code is organized using deeply nested controllers and custom Service execution layers.

```text
c:\laragon\www\MondalsEcommerce\
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/         # Secure CMS, vendor approvals, global order pipeline
│   │   ├── Api/V1/        # 100% JSON serialized strict headless endpoints
│   │   ├── Storefront/    # Cart state manipulation, product browsing
│   │   └── Vendor/        # Scoped vendor dashboard and localized catalogs
│   ├── Providers/         # Registers Core singletons (ThemeServiceProvider)
│   ├── Services/              
│   │   ├── ThemeManager.php   # Handles active theme view mapping & asset symlinks
│   │   └── PluginManager.php  # Discovers, installs, and boots ZIP plugin ecosystems
│   └── Traits/
│       ├── HasFallbackImage.php # Deterministic placeholders for missing media
│       └── Filterable.php       # Query-based Eloquent filtering
├── plugins/               # Extracted ZIP extensions (bKash, Pathao, etc.)
├── resources/
│   ├── themes/            # Root array for Swappable frontend blade files (e.g., lovemad)
│   └── views/             # Administrative & Vendor Core UI (Tailwind based)
└── scripts/               # Automation: deploy.sh, setup-server.sh
```

---

## 💾 Database Schemas & Strict Enums

To prevent financial mapping errors, order statuses are strictly enforced database Enums.

### Physical Fulfillment (`status`)
*   `pending` ➡️ `confirmed` ➡️ `processing` ➡️ `shipped` ➡️ `delivered` ➡️ `completed`
*   `cancelled`: Order aborted.

### Financial Settlement (`payment_status`)
*   `pending`: Awaiting gateway confirmation.
*   `paid`: Cleared financial transaction.
*   `refunded`: Capital reverted.
*   `failed`: Rejection or timeout.

---

## 🔌 Extensibility Ecosystem

### 1. ThemeManager Service
Mondal's engine replaces Laravel's native view mapping.
*   **Active Theme:** Defined in the `settings` table.
*   **Assets:** CSS/JS in `resources/themes/{theme}/assets/` are auto-symlinked to `public/themes/{theme}/`.
*   **Development:** Use `{{ @themeAsset('css/main.css') }}` for dynamic loading.

### 2. PluginManager Engine
Support for "Drag-and-Drop" ZIP plugin additions.
*   **Lifecycle:** Upload ➡️ Discover ➡️ Install (Migrations) ➡️ Enable ➡️ **Purge** (Total removal).
*   **Hooks:** `register_shipping_methods`, `register_payment_gateways`.

---

## 🚢 Smart Deployment Ecosystem

Deploying Mondal's E-Commerce is now simpler and more robust than ever.

### 🐧 The One-Click Deployment
The `scripts/deploy.sh` script handles everything:
- Git synchronization and ownership fixes.
- Docker container builds and health checks.
- Automated migrations, caching, and permission hardening.
- **Direct Public Storage:** Optimized for cloud serving (Cloudflare Tunnel ready).

Check [README_DEPLOY.md](README_DEPLOY.md) for full server setup instructions.

---

## 🌐 API Specification v1
Prefix all paths with `/api/v1/`.

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/register` | User creation + Sanctum Token returns |
| `GET` | `/products` | Filterable paginated catalog index |
| `GET` | `/cart` | Live session-based cart retrieval |
| `POST` | `/checkout/order` | Finalizes transaction and builds DB records |

---

## 💻 Installation & Setup

```bash
# 1. Clone & Enter
git clone https://github.com/needyamin/mondals-ecommerce.git && cd mondals-ecommerce

# 2. Hydrate
composer install && npm install && npm run build

# 3. Configure
cp .env.example .env && php artisan key:generate

# 4. Migrate & Link
php artisan migrate --seed && php artisan storage:link

# 5. Boot
php artisan serve
```

---

<p align="center">
  <small><em>Proprietary Engine crafted by <b>Mondal's E-Commerce Development</b>. Version 1.1.0 Stable.</em></small>
</p>
