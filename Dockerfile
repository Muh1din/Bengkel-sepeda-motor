# Stage 1: Build Node
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP Runtime
FROM php:8.3-fpm

# Install dependencies PHP
RUN apt-get update && apt-get install -y \
    git curl zip unzip libonig-dev libxml2-dev libzip-dev libicu-dev libwebp-dev \
    libpng-dev libjpeg-dev libfreetype6-dev passwd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql mbstring xml intl zip gd opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

ARG WWWUSER=1000
ARG WWWGROUP=1000
RUN usermod -u ${WWWUSER} www-data && groupmod -g ${WWWGROUP} www-data

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project & Build assets
COPY --from=node-builder /app/public/build /var/www/html/public/build
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader --ansi --no-interaction --no-dev

COPY . .

RUN composer dump-autoload --optimize --no-dev 

# Set Permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]