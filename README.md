<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" alt="Mondal's E-Commerce" width="300">
  <h1>Mondal's E-Commerce</h1>
  <p><b>Multi-vendor storefront and admin platform on Laravel.</b></p>
  
  [![PHP](https://img.shields.io/badge/PHP-8.3%2B-blue.svg?style=for-the-badge&logo=php)](https://php.net)
  [![Laravel](https://img.shields.io/badge/Laravel-13.x-red.svg?style=for-the-badge&logo=laravel)](https://laravel.com)
  [![Database](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)]()
</div>

---

## Overview

Multi-vendor e-commerce with **customers**, **vendors**, **staff**, and **admins** (Spatie Laravel Permission). Storefront uses a swappable **theme engine** (`resources/themes/`, e.g. `default`, `lovemad`); optional features ship as **ZIP plugins** under `plugins/`. **API v1** is JSON + Laravel Sanctum.

---

## Requirements

- PHP **8.3+**
- Composer, Node/npm (for Vite assets)
- MySQL (or compatible SQL)

---

## Install

```bash
git clone https://github.com/needyamin/mondals-ecommerce.git && cd MondalsEcommerce

composer install && npm install && npm run build

cp .env.example .env && php artisan key:generate

php artisan migrate --seed && php artisan storage:link

php artisan serve
```

After changing `composer.json` autoload `files`, run:

```bash
composer dump-autoload
```

---

## Project layout (high level)

```text
app/
├── Helpers/                 # Autoloaded: theme_helpers, business_calculation, media_uploads
├── Http/Controllers/
│   ├── Admin/               # CMS, users (roles), catalog, orders
│   ├── Api/V1/
│   ├── Vendor/              # Vendor dashboard (products, earnings, settings)
│   └── StoreController.php  # Public store listing & vendor storefront
├── Services/                # Checkout, cart, commissions, product gallery, etc.
├── Support/                 # e.g. MediaDisks
└── Models/
plugins/                     # bKash, Pathao, product-reviews, etc.
resources/
├── themes/{theme}/views/    # Storefront Blade (active theme from settings)
└── views/                   # Admin, vendor panels, auth
```

### Autoloaded helpers (`composer.json` → `autoload.files`)

| File | Purpose |
|------|---------|
| `app/Helpers/theme_helpers.php` | Theme URL/asset helpers |
| `app/Helpers/business_calculation.php` | Money, tax, coupons, commissions (single source of truth) |
| `app/Helpers/media_uploads.php` | Public disk uploads, `store_disk_upload`, deletes |

---

## Roles (web guard)

Seeded roles include **`customer`**, **`vendor`**, **`staff`**, **`admin`**. Admin UI and middleware expect the **`admin`** role for `/admin/*`.

---

## Themes & plugins

- **Theme:** Active theme key in settings; views resolve under `resources/themes/{active}/views/`.
- **Plugins:** Installed under `plugins/`; manager registers hooks (shipping, payments, etc.).

---

## API v1

Prefix: `/api/v1/` — Sanctum-authenticated where required. See `routes/api.php` for current routes.

---

## Deployment

See [README_DEPLOY.md](README_DEPLOY.md) if present for server/deploy notes.

---

<p align="center">
  <small><em>Mondal's E-Commerce — Laravel application.</em></small>
</p>
