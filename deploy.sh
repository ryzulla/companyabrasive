#!/usr/bin/env bash
# Deploy Laravel di cPanel/Domainesia.
# Jalankan di server dari folder project:  bash deploy.sh
set -euo pipefail

cd "$(dirname "$0")"

# Sesuaikan jika binary PHP default server bukan 8.2+
# Contoh Domainesia/cPanel: /opt/cpanel/ea-php83/root/usr/bin/php
PHP="${PHP:-php}"
COMPOSER="${COMPOSER:-composer}"

echo "==> Routing ke public/ (.htaccess di root project)"
# Document root domain bisa mengarah ke folder project, bukan ke public/.
# Selalu ditulis ulang supaya konsisten; tidak berpengaruh jika docroot sudah ke public/.
cat > .htaccess <<'HTACCESS'
Options -Indexes
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Domain lama (salah ketik) -> domain utama, permanen
    RewriteCond %{HTTP_HOST} ^(www\.)?anekabarasiveandalan\.com$ [NC]
    RewriteRule ^(.*)$ https://anekaabrasiveandalan.com/$1 [R=301,L]

    RewriteRule ^$ public/ [L]
    RewriteRule ^(?!public/)(.*)$ public/$1 [L]
</IfModule>
HTACCESS

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
# Hapus symlink rusak (mis. menunjuk ke path lama) lalu buat ulang
if [ -L public/storage ] && [ ! -e public/storage ]; then rm -f public/storage; fi
[ -e public/storage ] || $PHP artisan storage:link
if [ ! -f public/storage/settings/.keep ] && [ -z "$(ls -A public/storage 2>/dev/null)" ]; then
  echo "!! public/storage kosong/rusak. Cek: ls -la public/storage  (gambar ada di storage/app/public)"
fi

echo "==> Permission"
chmod -R 775 storage bootstrap/cache

echo "==> Cache production"
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache

echo "==> Build aset (Vite)"
export NVM_DIR="${NVM_DIR:-$HOME/.nvm}"
if ! command -v npm >/dev/null 2>&1; then
  echo "==> npm belum ada, install Node via nvm (tanpa root, ke $NVM_DIR)"
  if [ ! -s "$NVM_DIR/nvm.sh" ]; then
    curl -fsSL https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash || true
  fi
  # shellcheck disable=SC1091
  [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" && nvm install 20 || true
fi

if command -v npm >/dev/null 2>&1; then
  npm ci
  npm run build
else
  echo "!! Install Node gagal, build dilewati."
fi

if [ ! -f public/build/manifest.json ]; then
  echo "!! public/build belum ada. Jalankan 'npm run build' di lokal lalu upload folder public/build."
fi

echo "==> Selesai"
