# # ---------- Stage 1: Build assets (Vite) ----------
# FROM node:18-alpine AS assets
# WORKDIR /app

# # Copy file cần thiết cho npm install
# COPY package.json package-lock.json* yarn.lock* pnpm-lock.yaml* ./
# RUN npm ci

# # Copy source để build Vite
# COPY vite.config.* ./
# COPY resources ./resources
# RUN npm run build


# # ---------- Stage 2: Composer (vendor) ----------
# # FROM composer:2 AS vendor
# # WORKDIR /app
# # COPY composer.json composer.lock ./
# # RUN composer install --no-dev --prefer-dist --no-progress --no-interaction

# FROM php:8.2-cli AS vendor
# WORKDIR /app

# # Cài composer và các tiện ích cần thiết
# # RUN apt-get update && apt-get install -y \
# #     git \
# #     unzip \
# #     libzip-dev \
# #  && docker-php-ext-install zip \
# #  && rm -rf /var/lib/apt/lists/*

# RUN apt-get update \
# && apt-get install -y --no-install-recommends git unzip libzip-dev \
# && docker-php-ext-install zip


# # Cài Composer (trực tiếp từ getcomposer.org)
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# # Copy file composer
# COPY composer.json composer.lock ./

# # Cài dependencies PHP
# RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --no-scripts

# # ---------- Stage 3: Runtime (PHP-FPM + Nginx) ----------
# FROM webdevops/php-nginx:8.2
# WORKDIR /app

# # Cấu hình Nginx & PHP
# ENV WEB_DOCUMENT_ROOT=/app/public
# EXPOSE 80

# # Cài extension nếu cần
# # RUN docker-php-ext-install pdo_mysql bcmath

# # Copy vendor trước (tối ưu cache)
# COPY --from=vendor /app/vendor ./vendor

# # Copy toàn bộ code
# COPY . .

# # Copy asset build từ Vite (đúng thư mục public/build)
# COPY --from=assets /app/public/build ./public/build

# # Phân quyền thư mục
# RUN chown -R application:application storage bootstrap/cache \
#  && chmod -R ug+rwX storage bootstrap/cache

# # USER application


FROM php:8.3.11-fpm

# Cài dependency
RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev postgresql-client libpq-dev \
    nodejs npm git unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# PHP Extensions
RUN docker-php-ext-install pdo pgsql pdo_pgsql gd bcmath zip \
    && pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /app

# Copy code
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && php artisan package:discover --ansi \
    && mkdir -p storage/framework/{sessions,views,cache} \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Port cho Render
EXPOSE 80

# Start app (Render cần port 80)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
