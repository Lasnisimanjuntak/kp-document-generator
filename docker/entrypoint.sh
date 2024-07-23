#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    echo "Installing composer dependencies"
    composer install --no-progress --no-interaction
fi

if [ ! -f ".env" ]; then
    echo "Creating .env file"
    cp .env.example .env
fi

# run postgre
service postgresql start

# set up user & database
echo "CREATE USER $DB_USERNAME WITH PASSWORD '$DB_PASSWORD';" | su - postgres -c psql
echo "CREATE DATABASE $DB_DATABASE WITH OWNER $DB_USERNAME;" | su - postgres -c psql

php artisan migrate
php artisan cache:clear
php artisan config:clear
php artisan storage:link
php artisan key:generate

# intall dependencies
npm install

chmod +x node_modules/.bin/vite
npm run build


php-fpm -D
nginx -g "daemon off;"
