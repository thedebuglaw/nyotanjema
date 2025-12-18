#!/bin/bash

echo "Fixing storage permissions..."

chown -R application:application /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

echo "Starting services..."
/opt/docker/bin/entrypoint.sh
