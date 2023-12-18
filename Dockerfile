ARG NODE_VERSION=19
ARG PHP_VERSION=8.2

FROM mpopowicz/php:${PHP_VERSION}-apache

ARG NODE_VERSION

RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | sudo -E bash - \
    && apt-get update \
    && apt-get install -y --no-install-recommends nodejs

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install --optimize-autoloader --no-dev \
    && mkdir -p /var/www/html/storage/logs \
    && php artisan optimize:clear \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage
