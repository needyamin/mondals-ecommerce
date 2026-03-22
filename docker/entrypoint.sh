#!/bin/bash
set -e

echo "🔧 Mondals E-Commerce - Container Starting..."

# Always create storage symlink (safe to re-run)
php artisan storage:link 2>/dev/null || true

# Cache configuration, routes, and views if in production
if [ "$APP_ENV" = "production" ]; then
    echo "⚡ Caching Laravel configuration, routes, and views..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Run database migrations if enabled
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "📦 Running database migrations..."
    php artisan migrate --force
fi

# Run database seeder if enabled
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "🌱 Running database seeders..."
    php artisan db:seed --force
fi

# Ensure correct permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "✅ Container ready!"

# Start the application server (Apache)
exec "$@"
