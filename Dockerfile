# Stage 1: PHP Laravel Application
FROM php:8.3-fpm-alpine AS app

# 1. Install System Dependencies
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

# 3. Adjust User/Group ID www-data
ARG WWWUSER=1000
ARG WWWGROUP=1000
RUN usermod -u ${WWWUSER} www-data \
    && groupmod -g ${WWWGROUP} www-data

# 4. Copy Composer Executable
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 5. Install Composer Dependencies (Leveraging Docker Cache)
COPY composer.json composer.lock ./
RUN composer install \
    --no-scripts \
    --no-autoloader \
    --ansi \
    --no-interaction \
    --no-dev \
    --prefer-dist

# 6. Copy Entire Source Code (Termasuk folder public/build dari lokal)
COPY . .

# 7. Optimize Autoloader
RUN composer dump-autoload \
    --optimize \
    --no-dev \
    --classmap-authoritative

# 8. Create Storage Structure & Fix Ownership
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache
    
USER www-data

EXPOSE 9000

CMD ["php-fpm"]


# Stage 2: Nginx Web Server
FROM nginx:alpine AS nginx

# Copy Konfigurasi Nginx
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy Public Directory (Terdiri dari index.php, assets, dan build frontend)
COPY --from=app /var/www/html/public /var/www/html/public

# Pastikan folder storage publik dibuat untuk symlink
RUN mkdir -p /var/www/html/storage/app/public