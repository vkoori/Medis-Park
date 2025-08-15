#!/bin/sh
set -e

if ! command -v redis-server >/dev/null 2>&1; then
  exit 0
fi

DATA_DIR="/data"
mkdir -p "$DATA_DIR"
chown redis:redis "$DATA_DIR" 2>/dev/null || true

redis-server --dir "$DATA_DIR" --appendonly yes --protected-mode yes &

while ! redis-cli -h 127.0.0.1 ping >/dev/null 2>&1; do
  sleep 1
done

echo "Redis is ready."
