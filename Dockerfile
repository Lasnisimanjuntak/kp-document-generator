FROM php:8.2-fpm as php

ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_ENABLE_CLI=0
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=1
ENV PHP_OPCACHE_REVALIDATE_FREQ=1

RUN usermod -u 1000 www-data

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y unzip libpq-dev libcurl4-gnutls-dev nginx zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev libzip-dev
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql bcmath curl opcache gd exif

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

RUN apt-get install -y postgresql postgresql-contrib && \
  docker-php-ext-install pdo_pgsql

ENV DB_USERNAME=itl_pguser
ENV DB_PASSWORD=itl_pgpwd
ENV DB_DATABASE=itl

WORKDIR /var/www

COPY --chown=www-data . .

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

COPY --from=composer:2.5.5 /usr/bin/composer /usr/bin/composer

RUN chmod -R 755 /var/www/storage
RUN chmod -R 755 /var/www/bootstrap

ENTRYPOINT [ "docker/entrypoint.sh" ]
