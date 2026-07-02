#!/bin/sh
set -e

# Create necessary storage directories
mkdir -p /var/www/html/storage/framework/{cache/data,sessions,testing,views}
mkdir -p /var/www/html/storage/app/{private/surat,private/lampiran,public}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Clear and cache configs for production
php artisan config:clear
php artisan cache:clear

# Run migrations
php artisan migrate --force

# Cache configs for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
php artisan storage:link || true

# Start PHP-FPM in background
php-fpm -D

echo "PHP-FPM started"

# Start Nginx in foreground
exec nginx -g "daemon off;"
