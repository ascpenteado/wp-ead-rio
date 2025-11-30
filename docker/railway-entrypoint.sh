#!/usr/bin/env bash
set -euo pipefail

# Configure Apache to listen on the PORT provided by the platform (Railway sets this).
PORT="${PORT:-8080}"

if [[ -f /etc/apache2/ports.conf ]]; then
  sed -ri "s/Listen [0-9]+/Listen ${PORT}/g" /etc/apache2/ports.conf
fi

if [[ -f /etc/apache2/sites-available/000-default.conf ]]; then
  sed -ri "s/<VirtualHost \\*:[0-9]+>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/000-default.conf
fi

exec docker-entrypoint.sh "$@"
