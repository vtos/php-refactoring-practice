FROM php:7.4

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

WORKDIR /usr/apps/php/down_payment_calculator

COPY ./ ./

RUN apt-get update \
    && apt-get -y install git \
    && apt-get -y install unzip \
    && chmod +x /usr/local/bin/install-php-extensions \
    && sync \
    && install-php-extensions zip \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
