FROM composer:latest AS composer

FROM node:22-alpine AS frontend

WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM php:8.3-fpm-alpine AS base

# Install dependencies (including PostgreSQL dev headers)
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    zip \
    unzip \
    git \
    oniguruma-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    postgresql-dev \
    postgresql-libs

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    zip \
    gd \
    exif \
    pcntl \
    bcmath

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Copy Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# Copy frontend assets
COPY --from=frontend /app/public/build /app/public/build

# Install dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy supervisord config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create entrypoint script (runs at container start)
RUN echo '#!/bin/sh' > /docker-entrypoint.sh \
    && echo 'set -e' >> /docker-entrypoint.sh \
    && echo '' >> /docker-entrypoint.sh \
    && echo '# Generate APP_KEY if not set' >> /docker-entrypoint.sh \
    && echo 'if [ -z "$APP_KEY" ]; then' >> /docker-entrypoint.sh \
    && echo '    php artisan key:generate --force' >> /docker-entrypoint.sh \
    && echo 'fi' >> /docker-entrypoint.sh \
    && echo '' >> /docker-entrypoint.sh \
    && echo '# Optimize Laravel (cache config, routes, views)' >> /docker-entrypoint.sh \
    && echo 'php artisan optimize' >> /docker-entrypoint.sh \
    && echo '' >> /docker-entrypoint.sh \
    && echo '# Create storage symlink' >> /docker-entrypoint.sh \
    && echo 'php artisan storage:link --force 2>/dev/null || true' >> /docker-entrypoint.sh \
    && echo '' >> /docker-entrypoint.sh \
    && echo '# Run migrations' >> /docker-entrypoint.sh \
    && echo 'php artisan migrate --force 2>/dev/null || true' >> /docker-entrypoint.sh \
    && echo '' >> /docker-entrypoint.sh \
    && echo '# Start supervisor' >> /docker-entrypoint.sh \
    && echo 'exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf' >> /docker-entrypoint.sh \
    && chmod +x /docker-entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/docker-entrypoint.sh"]