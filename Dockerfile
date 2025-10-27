# ---------- Stage 1: Build assets (Vite) ----------
FROM node:18-alpine AS assets
WORKDIR /app

# Copy file cần thiết cho npm install
COPY package.json package-lock.json* yarn.lock* pnpm-lock.yaml* ./
RUN npm ci

# Copy source để build Vite
COPY vite.config.* ./
COPY resources ./resources
RUN npm run build


# ---------- Stage 2: Composer (vendor) ----------
# FROM composer:2 AS vendor
# WORKDIR /app
# COPY composer.json composer.lock ./
# RUN composer install --no-dev --prefer-dist --no-progress --no-interaction

FROM php:8.2-cli AS vendor
WORKDIR /app

# Cài composer và các tiện ích cần thiết
# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     libzip-dev \
#  && docker-php-ext-install zip \
#  && rm -rf /var/lib/apt/lists/*

RUN apt-get update \
&& apt-get install -y --no-install-recommends git unzip libzip-dev \
&& docker-php-ext-install zip


# Cài Composer (trực tiếp từ getcomposer.org)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy file composer
COPY composer.json composer.lock ./

# Cài dependencies PHP
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --no-scripts

# ---------- Stage 3: Runtime (PHP-FPM + Nginx) ----------
FROM webdevops/php-nginx:8.2
WORKDIR /app

# Cấu hình Nginx & PHP
ENV WEB_DOCUMENT_ROOT=/app/public
EXPOSE 80

# Cài extension nếu cần
# RUN docker-php-ext-install pdo_mysql bcmath

# Copy vendor trước (tối ưu cache)
COPY --from=vendor /app/vendor ./vendor

# Copy toàn bộ code
COPY . .

# Copy asset build từ Vite (đúng thư mục public/build)
COPY --from=assets /app/public/build ./public/build

# Phân quyền thư mục
RUN chown -R application:application storage bootstrap/cache \
 && chmod -R ug+rwX storage bootstrap/cache

# USER application
