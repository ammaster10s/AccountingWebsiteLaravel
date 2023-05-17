FROM php:8.1.19-fpm

RUN apt-get update && apt-get -y install git zip libzip-dev wget libjpeg-dev libpng* \
zlib1g-dev unzip libfreetype6 libfreetype6-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli pdo_mysql zip gd
RUN docker-php-ext-configure opcache --enable-opcache
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1000:50:/' /etc/passwd

# Change www-data user to match the host system UID and GID and chown www directory
RUN usermod --non-unique --uid 1000 www-data \
  && groupmod --non-unique --gid 1000 www-data \
  && chown -R www-data:www-data /var/www

RUN chown www-data /var/www
RUN mkdir -p /var/www/vendor && chown www-data /var/www/vendor && chgrp www-data /var/www/vendor

ADD dockers/php.ini /usr/local/etc/php

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get install -y curl && \
  curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www

RUN ls

COPY composer.json ./
RUN composer install --no-scripts

COPY . .
RUN chmod +x artisan

RUN composer dump-autoload --optimize
# && composer run-script post-install-cmd