FROM php:8-fpm-alpine

RUN apk add --no-cache composer

COPY composer.* /composer/
RUN composer install --no-dev --working-dir=/composer

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
#RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
