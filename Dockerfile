# Stage 1: PHP Laravel Application
FROM php:8.3-fpm-alpine AS app

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

ARG WWWUSER=1000
ARG WWWGROUP=1000
RUN usermod -u ${WWWUSER} www-data \
    && groupmod -g ${WWWGROUP} www-data

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-scripts \
    --no-autoloader \
    --ansi \
    --no-interaction \
    --no-dev \
    --prefer-dist

COPY . .

RUN composer dump-autoload \
    --optimize \
    --no-dev \
    --classmap-authoritative

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


# Stage 2: Nginx Web Server
FROM nginx:alpine AS nginx

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=app /var/www/html/public /var/www/html/public

RUN mkdir -p /var/www/html/storage/app/public