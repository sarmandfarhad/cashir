#!/usr/bin/env bash
# Idempotent repository bootstrap for the Cashir Laravel POS app.
# Runs after the repository is checked out. Safe to run repeatedly.
set -euo pipefail

cd "$(dirname "$0")/.."

# PHP dependencies.
composer install --no-interaction --no-progress --prefer-dist

# Application environment file + key (only generate a key when missing so the
# step stays idempotent and does not churn existing sessions).
if [ ! -f .env ]; then
    cp .env.example .env
fi
if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

# SQLite database file (default DB_CONNECTION in .env.example).
touch database/database.sqlite

# Schema + demo users. migrate only applies pending migrations and the seeder
# uses updateOrCreate, so both are safe to re-run.
php artisan migrate --force
php artisan db:seed --force

# Frontend dependencies + production asset build.
npm ci
npm run build
