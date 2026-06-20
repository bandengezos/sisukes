FROM composer:latest AS composer

FROM node:22-alpine AS frontend

WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM php:8.3-fpm-alpine AS build

# Install dependencies
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
    freetype-dev

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

# Generate app key for build (will be overridden by env)
RUN php artisan key:generate --force

# Optimize Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy supervisord config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create storage link script
RUN echo '#!/bin/sh' > /docker-entrypoint.sh \
    && echo 'php artisan storage:link --force' >> /docker-entrypoint.sh \
    && echo 'php artisan migrate --force' >> /docker-entrypoint.sh \
    && echo '/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf' >> /docker-entrypoint.sh \
    && chmod +x /docker-entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/docker-entrypoint.sh"]