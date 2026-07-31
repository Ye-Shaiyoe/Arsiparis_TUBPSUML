#!/bin/bash
set -e

echo "🚀 Memulai proses deployment..."

# 1. Aktifkan mode maintenance
php artisan down || true

# 2. Tarik kode terbaru dari Git
git pull origin main

# 3. Install dependencies PHP
composer install --no-dev --optimize-autoloader

# 4. Jalankan migrasi database
php artisan migrate --force

# 5. Optimalkan cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart queue worker (opsional)
php artisan queue:restart

# 7. Matikan mode maintenance
php artisan up

echo "✅ Deployment selesai dengan sukses!"
