#!/bin/sh

# Initialize variable
SUPERVISOR_CONFIG=""

# Parse arguments
for arg in "$@"; do
    case $arg in
        --supervisor_config=*)
            SUPERVISOR_CONFIG="${arg#*=}"
            ;;
        *)
            # Ignore all other args
            ;;
    esac
done

# Validate config path
if [ -z "$SUPERVISOR_CONFIG" ]; then
    echo "Error: --supervisor_config is required."
    echo "Usage: $0 --supervisor_config=<path_to_config>"
    exit 1
fi

if [ ! -f "$SUPERVISOR_CONFIG" ]; then
    echo "Error: Supervisor config file not found at: $SUPERVISOR_CONFIG"
    exit 1
fi

# Start supervisord
echo "Starting supervisord with config: $SUPERVISOR_CONFIG"
exec supervisord -c "$SUPERVISOR_CONFIG"
