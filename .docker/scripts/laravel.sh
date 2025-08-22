#!/bin/sh

php artisan optimize
php artisan migrate --force
php artisan db:seed --force
php artisan module:seed User Notification Reward Product --force
