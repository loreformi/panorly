#!/bin/sh
set -eu

mkdir -p /config/database /config/storage/app/public/backgrounds
mkdir -p /app/bootstrap/cache /app/storage/framework/cache /app/storage/framework/sessions /app/storage/framework/views /app/storage/logs

touch /config/database/panorly.sqlite

# SQLite needs write access to both the database file and its parent directory.
chown -R www-data:www-data /config
chmod 775 /config /config/database /config/storage /config/storage/app /config/storage/app/public /config/storage/app/public/backgrounds
chmod 664 /config/database/panorly.sqlite
chmod -R ug+rwX /app/bootstrap/cache /app/storage

if [ -z "${APP_KEY:-}" ]; then
  echo "ERROR: APP_KEY is not set. Generate one with: php artisan key:generate --show"
  exit 1
fi

php artisan migrate --force

exec /usr/bin/supervisord -c /etc/supervisord.conf
