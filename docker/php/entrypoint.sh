#!/usr/bin/env sh
set -e

cd /var/www/html

# Ожидаем доступности БД, если переменные заданы
if [ -n "${DB_HOST}" ] && [ -n "${DB_PORT}" ]; then
  echo "Waiting for database ${DB_HOST}:${DB_PORT}..."
  until nc -z ${DB_HOST} ${DB_PORT}; do
    sleep 1
  done
fi

# Установка зависимостей, если vendor пуст
if [ ! -d vendor ] || [ -z "$(ls -A vendor 2>/dev/null)" ]; then
  composer install --no-dev --prefer-dist --no-progress --no-interaction
fi

# Копируем .env при необходимости
if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

# Генерируем ключ приложения
php artisan key:generate --force || true

# Миграции (если нужно)
if [ "${RUN_MIGRATIONS}" = "true" ]; then
  php artisan migrate --force
fi

chown -R www-data:www-data storage bootstrap/cache
php-fpm -F


