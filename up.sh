#!/bin/bash

# Exit on any error
set -e

# Variable to store npm process ID
NPM_PID=""

# Cleanup function to stop DDEV and npm when script is interrupted
cleanup() {
    echo ""
    echo "Shutting down services..."

    # Kill npm process if it's running
    if [ -n "$NPM_PID" ] && kill -0 "$NPM_PID" 2>/dev/null; then
        echo "Stopping npm run dev (PID: $NPM_PID)..."
        kill "$NPM_PID" 2>/dev/null || true
        wait "$NPM_PID" 2>/dev/null || true
        echo "npm run dev stopped."
    else
        # Fallback: try to kill any npm run dev processes
        pkill -f "npm run dev" 2>/dev/null || true
    fi

    # Stop DDEV
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

# Always check and start npm run dev if needed (regardless of DDEV startup state)
if ! pgrep -f "npm run dev" > /dev/null; then
    echo "Starting npm run dev..."
    # go to theme folder
    cd wp-content/themes/ead-rio
    npm run dev &
    NPM_PID=$!
    echo "npm run dev started with PID: $NPM_PID"
else
    echo "npm run dev is already running."
    # Get the existing PID for cleanup purposes
    NPM_PID=$(pgrep -f "npm run dev" | head -1)
    echo "Found existing npm run dev process with PID: $NPM_PID"
fi

# Keep the script running to handle Ctrl+C properly
echo ""
echo "Services are running. Press Ctrl+C to stop all services and exit."
echo "DDEV site: https://wp-ead-rio.ddev.site"
echo ""

# Wait indefinitely until interrupted
while true; do
    sleep 1
done
