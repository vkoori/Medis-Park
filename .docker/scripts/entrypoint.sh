#!/bin/sh
set -e

# Run JWT key setup
/opt/scripts/setup-jwt.sh

# Reconfigure php
/opt/scripts/php-fpm.sh "$@"

# Reconfigure nginx
/opt/scripts/nginx.sh "$@"

# Start supervisor
/opt/scripts/supervisord.sh "$@"
