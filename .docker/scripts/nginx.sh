#!/bin/sh

# Config file paths
NGINX_CONF_DIR="/etc/nginx/http.d"

# Initialize variables
NGINX_CONF=""

# Parse arguments
for arg in "$@"; do
    case $arg in
        --nginx_conf=*)
            NGINX_CONF="${arg#*=}"
            shift
            ;;
        *)
            # Ignore all other args
            ;;
    esac
done

# Validate required arguments
if [ -z "$NGINX_CONF" ]; then
    echo "Error: --nginx_config is required."
    echo "Usage: $0 --nginx_conf=prod.conf|dev.conf"
    exit 1
fi

# Rename .conf.old to .conf
if [ -n "$NGINX_CONF" ]; then
    CONF_FILE="$NGINX_CONF_DIR/${NGINX_CONF}.old"
    TARGET_FILE="$NGINX_CONF_DIR/default.conf"

    if [ -f "$CONF_FILE" ]; then
        mv "$CONF_FILE" "$TARGET_FILE"
        echo "Renamed: $CONF_FILE -> $TARGET_FILE"
    else
        echo "Warning: File not found: $CONF_FILE"
    fi
fi
