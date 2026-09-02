FROM php:8.2-fpm-alpine AS base

RUN apk add --no-cache \
    nginx \
    sqlite \
    sqlite-dev \
    supervisor \
    nodejs \
    npm \
    git \
    unzip \
    $PHPIZE_DEPS \
    && docker-php-ext-install pdo pdo_sqlite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist || true

COPY package.json package-lock.json* ./
RUN npm install || true

COPY . .

RUN composer dump-autoload --optimize || true
RUN npm run build || true

RUN mkdir -p /config/database /config/storage/app/public/backgrounds \
    && chown -R www-data:www-data /app /config

EXPOSE 80

CMD ["sh", "-c", "php artisan migrate --force && supervisord -c /app/docker/supervisord.conf"]
