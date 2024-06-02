#!/usr/bin/env bash

# Migrate DB before starting the main process
echo "Migrating database"
php artisan migrate --force

# Open the floodgates for incoming traffic
echo "Application is now starting up"
php-fpm