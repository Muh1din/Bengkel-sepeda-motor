# Stage 1: Build Frontend (Vite/Mix)
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package*.json ./

# Pakai --no-audit dan --no-fund untuk mempercepat install & hemat RAM saat build
RUN npm ci --no-audit --no-fund

COPY . .

RUN npm run build

# Stage 2: PHP Laravel Application
FROM php:8.3-fpm-alpine AS app

# 1. Gunakan Alpine Linux alih-alih Debian (Apt).
# Ini memotong ukuran image dari ~500MB menjadi ~80MB & hemat RAM!
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    shadow

# 2. Configure & Install PHP Extensions
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mbstring \
    xml \
    intl \
    zip \
    gd \
    opcache

# Modifikasi UID/GID www-data
ARG WWWUSER=1000
ARG WWWGROUP=1000
RUN usermod -u ${WWWUSER} www-data \
    && groupmod -g ${WWWGROUP} www-data

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy Composer dependencies terlebih dahulu (Caching Layer)
COPY composer.json composer.lock ./

# Install Composer Dependencies (Optimized for Production)
RUN composer install \
    --no-scripts \
    --no-autoloader \
    --ansi \
    --no-interaction \
    --no-dev \
    --prefer-dist

# Copy Source Code Aplikasi
COPY . .

# Copy Frontend Assets dari Stage 1
COPY --from=node-builder /app/public/build /var/www/html/public/build

# Optimize Autoloader
RUN composer dump-autoload \
    --optimize \
    --no-dev \
    --classmap-authoritative

# Buat direktori storage dan sesuaikan hak akses
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]


# Stage 3: Nginx Web Server
FROM nginx:alpine AS nginx

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=app /var/www/html/public /var/www/html/public
COPY --from=node-builder /app/public/build /var/www/html/public/build

RUN mkdir -p /var/www/html/storage/app/public