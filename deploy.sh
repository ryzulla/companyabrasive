#!/usr/bin/env bash
# Deploy Laravel di cPanel/Domainesia.
# Jalankan di server dari folder project:  bash deploy.sh
set -euo pipefail

cd "$(dirname "$0")"

# Sesuaikan jika binary PHP default server bukan 8.2+
# Contoh Domainesia/cPanel: /opt/cpanel/ea-php83/root/usr/bin/php
PHP="${PHP:-php}"
COMPOSER="${COMPOSER:-composer}"

echo "==> Versi PHP"
$PHP -v | head -1

echo "==> Ambil kode terbaru"
git pull origin main

echo "==> Install dependency"
$PHP "$(command -v $COMPOSER)" install --no-dev --optimize-autoloader --no-interaction

if [ ! -f .env ]; then
  echo "==> .env belum ada, salin dari .env.example"
  cp .env.example .env
  $PHP artisan key:generate --force
  echo "!! Edit .env dulu (APP_ENV, APP_DEBUG, APP_URL, DB_*), lalu jalankan ulang deploy.sh"
  exit 1
fi

echo "==> Migrasi database"
$PHP artisan migrate --force

echo "==> Storage link"
[ -e public/storage ] || $PHP artisan storage:link

echo "==> Permission"
chmod -R 775 storage bootstrap/cache

echo "==> Cache production"
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache

if [ ! -d public/build ]; then
  echo "!! public/build belum ada. Jalankan 'npm run build' di lokal lalu upload folder public/build."
fi

echo "==> Selesai"
