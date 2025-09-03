FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

RUN sed -i 's|deb.debian.org/debian|archive.debian.org/debian|g' /etc/apt/sources.list \
  && sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list \
  && apt-get update \
  && apt-get install -y default-mysql-client zlib1g-dev libzip-dev unzip ca-certificates git \
  libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
  && update-ca-certificates \
  && docker-php-ext-install pdo_mysql zip \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install gd

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -fsSL https://getcomposer.org/download/2.6.6/composer.phar -o /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer \
  && composer --version

WORKDIR /var/www