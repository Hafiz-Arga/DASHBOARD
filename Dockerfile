FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json ./
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./

RUN npm install --no-audit --no-fund \
    && npm run build

FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends libonig-dev libzip-dev unzip \
    && docker-php-ext-install mbstring pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php-uploads.ini /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 8080

ENTRYPOINT ["sh", "docker/entrypoint.sh"]
