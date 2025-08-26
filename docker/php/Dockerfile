FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

RUN sed -i 's|deb.debian.org/debian|archive.debian.org/debian|g' /etc/apt/sources.list \
  && sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list \
  && apt-get update \
  && apt-get install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
  && docker-php-ext-install pdo_mysql zip \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www