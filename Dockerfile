FROM php:8.3-fpm-alpine AS build

RUN apk add --no-cache \
    sqlite-dev \
    nodejs \
    npm \
    git \
    unzip \
    $PHPIZE_DEPS \
    && docker-php-ext-install pdo_sqlite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts

COPY package.json ./
RUN npm install --no-audit --no-fund

COPY . .

RUN composer dump-autoload --optimize --no-interaction \
    && npm run build

FROM php:8.3-fpm-alpine AS runtime

RUN apk add --no-cache nginx sqlite supervisor

WORKDIR /app

COPY --from=build /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=build /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=build /app /app
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/panorly-entrypoint

RUN chmod +x /usr/local/bin/panorly-entrypoint \
    && mkdir -p /config/database /config/storage/app/public/backgrounds \
        /app/storage/framework/cache /app/storage/framework/sessions \
        /app/storage/framework/views /app/storage/logs \
    && touch /config/database/panorly.sqlite \
    && chown -R www-data:www-data /app /config

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/panorly-entrypoint"]
