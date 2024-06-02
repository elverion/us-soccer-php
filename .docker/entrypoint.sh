#!/usr/bin/env bash

# Migrate DB before starting the main process
php artisan migrate --force

# Open the floodgates for incoming traffic
php-fpm