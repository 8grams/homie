FROM php:8.3.6-apache-bookworm

RUN apt-get update && apt-get install -y libzip-dev libsqlite3-dev git curl
COPY --from=composer:2.8.5 /usr/bin/composer /usr/bin/composer

COPY . .

RUN COMPOSER_PROCESS_TIMEOUT=600 composer install --prefer-dist --no-dev --no-interaction --no-progress --no-suggest
RUN a2enmod rewrite
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /var/www/html

ENTRYPOINT ["apache2-foreground"]
