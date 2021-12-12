FROM php:8.0-apache

RUN apt-get update && apt install zip unzip

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN a2enmod rewrite && a2enmod actions

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

USER www-data
WORKDIR /var/www/html

COPY composer.* /var/www/html
RUN composer install

COPY . /var/www/html