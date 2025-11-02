#!/usr/bin/env bash
set -euo pipefail

echo "[setup] CyberCore environment bootstrap starting..."

# Show tool versions for diagnostics (non-fatal if missing)
php -v || true
composer --version || true
node -v || true
npm -v || true

echo "[setup] Installing PHP dependencies (composer install)"
composer install \
  --no-interaction \
  --prefer-dist \
  --no-progress

if [ ! -f .env ]; then
  echo "[setup] Creating .env from .env.example"
  cp .env.example .env
fi

echo "[setup] Generating APP_KEY if missing"
php artisan key:generate --ansi || true

# Ensure sqlite file exists when using sqlite (default in .env.example)
if grep -q "^DB_CONNECTION=sqlite" .env; then
  echo "[setup] Ensuring sqlite database exists"
  mkdir -p database
  : > database/database.sqlite
fi

echo "[setup] Running database migrations and seeders"
php artisan migrate --seed --force

echo "[setup] Setting writable permissions for storage and cache"
chmod -R u+rwX,go+rwX storage bootstrap/cache || true

echo "[setup] Installing Node dev dependencies"
npm ci || npm install

if [ "${BUILD:-1}" != "0" ]; then
  echo "[setup] Building frontend assets (vite build)"
  npm run build
else
  echo "[setup] Skipping asset build because BUILD=0"
fi

echo "[setup] Done. Useful commands:"
echo "  - composer dev           # run app + queue + logs + vite"
echo "  - php artisan serve      # run PHP server on :8000"
echo "  - npm run dev            # run Vite dev server (port 5173)"
echo "  - php artisan test       # run backend tests"

