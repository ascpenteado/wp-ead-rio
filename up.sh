#!/bin/bash

# Exit on any error
set -e

# Cleanup function to stop DDEV when script is interrupted
cleanup() {
    echo ""
    echo "Shutting down DDEV containers..."
    cd web 2>/dev/null || true
    ddev stop 2>/dev/null || true
    echo "DDEV containers stopped."
    exit 0
}

# Trap SIGINT (Ctrl+C) and SIGTERM signals to run cleanup
trap cleanup SIGINT SIGTERM

# Navigate to WordPress folder
cd web

# Check if DDEV is running
if ! ddev status -j | grep -q '"status":"running"'; then
    echo "Starting DDEV..."
    ddev start
    # Wait for DDEV to be fully ready
    max_attempts=30
    attempt=1
    while ! ddev status -j | grep -q '"status":"running"'; do
        echo "Waiting for DDEV to be ready... (Attempt $attempt/$max_attempts)"
        if [ $attempt -ge $max_attempts ]; then
            echo "Error: DDEV failed to start within the timeout period"
            exit 1
        fi
        sleep 2
        attempt=$((attempt + 1))
    done
    echo "DDEV is now running"
else
    echo "DDEV is already running"
fi


