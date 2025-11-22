#!/bin/sh

cp .env.example .env

echo "installing framework and deps"
composer install --no-interaction --prefer-dist
if [ $? -ne 0 ]; then
    echo "Composer installation failed."
    exit 1
fi

clear

echo "project setup"
php artisan key:generate
php artisan jwt:secret