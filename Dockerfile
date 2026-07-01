# Use official PHP 8.3 CLI image
FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm

# Clear package list cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required for Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside container
WORKDIR /app

# Copy all project files into the container
COPY . .

# Run Composer installation
RUN composer install --no-dev --optimize-autoloader

# Run NPM installation and build Vite production assets
RUN npm install && npm run build

# Make sure storage and cache directories have write permissions
RUN chmod -R 777 /app/storage /app/bootstrap/cache

# Expose port 8000
EXPOSE 8000

# Set start command
CMD touch database/database.sqlite && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
