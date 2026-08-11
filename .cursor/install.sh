#!/usr/bin/env bash
# Idempotent bootstrap for the Cashir Laravel POS Cloud Agent environment.
#
# Runs from the repository root after checkout (and whenever dependencies are
# refreshed). It is self-contained: it provisions the language toolchain that
# the default base image lacks, then installs the application dependencies and
# generated state. Every step is guarded so re-runs are safe and fast.
set -euo pipefail

cd "$(dirname "$0")/.."

export DEBIAN_FRONTEND=noninteractive

# --- PHP 8.4 -----------------------------------------------------------------
# Ubuntu 24.04 ships only PHP 8.3, but the locked Symfony 8.1 packages require
# PHP >= 8.4.1, so pull PHP 8.4 from the ondrej/php PPA when it is missing.
if ! command -v php >/dev/null 2>&1 || ! php -r 'exit(version_compare(PHP_VERSION, "8.4.0", ">=") ? 0 : 1);'; then
    sudo apt-get update -y
    sudo apt-get install -y --no-install-recommends software-properties-common ca-certificates curl gnupg
    sudo add-apt-repository -y ppa:ondrej/php
    sudo apt-get update -y
    sudo apt-get install -y --no-install-recommends \
        php8.4-cli \
        php8.4-sqlite3 \
        php8.4-mbstring \
        php8.4-xml \
        php8.4-curl \
        php8.4-gd \
        php8.4-bcmath \
        php8.4-intl \
        php8.4-zip
    sudo update-alternatives --set php /usr/bin/php8.4
fi

# --- Composer ----------------------------------------------------------------
if ! command -v composer >/dev/null 2>&1; then
    expected_sig="$(curl -fsSL https://composer.github.io/installer.sig)"
    curl -fsSL https://getcomposer.org/installer -o /tmp/composer-setup.php
    actual_sig="$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")"
    if [ "$expected_sig" != "$actual_sig" ]; then
        echo "ERROR: Composer installer signature mismatch" >&2
        rm -f /tmp/composer-setup.php
        exit 1
    fi
    sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm -f /tmp/composer-setup.php
fi

# --- Node.js 22 --------------------------------------------------------------
# Vite 8 requires Node >= 20.19 / 22.12. Install Node 22 from NodeSource only
# when a suitable version is not already present on the base image.
if ! command -v node >/dev/null 2>&1 || [ "$(node -p 'process.versions.node.split(".")[0]')" -lt 22 ]; then
    curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
    sudo apt-get install -y nodejs
fi

# --- Application bootstrap ---------------------------------------------------
composer install --no-interaction --no-progress --prefer-dist

if [ ! -f .env ]; then
    cp .env.example .env
fi
if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

# SQLite database (default DB_CONNECTION in .env.example).
touch database/database.sqlite

# Schema + demo users. migrate only applies pending migrations and the seeder
# uses updateOrCreate, so both are safe to re-run.
php artisan migrate --force
php artisan db:seed --force

# Frontend dependencies + production asset build.
npm ci
npm run build
