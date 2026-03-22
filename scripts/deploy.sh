#!/bin/bash
# 🚢 Mondal's E-Commerce Deployment Script 🚢
# Designed for Docker-Compose powered deployment on Ubuntu

set -euo pipefail

APP_CONTAINER="mondals-app"
DB_CONTAINER="mondals-db"

echo ""
echo "🌊 Starting Mondal's E-Commerce Deployment..."
echo ""

# ── 1. Verify Docker is running ──
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running! Start Docker first:"
    echo "   sudo systemctl start docker"
    echo "   Or run: ./scripts/setup-server.sh"
    exit 1
fi

# ── 2. Ensure .env file exists ──
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "⚠️  No .env file found. Copying from .env.example..."
        cp .env.example .env
        echo "📝 Generating application key..."
        # We'll generate the key after container starts
    else
        echo "❌ Neither .env nor .env.example found!"
        echo "   Please create a .env file with your configuration."
        exit 1
    fi
fi

# ── 3. Stop existing containers ──
echo "🛑 Stopping existing containers..."
docker compose down --remove-orphans 2>/dev/null || true

# ── 4. Build and start containers ──
echo "🏗️  Building and starting containers..."
docker compose up -d --build

# ── 5. Wait for database to be ready (proper health check instead of sleep) ──
echo "⏳ Waiting for database to be ready..."
MAX_RETRIES=30
RETRY_COUNT=0
until docker exec "$DB_CONTAINER" mysqladmin ping -h "127.0.0.1" -u root -proot_password --silent 2>/dev/null; do
    RETRY_COUNT=$((RETRY_COUNT + 1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "❌ Database failed to start after ${MAX_RETRIES} attempts!"
        echo "🔍 Database logs:"
        docker logs "$DB_CONTAINER" --tail 30
        exit 1
    fi
    echo "   Waiting for MySQL... (attempt $RETRY_COUNT/$MAX_RETRIES)"
    sleep 2
done
echo "✅ Database is ready!"

# ── 6. Generate app key if not set ──
APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    docker exec "$APP_CONTAINER" php artisan key:generate --force
fi

# ── 7. Run Laravel setup commands ──
echo "⚡ Caching configuration to ensure correct DB connection..."
docker exec "$APP_CONTAINER" php artisan config:clear
docker exec "$APP_CONTAINER" php artisan config:cache
docker exec "$APP_CONTAINER" php artisan route:cache
docker exec "$APP_CONTAINER" php artisan view:cache

echo "🛠️  Running database migrations..."
docker exec "$APP_CONTAINER" php artisan migrate --force

echo "📦 Creating storage symlink..."
docker exec "$APP_CONTAINER" php artisan storage:link 2>/dev/null || true

# ── 8. Fix permissions ──
echo "🔐 Setting file permissions..."
docker exec "$APP_CONTAINER" chown -R www-data:www-data storage bootstrap/cache
docker exec "$APP_CONTAINER" chmod -R 775 storage bootstrap/cache

# ── 9. Quick health check ──
echo "🔍 Verifying application..."
sleep 2
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 2>/dev/null || echo "000")
if [ "$HTTP_STATUS" = "200" ] || [ "$HTTP_STATUS" = "302" ]; then
    echo "✅ Application is responding (HTTP $HTTP_STATUS)"
else
    echo "⚠️  Application returned HTTP $HTTP_STATUS (may still be starting up)"
    echo "   Check logs: docker logs $APP_CONTAINER --tail 20"
fi

echo ""
echo "============================================"
echo "  🚀 DEPLOYMENT SUCCESSFUL!"
echo "============================================"
echo ""
echo "  🌐 App:        http://localhost:8000"
echo "  🗄️  phpMyAdmin: http://localhost:8088"
echo "  📊 DB Port:     localhost:3388"
echo ""
echo "  Useful commands:"
echo "    docker compose logs -f        # Watch logs"
echo "    docker exec -it $APP_CONTAINER bash  # Shell into app"
echo "    docker compose down            # Stop everything"
echo "============================================"
