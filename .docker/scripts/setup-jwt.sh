#!/bin/sh

JWT_DIR="/var/www/html/storage/jwt"
PRIVATE_KEY_FILE="$JWT_DIR/rs256_private.pem"
PUBLIC_KEY_FILE="$JWT_DIR/rs256_public.pem"

if [ -z "$JWT_PRIVATE" ] || [ -z "$JWT_PUBLIC" ]; then
  echo "Error: JWT_PRIVATE or JWT_PUBLIC environment variables are not set."
  exit 1
fi

mkdir -p "$JWT_DIR"

if ! echo "$JWT_PRIVATE" | base64 -d > "$PRIVATE_KEY_FILE"; then
  echo "Error: Failed to decode JWT_PRIVATE."
  exit 1
fi

if ! echo "$JWT_PUBLIC" | base64 -d > "$PUBLIC_KEY_FILE"; then
  echo "Error: Failed to decode JWT_PUBLIC."
  exit 1
fi

chmod 600 "$PRIVATE_KEY_FILE" "$PUBLIC_KEY_FILE"
chown www-data:www-data "$PRIVATE_KEY_FILE" "$PUBLIC_KEY_FILE"
echo "JWT keys decoded and saved."
