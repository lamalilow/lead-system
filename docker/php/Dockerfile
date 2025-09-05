# syntax=docker/dockerfile:1

FROM php:8.2-fpm

# Установим системные зависимости и расширения PHP для Laravel + MySQL
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        netcat-openbsd \
        libpq-dev \
        libonig-dev \
        libzip-dev \
        libicu-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip intl xml \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

# Установим Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Используем отдельные слои для зависимостей
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction || true

# Копируем исходники
COPY . .

# Повторная установка после копирования (на случай наличия post-scripts)
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Конфигурация PHP (опционально можно добавить свой ini)
# COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

EXPOSE 9000

CMD ["php-fpm"]


