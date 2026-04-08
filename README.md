<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" alt="Mondal's E-Commerce" width="300">
  <h1>Mondal's E-Commerce</h1>
  <p><b>Multi-vendor Laravel storefront + admin.</b></p>

  [![PHP](https://img.shields.io/badge/PHP-8.3%2B-blue.svg?style=for-the-badge&logo=php)](https://php.net)
  [![Laravel](https://img.shields.io/badge/Laravel-13.x-red.svg?style=for-the-badge&logo=laravel)](https://laravel.com)
  [![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docs.docker.com/compose/)
</div>

---
## Screenshots

<img width="1920" height="3037" alt="Image" src="https://github.com/user-attachments/assets/067639b4-5e2a-4a5e-9a75-d3fe82e53898" />

## Local (no Docker)

```bash
git clone https://github.com/needyamin/mondals-ecommerce.git && cd mondals-ecommerce
composer install && npm install && npm run build
cp .env.example .env && php artisan key:generate
php artisan migrate --seed && php artisan storage:link
php artisan serve
```

`composer dump-autoload` after changing `composer.json` autoload `files`.

---

## Docker (quick)

```bash
cp .env.example .env   # set APP_KEY, APP_URL
docker compose up -d --build
docker compose exec app php artisan migrate --seed
```

App: **http://localhost:8000** · phpMyAdmin: **8088** · MySQL host: **3388**

---

## Ubuntu server: setup then deploy

Run everything from **`scripts/`** (no need to `cd` to repo root):

```bash
cd scripts
chmod +x setup-server.sh && ./setup-server.sh
```

Log out and back in (Docker group). Then still from **`scripts/`**:

```bash
chmod +x up deploy.sh && ./up
```

**`./up`** = full Docker deploy: syncs `.env` for Compose (MySQL `db`, drivers), `git pull`, `compose up`, migrate, cache. Same as `./deploy.sh`.

---

## Layout

`app/` (Controllers, Services, **Helpers**: theme, business math, media uploads) · `docker/` · `scripts/` · `k8s/` (optional K8s examples) · `resources/themes/` · `plugins/`

**Roles:** `customer`, `vendor`, `staff`, `admin` (Spatie). **API:** `/api/v1/` (Sanctum).

---

<p align="center"><small>Mondal's E-Commerce</small></p>
