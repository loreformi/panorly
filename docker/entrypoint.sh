#!/bin/sh
set -eu

mkdir -p /config/database /config/storage/app/public/backgrounds
mkdir -p /app/storage/framework/cache /app/storage/framework/sessions /app/storage/framework/views /app/storage/logs

touch /config/database/panorly.sqlite

if [ -z "${APP_KEY:-}" ]; then
  echo "ERROR: APP_KEY is not set. Generate one with: php artisan key:generate --show"
  exit 1
fi

php artisan migrate --force

exec /usr/bin/supervisord -c /etc/supervisord.conf
