#!/bin/bash
set -e

echo "🔧 Mondals E-Commerce - Container Starting..."

# Always create storage symlink (safe to re-run)
php artisan storage:link 2>/dev/null || true

# First deploy: empty APP_KEY makes artisan (migrate/seed) exit 1
if [ -f /var/www/html/.env ] && ! grep -qE '^APP_KEY=.' /var/www/html/.env; then
    php artisan key:generate --force
fi

# Caching is done in deploy.sh after migrate (not here — avoids boot failures / duplicate work)

# Run database migrations if enabled
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "📦 Running database migrations..."
    php artisan migrate --force
fi

# Run database seeder if enabled (migrate first so empty DB does not kill the container)
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "📦 Migrating before seed..."
    php artisan migrate --force
    echo "🌱 Running database seeders..."
    php artisan db:seed --force
fi

# Ensure correct permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "✅ Container ready!"

# Start the application server (Apache)
exec "$@"
