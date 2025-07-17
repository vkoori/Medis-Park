#!/bin/sh

# Config file paths
WWW_CONF="/usr/local/etc/php-fpm.d/www.conf"
ZZ_DOCKER_CONF="/usr/local/etc/php-fpm.d/zz-docker.conf"
PHP_INI_DIR="/usr/local/etc/php/conf.d"

# Initialize variables
MAX_CHILDREN=""
REQ_TERMINATE_TIMEOUT=""
ENABLE_INI=""

# Parse arguments
for arg in "$@"; do
    case $arg in
        --max_children=*)
            MAX_CHILDREN="${arg#*=}"
            shift
            ;;
        --request_terminate_timeout=*)
            REQ_TERMINATE_TIMEOUT="${arg#*=}"
            shift
            ;;
        --enable_ini=*)
            ENABLE_INI="${arg#*=}"
            shift
            ;;
        *)
            # Ignore all other args
            ;;
    esac
done

# Validate required arguments
if [ -z "$MAX_CHILDREN" ] || [ -z "$REQ_TERMINATE_TIMEOUT" ] || [ -z "$ENABLE_INI" ]; then
    echo "Error: All three arguments are required."
    echo "Usage: $0 --max_children=<value> --request_terminate_timeout=<value> --enable_ini=web-prod|subscriber-prod|scheduler-prod"
    exit 1
fi

# Rename .ini.old to .ini
if [ -n "$ENABLE_INI" ]; then
    INI_FILE="$PHP_INI_DIR/${ENABLE_INI}.ini.old"
    TARGET_FILE="$PHP_INI_DIR/${ENABLE_INI}.ini"

    if [ -f "$INI_FILE" ]; then
        mv "$INI_FILE" "$TARGET_FILE"
        echo "Renamed: $INI_FILE -> $TARGET_FILE"
    else
        echo "Warning: File not found: $INI_FILE"
    fi
fi

# Update pm.max_children
if grep -q "^pm\.max_children" "$WWW_CONF"; then
    sed -i "s/^pm\.max_children = .*/pm.max_children = $MAX_CHILDREN/" "$WWW_CONF"
else
    echo "pm.max_children = $MAX_CHILDREN" >> "$WWW_CONF"
fi

# Update request_terminate_timeout
sed -i '/^request_terminate_timeout/d' "$ZZ_DOCKER_CONF"
cat <<EOF >> "$ZZ_DOCKER_CONF"

; Added by php-fpm.sh
request_terminate_timeout = $REQ_TERMINATE_TIMEOUT
EOF
