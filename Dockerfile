# Stage 1: Build Frontend
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run build


# Stage 2: PHP Laravel Application
FROM php:8.3-fpm AS app

# Install PHP dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libwebp-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    passwd \
    && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    xml \
    intl \
    zip \
    gd \
    opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


ARG WWWUSER=1000
ARG WWWGROUP=1000

RUN usermod -u ${WWWUSER} www-data \
    && groupmod -g ${WWWGROUP} www-data


# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


WORKDIR /var/www/html


# Composer dependency files
COPY composer.json composer.lock ./


# Install production dependencies
RUN composer install \
    --no-scripts \
    --no-autoloader \
    --ansi \
    --no-interaction \
    --no-dev


# Copy Laravel application
COPY . .


# Copy Vite build AFTER copying source
COPY --from=node-builder /app/public/build /var/www/html/public/build


# Optimize Composer autoloader
RUN composer dump-autoload \
    --optimize \
    --no-dev


# Create Laravel storage directories
RUN mkdir -p \
    /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache


# Permissions
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    && chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache


USER www-data

EXPOSE 9000

CMD ["php-fpm"]
# Stage 3: Nginx
FROM nginx:alpine AS nginx
# Nginx configuration
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
# Laravel public directory
COPY public /var/www/html/public
# Copy Vite production build
COPY --from=node-builder /app/public/build /var/www/html/public/build
# Create storage directory
RUN mkdir -p /var/www/html/storage/app/public