#!/bin/sh

echo "installing framework and deps"
composer install --no-interaction --prefer-dist
if [ $? -ne 0 ]; then
    echo "Composer installation failed."
    exit 1
fi

clear

echo "project setup"
php artisan key:generate
if [ $? -ne 0 ]; then
    echo "can't create key:generate"
    exit 1
fi

clear

echo "DB migration & seeding"
php artisan migrate:fresh --seed
if [ $? -ne 0 ]; then
    echo "DB setup failed"
    exit 1
fi

clear

echo "JWT setup"
php artisan jwt:secret
if [ $? -ne 0 ]; then
    echo "JWT secret key issuance failed."
    exit 1
fi